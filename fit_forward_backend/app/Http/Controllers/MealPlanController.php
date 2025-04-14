<?php

namespace App\Http\Controllers;

use App\Models\BmiCategory;
use App\Models\MealPlan;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meal_plan = MealPlan::all();
        return view('meal_plans.index', compact('meal_plan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bmi_category = BmiCategory::all();
        return view('meal_plans.create', compact('bmi_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bmi_category_id' => 'required|integer',
            'protein'  => 'required|numeric',
            'carbs'  => 'required|numeric',
            'fats'  => 'required|numeric',
            'description'  => 'required',
        ]);

        MealPlan::create($request->all());

        return redirect()->route('meal_plans.index')->with('success', 'Meal Plan created successfully.');
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
        $meal_plan = MealPlan::findOrFail($id);
        $bmi_category = BmiCategory::all();
        return view('meal_plans.edit', compact('meal_plan', 'bmi_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'bmi_category_id' => 'required|integer',
            'protein'  => 'required|numeric',
            'carbs'  => 'required|numeric',
            'fats'  => 'required|numeric',
            'description'  => 'required',
        ]);

        $meal_plan = MealPlan::findOrFail($id);
        $meal_plan->update($request->all());

        return redirect()->route('meal_plans.index')->with('success', 'Meal Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meal_plan = MealPlan::findOrFail($id);
        $meal_plan->delete();
        return redirect()->route('meal_plans.index')->with('success', 'Meal Plan deleted.');
    }
}
