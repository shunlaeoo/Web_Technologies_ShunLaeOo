<?php

namespace App\Http\Controllers;

use App\Models\BmiCategory;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Models\MealPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalUsers = User::count();
        $averageBmi = round(User::avg('bmi'), 2);
        $workoutPlansCount = WorkoutPlan::count();
        $mealPlansCount = MealPlan::count();

        $recentUsers = User::latest()->take(5)->get();

        return view('dashboard', 
            compact('totalUsers', 'averageBmi',
                    'workoutPlansCount', 'mealPlansCount', 'recentUsers'));
    }
}
