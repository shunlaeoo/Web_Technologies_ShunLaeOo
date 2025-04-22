@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New BMI Category</h5>
            <a href="{{ route('bmi_category.index') }}" class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('bmi_category.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Normal, Overweight" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min" class="form-label">Min BMI</label>
                    <input type="number" min="0" name="min" id="min" class="form-control" placeholder="e.g. 18.5" value="{{ old('min') }}" required>
                    @error('min')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="max" class="form-label">Max BMI</label>
                    <input type="number" min="0" name="max" id="max" class="form-control" placeholder="e.g. 24.9" value="{{ old('max') }}" required>
                    @error('max')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create BMI Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
