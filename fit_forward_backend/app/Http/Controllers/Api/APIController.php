<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BmiCategory;
use App\Models\MealPlan;
use App\Models\User;
use App\Models\UserWorkout;
use App\Models\WorkoutPlan;
use App\Models\WorkoutPlanExercise;
use Carbon\Carbon;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function user() {
        $user = Auth()->user();
        $completedWorkouts = $user->completedWorkouts()->pluck('exercise_id');
        return response()->json([
            'user' => $user,
            'completedWorkouts' => $completedWorkouts
        ]);
    }

    public function plans() 
    {
        $id = Auth()->user()->id;
        $user = User::findOrFail($id);
        $bmiCategory = BmiCategory::where('min', '<=', $user->bmi)
                                ->where('max', '>=', $user->bmi)
                                ->first();
        $workoutPlans = WorkoutPlan::where('bmi_category_id', $bmiCategory->id)->get();
        $exercises = WorkoutPlanExercise::whereIn(
            'workout_plan_id',
            $workoutPlans->pluck('id')
        )->with('exercise')->get();

        $meal_plan = MealPlan::where('bmi_category_id', $bmiCategory->id)->first();

        $daily_calories = $this->calculateDailyCalories($user->gender, $user->weight, $user->height, $user->age, $user->bmi, $user->activity_level);

        return response()->json([
            'user'          => $user,
            'exercises'     => $exercises,
            'meal_plan'     => $meal_plan,
            'daily_calories'=> $daily_calories 
        ]);
    }

    public function calculateDailyCalories($gender, $weight, $height, $age, $bmi, $activityLevel)
    {
        $bmr = $gender == 1
            ? (10 * $weight) + (6.25 * $height) - (5 * $age) + 5
            : (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;

        $activityFactors = [
            1 => 1.2,
            2 => 1.375,
            3 => 1.55,
            4 => 1.725,
            5 => 1.9,
        ];

        $tdee = $bmr * ($activityFactors[$activityLevel] ?? 1.2);

        if ($bmi < 18.5) {
            $goal = 'muscle_gain';
        } elseif ($bmi > 25) {
            $goal = 'fat_loss';
        } else {
            $goal = 'maintenance';
        }        

        $goalMultiplier = match ($goal) {
            'fat_loss' => 0.8,
            'muscle_gain' => 1.15,
            'maintenance' => 1.0,
        };

        return round($tdee * $goalMultiplier);
    }

    public function workout_complete(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'exercise_id' => 'required|exists:exercises,id',
            'workout_plan_id' => 'required|exists:workout_plans,id',
            'completed' => 'required|boolean',
        ]);
    
        $userWorkout = UserWorkout::create($request->all());
    
        return response()->json($userWorkout);
    }

    public function user_progress()
    {
        $user = Auth()->user();

        $streak = $this->getUserStreak($user);
        $planName = $this->getUserPlanName($user);
        $goalProgress = $this->getDailyGoalProgress($user);
        $progress = $this->calculateGoalProgress($goalProgress);
        $weeklyData = $this->getWeeklyWorkoutData($user);

        return response()->json([
            'streak' => $streak,
            'plan' => $planName,
            'goal' => $goalProgress,
            'progress' => $progress,
            'weeklyData' => $weeklyData
        ]);
    }

    private function getUserStreak($user)
    {
        $logs = UserWorkout::where('user_id', $user->id)
            ->where('completed', true)
            ->selectRaw('DATE(created_at) as date, workout_plan_id, COUNT(*) as completed_count')
            ->groupBy('date', 'workout_plan_id')
            ->get();

        $groupedByDate = $logs->groupBy('date');
        $streak = 0;
        $today = Carbon::today();

        while ($groupedByDate->has($today->toDateString())) {
            $plansToday = $groupedByDate[$today->toDateString()];
            $allCompleted = $plansToday->every(function ($log) {
                $totalExercises = WorkoutPlanExercise::where('workout_plan_id', $log->workout_plan_id)->count();
                return $totalExercises > 0 && $log->completed_count >= $totalExercises;
            });

            if ($allCompleted) {
                $streak++;
                $today->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function getUserPlanName($user)
    {
        $latestLog = UserWorkout::where('user_id', $user->id)->latest()->first();
        if ($latestLog) {
            $plan = WorkoutPlan::find($latestLog->workout_plan_id);
            return $plan->title ?? null;
        }
        return null;
    }

    private function getDailyGoalProgress($user)
    {
        $today = Carbon::today()->toDateString();
        $logsToday = UserWorkout::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->get();

        $completedToday = $logsToday->where('completed', true)->count();

        $bmiCategory = BmiCategory::where('min', '<=', $user->bmi)
            ->where('max', '>=', $user->bmi)
            ->first();

        $workoutPlan = WorkoutPlan::where('bmi_category_id', $bmiCategory->id)->first();
        $totalToday = WorkoutPlanExercise::where('workout_plan_id', $workoutPlan->id)->count();

        return [
            'total' => $totalToday,
            'completed' => $completedToday
        ];
    }

    private function calculateGoalProgress($goal)
    {
        return $goal['total'] > 0 ? round(($goal['completed'] / $goal['total']) * 100) : 0;
    }

    private function getWeeklyWorkoutData($user)
    {
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        $logs = UserWorkout::where('user_id', $user->id)
            ->where('completed', true)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, DAYNAME(created_at) as day_name, COUNT(*) as workouts')
            ->groupBy('date', 'day_name')
            ->orderBy('date')
            ->get();

        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days->push([
                'date' => $date->toDateString(),
                'day' => $date->format('D'),
                'workouts' => 0
            ]);
        }

        return $last7Days->map(function ($day) use ($logs) {
            $log = $logs->firstWhere('date', $day['date']);
            return [
                'day' => $day['day'],
                'workouts' => $log ? $log->workouts : 0
            ];
        });
    }

}
