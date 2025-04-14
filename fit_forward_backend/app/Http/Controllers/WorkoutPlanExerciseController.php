<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\WorkoutPlanExercise;
use Illuminate\Http\Request;

class WorkoutPlanExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $workout_plan_exercises = WorkoutPlanExercise::where('workout_plan_id', $id)->get();
        return view('workout_plan_exercises.index', compact('id', 'workout_plan_exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $exercises = Exercise::all();
        $workout_plan = WorkoutPlan::where('id', $id)->first();
        return view('workout_plan_exercises.create', compact('id', 'exercises', 'workout_plan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'workout_plan_id' => 'required|integer',
            'exercise_id'  => 'required|integer',
            'sets'  => 'required|integer',
        ]);

        WorkoutPlanExercise::create($request->all());

        return redirect()->route('workout_plan_exercises.index', $id)
            ->with('success', 'Workout Plan Exercise created successfully.');
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
    public function edit(string $wid, $id)
    {
        $workout_plan_exercises = WorkoutPlanExercise::findOrFail($id);

        $exercises = Exercise::all();
        $workout_plan = WorkoutPlan::where('id', $wid)->first();

        return view('workout_plan_exercises.edit', 
            compact('wid', 'id', 'workout_plan_exercises', 'exercises', 'workout_plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $wid, string $id)
    {
        $request->validate([
            'workout_plan_id' => 'required|integer',
            'exercise_id'  => 'required|integer',
            'sets'  => 'required|integer',
        ]);

        $workout_plan_exercises = WorkoutPlanExercise::findOrFail($id);
        $workout_plan_exercises->update($request->all());

        return redirect()->route('workout_plan_exercises.index', $wid)
            ->with('success', 'Workout Plan Exercise created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($wid, string $id)
    {
        $workout_plan_exercise = WorkoutPlanExercise::findOrFail($id);
        $workout_plan_exercise->delete();
        return redirect()->route('workout_plan_exercises.index', $wid)
            ->with('success', 'Workout Plan Exercise deleted.');
    }
}
