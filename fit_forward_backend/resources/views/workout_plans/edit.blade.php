@extends('layouts.app')

@section('content')
<div class="container col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Workout Plan</h5>
            <a href="{{ route('workout_plans.index') }}" class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('workout_plans.update', $workout_plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- BMI Category -->
                <div class="mb-3">
                    <label for="bmi_category_id" class="form-label">BMI Category</label>
                    <select name="bmi_category_id" id="bmi_category_id" class="form-select" required>
                        <option value="">-- Select BMI Category --</option>
                        @foreach ($bmi_category as $category)
                            <option value="{{ $category->id }}" {{ $workout_plan->bmi_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bmi_category_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $workout_plan->title }}" required>
                    @error('title')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">
                        {{ $workout_plan->description }}
                    </textarea>
                    @error('description')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Edit Workout Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection