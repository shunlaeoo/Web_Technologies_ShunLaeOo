@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Admin</h5>
            <a href="{{ route('admin.index') }}" class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"></label>
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="{{ old('password') }}" required>
                    @error('password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
