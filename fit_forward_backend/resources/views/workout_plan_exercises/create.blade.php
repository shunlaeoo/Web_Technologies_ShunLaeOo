@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New Workout Plan Exercise</h5>
            <a href="{{ route('workout_plan_exercises.index', $id) }}" 
                class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('workout_plan_exercises.store', $id) }}" method="POST">
                @csrf

                <!-- Workout Plan -->
                <div class="mb-3">
                    <label for="workout_plan_id" class="form-label">Workout Plan</label>
                    <select name="workout_plan_id" id="workout_plan_id" class="form-select">
                        <option value="{{ $id }}" selected>
                            {{ $workout_plan->title }}
                        </option>
                    </select>
                    @error('workout_plan_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Exercises  -->
                <div class="mb-3">
                    <label for="exercise_id" class="form-label">Exercises</label>
                    <select name="exercise_id" id="exercise_id" class="form-select" required>
                        <option value="">-- Select Exercise --</option>
                        @foreach ($exercises as $category)
                            <option value="{{ $category->id }}" {{ old('exercise_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('exercise_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <!-- Sets -->
                    <div class="col-md-4 mb-3">
                        <label for="sets" class="form-label">Sets</label>
                        <input type="number" min="1" name="sets" id="sets" class="form-control" 
                            value="{{ old('sets') }}" required>
                        @error('sets')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Reps -->
                    <div class="col-md-4 mb-3">
                        <label for="reps" class="form-label">Reps</label>
                        <input type="number" min="1" name="reps" id="reps" class="form-control" 
                            value="{{ old('reps') }}">
                        @error('reps')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div class="col-md-4 mb-3">
                        <label for="duration" class="form-label">Duration (s)</label>
                        <input type="number" min="1" name="duration" id="duration" class="form-control" 
                            value="{{ old('duration') }}">
                        @error('duration')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Workout Plan Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
