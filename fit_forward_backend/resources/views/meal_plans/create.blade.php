@extends('layouts.app')

@section('content')
<div class="container col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New Meal Plan</h5>
            <a href="{{ route('meal_plans.index') }}" class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('meal_plans.store') }}" method="POST">
                @csrf

                <!-- BMI Category -->
                <div class="mb-3">
                    <label for="bmi_category_id" class="form-label">BMI Category</label>
                    <select name="bmi_category_id" id="bmi_category_id" class="form-select" required>
                        <option value="">-- Select BMI Category --</option>
                        @foreach ($bmi_category as $category)
                            <option value="{{ $category->id }}" {{ old('bmi_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bmi_category_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                <!-- Protein -->
                    <div class="col-md-4 mb-3">
                        <label for="protein" class="form-label">Protein (%)</label>
                        <input type="number" min="0" name="protein" id="protein" class="form-control" value="{{ old('protein') }}" required>
                        @error('protein')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Carbs -->
                    <div class="col-md-4 mb-3">
                        <label for="carbs" class="form-label">Carbs (%)</label>
                        <input type="number" min="0" name="carbs" id="carbs" class="form-control" value="{{ old('carbs') }}" required>
                        @error('carbs')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Fats -->
                    <div class="col-md-4 mb-3">
                        <label for="fats" class="form-label">Fats (%)</label>
                        <input type="number" min="0" name="fats" id="fats" class="form-control" value="{{ old('fats') }}" required>
                        @error('fats')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Meal Time</label>
                    <textarea name="description" id="description" class="form-control" rows="3">
                        {{ old('description') }}
                    </textarea>
                    @error('description')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Meal Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
