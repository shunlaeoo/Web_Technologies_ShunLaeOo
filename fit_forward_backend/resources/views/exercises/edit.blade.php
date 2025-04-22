@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-10 col-lg-8 mt-5">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Exercise</h5>
            <a href="{{ route('exercises.index') }}" class="btn btn-dark btn-sm">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('exercises.update', $exercises->id) }}" 
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Exercise Name</label>
                    <input type="text" name="name" id="name" class="form-control" 
                        placeholder="e.g. Jump Squats" value="{{ $exercises->name }}" required>
                    @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" 
                        value="{{ $exercises->image }}">
                    @error('image')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea id="description" name="instructions" class="mt-1">
                        {{ $exercises->instructions }}
                    </textarea>
                    @error('instructions')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="video_url" class="form-label">Video Url</label>
                    <input type="text" name="video_url" id="video_url" class="form-control"
                        value="{{ $exercises->video_url }}" required>
                    @error('video_url')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Edit Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
