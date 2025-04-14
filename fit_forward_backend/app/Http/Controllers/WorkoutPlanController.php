<?php

namespace App\Http\Controllers;

use App\Models\BmiCategory;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workout_plan = WorkoutPlan::all();
        return view('workout_plans.index', compact('workout_plan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bmi_category = BmiCategory::all();
        return view('workout_plans.create', compact('bmi_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bmi_category_id' => 'required|integer',
            'title'  => 'required|string',
            'description'  => 'required',
        ]);

        WorkoutPlan::create($request->all());

        return redirect()->route('workout_plans.index')->with('success', 'Workout Plan created successfully.');
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
        $workout_plan = WorkoutPlan::findOrFail($id);
        $bmi_category = BmiCategory::all();
        return view('workout_plans.edit', compact('workout_plan', 'bmi_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'bmi_category_id' => 'required|integer',
            'title'  => 'required|string',
            'description'  => 'required',
        ]);

        $workout_plan = WorkoutPlan::findOrFail($id);
        $workout_plan->update($request->all());

        return redirect()->route('workout_plans.index')->with('success', 'Workout Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workout_plan = WorkoutPlan::findOrFail($id);
        $workout_plan->delete();
        return redirect()->route('workout_plans.index')->with('success', 'Workout Plan deleted.');
    }
}
