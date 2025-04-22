@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Recommendations for {{ $user->name }}</h4>
        <a href="{{ route('users.index') }}" class="btn btn-dark btn-sm">
            Back to List
        </a>
    </div>

    <div class="row g-4">
      <!-- Workout Plan -->
      <div class="col-md-6">
        <div class="card-custom">
            <h5 class="fw-bold mb-3">
                <svg class="icon-color pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                    width="25" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 18V6l8 6-8 6Z"/>
                </svg>                  
                Workout Plan
            </h5>

          <!-- Exercise Item -->
        @foreach ($exercises as $exercise)
          <div class="card-custom p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-danger fw-bold mb-0">{{ $exercise->exercise->name }}</h6>
                <span class="badge-md times px-3 py-1">
                    {{ $exercise->sets }} × {{ $exercise->reps ? $exercise->reps : ($exercise->duration ? $exercise->duration . 's' : '') }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="fw-bold">Instructions</h6>
                {!! $exercise->exercise->instructions !!}
              </div>
              <img src="{{ asset('storage/' . $exercise->exercise->image) }}" 
                class="img-fluid w-50" 
                alt="{{ $exercise->exercise->name }}" />
            </div>
          </div>
        @endforeach
        </div>
      </div>

      <!-- Nutrition Recommendations -->
      <div class="col-md-6">
        <div class="card-custom">
            <h5 class="fw-bold mb-4">
                <svg class="icon-color me-1" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils text-primary h-5 w-5" data-lov-id="src/components/Profile.tsx:461:16" data-lov-name="Utensils" data-component-path="src/components/Profile.tsx" data-component-line="461" data-component-file="Profile.tsx" data-component-name="Utensils" data-component-content="%7B%22className%22%3A%22text-primary%20h-5%20w-5%22%7D">
                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                </svg>
                Nutrition Recommendations
            </h5>

          <div class="d-flex justify-content-between text-center mb-3">
            <div class="macro-box flex-fill me-2">
              <h6>Protein</h6>
              <strong>{{ $meal_plan->protein }}%</strong>
            </div>
            <div class="macro-box flex-fill me-2">
              <h6>Carbs</h6>
              <strong>{{ $meal_plan->carbs }}%</strong>
            </div>
            <div class="macro-box flex-fill">
              <h6>Fats</h6>
              <strong>{{ $meal_plan->fats }}%</strong>
            </div>
          </div>

          <div class="highlight-box py-4 my-4">
            <h6 class="text-dark fw-bold">Daily Calories Target</h6>
            <h2 class="mb-0">{{ $daily_calories }} kcal</h2>
          </div>

          <div>
            <h6 class="fw-bold mb-3">Sample Meal Plan</h6>
                {!! $meal_plan->description !!}
          </div>
        </div>
      </div>
    </div>
</div>
@endsection