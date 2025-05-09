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
        $completedToday = $user->completedWorkouts()
            ->whereDate('created_at', Carbon::today())
            ->pluck('exercise_id');
        return response()->json([
            'user' => $user,
            'completedWorkouts' => $completedToday
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
        $exercises = WorkoutPlanExercise::whereIn('workout_plan_id', $workoutPlans->pluck('id'))
                        ->with('exercise')
                        ->get();

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

        $hour = Carbon::now()->hour;

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'Good Afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        $streak = $this->getUserStreak($user);
        $planName = $this->getUserPlanName($user);
        $goalProgress = $this->getDailyGoalProgress($user);
        $progress = $this->calculateGoalProgress($goalProgress);
        $weeklyData = $this->getWeeklyWorkoutData($user);
        $achievements = $this->getUserAchievements($user);

        return response()->json([
            'user_name' => $user->name,
            'greeting' => $greeting,
            'streak' => $streak,
            'plan' => $planName,
            'goal' => $goalProgress,
            'progress' => $progress,
            'weeklyData' => $weeklyData,
            'achievements' => $achievements
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
        $yesterday = Carbon::yesterday();
    
        $checkStreakDay = function ($date) use ($groupedByDate) {
            $logsForDay = $groupedByDate->get($date->toDateString(), collect());
    
            if ($logsForDay->isEmpty()) return false;
    
            return $logsForDay->every(function ($log) {
                $totalExercises = WorkoutPlanExercise::where('workout_plan_id', $log->workout_plan_id)->count();
                return $totalExercises > 0 && $log->completed_count >= $totalExercises;
            });
        };
    
        $isTodayComplete = $checkStreakDay($today);
        $isYesterdayComplete = $checkStreakDay($yesterday);
    
        // Check from today backwards until streak breaks
        $current = $today->copy();
        while ($checkStreakDay($current)) {
            $streak++;
            $current->subDay();
        }
    
        // If today is not completed, but yesterday is streak = 1
        if (!$isTodayComplete && $isYesterdayComplete) {
            $streak = 1;
        }
    
        return $streak;
    }    

    private function getUserPlanName($user)
    {
        $bmiCategory = BmiCategory::where('min', '<=', $user->bmi)
            ->where('max', '>=', $user->bmi)
            ->first();
        $plan = WorkoutPlan::where('bmi_category_id', $bmiCategory->id)->first();
            
        return $plan->title ?? 'Full Body Workout';
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
                'date' => $day['date'],
                'day'  => $day['day'],
                'workouts' => $log ? $log->workouts : 0
            ];
        });
    }

    private function getUserAchievements($user)
    {
        $bmiCategory = BmiCategory::where('min', '<=', $user->bmi)
            ->where('max', '>=', $user->bmi)
            ->first();
        $workout_plan_id = WorkoutPlan::where('bmi_category_id', $bmiCategory->id)->value('id');
        
        $totalWorkouts = UserWorkout::where('user_id', $user->id)->count();
        $streak = $this->getUserStreak($user);
        $requiredTypes = WorkoutPlanExercise::where('workout_plan_id', $workout_plan_id)
                    ->pluck('exercise_id')
                    ->unique()
                    ->toArray();
        $doneTypes = UserWorkout::where('user_id', $user->id)
                    ->whereIn('exercise_id', $requiredTypes)
                    ->pluck('exercise_id')
                    ->unique()
                    ->count();

        $achievements = [];

        if ($totalWorkouts >= 1) $achievements[] = 'first_workout';
        if ($totalWorkouts >= 5) $achievements[] = 'workout_5';
        if ($streak >= 3) $achievements[] = 'streak_3';
        if ($doneTypes >= 3) $achievements[] = 'variety';
        if ($totalWorkouts >= 10) $achievements[] = 'workout_10';

        return $achievements;
    }
}
