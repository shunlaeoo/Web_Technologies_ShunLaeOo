<?php

use App\Http\Controllers\BmiCategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\WorkoutPlanExerciseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/plans/{id}', [UserController::class, 'plans'])->name('users.plans');

    Route::resource('bmi_category', BmiCategoryController::class);
    Route::resource('exercises', ExerciseController::class);
    Route::resource('workout_plans', WorkoutPlanController::class);
    Route::resource('meal_plans', MealPlanController::class);

    // Workout Plan Exercises
    Route::get('workout_plan_exercises/{id}', [WorkoutPlanExerciseController::class, 'index'])->name('workout_plan_exercises.index');
    Route::get('workout_plan_exercises/{id}/create', [WorkoutPlanExerciseController::class, 'create'])->name('workout_plan_exercises.create');
    Route::post('workout_plan_exercises/{id}/store', [WorkoutPlanExerciseController::class, 'store'])->name('workout_plan_exercises.store');
    Route::get('workout_plan_exercises/{wid}/{id}/edit', [WorkoutPlanExerciseController::class, 'edit'])->name('workout_plan_exercises.edit');
    Route::post('workout_plan_exercises/{wid}/{id}/update', [WorkoutPlanExerciseController::class, 'update'])->name('workout_plan_exercises.update');
    Route::delete('workout_plan_exercises/{wid}/{id}/delete', [WorkoutPlanExerciseController::class, 'destroy'])->name('workout_plan_exercises.delete');
});