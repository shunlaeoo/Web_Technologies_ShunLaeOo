<?php

namespace App\Http\Controllers;

use App\Models\BmiCategory;
use Illuminate\Http\Request;

class BmiCategoryController extends Controller
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
        $categories = BmiCategory::all();
        return view('bmi_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bmi_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'min'  => 'required|numeric',
            'max'  => 'required|numeric',
        ]);

        BmiCategory::create($request->all());

        return redirect()->route('bmi_category.index')->with('success', 'BMI Category created successfully.');
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
        $bmi_category = BmiCategory::findOrFail($id);
        return view('bmi_categories.edit', compact('bmi_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'min'  => 'required|numeric',
            'max'  => 'required|numeric',
        ]);

        $bmi_category = BmiCategory::findOrFail($id);
        $bmi_category->update($request->all());

        return redirect()->route('bmi_category.index')->with('success', 'BMI Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bmi_category = BmiCategory::findOrFail($id);
        $bmi_category->delete();
        return redirect()->route('bmi_category.index')->with('success', 'BMI Category deleted.');
    }
}
