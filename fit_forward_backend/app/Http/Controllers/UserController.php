<?php

namespace App\Http\Controllers;

use App\Models\BmiCategory;
use App\Models\MealPlan;
use App\Models\User;
use App\Models\UserWorkout;
use App\Models\WorkoutPlan;
use App\Models\WorkoutPlanExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function plans($id) 
    {
        $user = User::findOrFail($id);
        $bmiCategory = BmiCategory::where('min', '<=', $user->bmi)
                                ->where('max', '>=', $user->bmi)
                                ->first();
        $workoutPlans = WorkoutPlan::where('bmi_category_id', $bmiCategory->id)->get();
        $exercises = WorkoutPlanExercise::whereIn(
            'workout_plan_id',
            $workoutPlans->pluck('id')
        )->get();

        $meal_plan = MealPlan::where('bmi_category_id', $bmiCategory->id)->first();

        $daily_calories = $this->calculateDailyCalories($user->gender, $user->weight, $user->height, $user->age, $user->bmi, $user->activity_level);

        return view('users.plans', compact('user', 'exercises', 'meal_plan', 'daily_calories'));
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

    public function logs($id)
    {
        $logs = UserWorkout::where('user_id', $id)->get();
        return view('users.logs', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'name'           => 'required|string|max:255',
        //     'email'          => 'required|email|unique:users,email',
        //     'password'       => 'required|string|min:6',
        //     'age'            => 'required|integer|min:1',
        //     'gender'         => 'required|integer|in:0,1',
        //     'height'         => 'required|numeric|min:20',
        //     'weight'         => 'required|numeric|min:20',
        //     'activity_level' => 'required|integer|min:1|max:5',
        // ]);

        // // Calculate BMI
        // $heightInMeters = $validated['height'] / 100;
        // $bmi = $validated['weight'] / ($heightInMeters * $heightInMeters);
        // $bmi = round($bmi, 2);

        // // Create user
        // $user = User::create([
        //     'name'           => $validated['name'],
        //     'email'          => $validated['email'],
        //     'password'       => Hash::make($validated['password']),
        //     'age'            => $validated['age'],
        //     'gender'         => $validated['gender'],
        //     'height'         => $validated['height'],
        //     'weight'         => $validated['weight'],
        //     'bmi'            => $bmi,
        //     'activity_level' => $validated['activity_level'],
        // ]);
        
        // return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
