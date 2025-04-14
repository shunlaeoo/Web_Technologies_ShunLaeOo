<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
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
        $exercises = Exercise::all();
        return view('exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exercises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'video_url' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Exercise::create([
                'name' => $request->name,
                'image' => $imagePath ?? null,
                'instructions' => $request->instructions,
                'video_url' => $request->video_url,
            ]);

        return redirect()->route('exercises.index')->with('success', 'Exercise created successfully.');
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
        $exercises = Exercise::findOrFail($id);
        return view('exercises.edit', compact('exercises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'video_url' => 'required',
        ]);

        $exercises = Exercise::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($exercises->image && \Storage::disk('public')->exists($exercises->image)) {
                \Storage::disk('public')->delete($exercises->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $exercises->image = $imagePath;
        }

        $exercises->name = $request->name;
        $exercises->instructions = $request->instructions;
        $exercises->video_url = $request->video_url;
        $exercises->save();

        return redirect()->route('exercises.index')->with('success', 'Exercise updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exercises = Exercise::findOrFail($id);
        $exercises->delete();
        return redirect()->route('exercises.index')->with('success', 'Exercise deleted.');
    }
}
