@extends('layouts.app')

@section('content')
    <div class="dashboard container-fluid my-4">
        <h2 class="fw-bold mb-4 py-1">Admin Dashboard</h2>

        {{-- 📊 Stat Cards --}}
        <div class="row mb-4">
            <div class="col-md-3 d-flex flex-column pe-md-2">
                <div class="card gradient-1 text-muted flex-grow-1 box-shadow py-2 mb-3">
                    <div class="card-body text-dark text-center">
                        <h4 class="card-title fw-bold mb-3">👤 Total Users</h4>
                        <p class="card-text fs-4 fw-bold">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex flex-column px-md-2">
                <div class="card gradient-2 text-muted flex-grow-1 py-2 mb-3">
                    <div class="card-body text-dark text-center">
                        <h4 class="card-title fw-bold mb-3">📈 Avg BMI</h4>
                        <p class="card-text fs-4 fw-bold">{{ $averageBmi }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex flex-column px-md-2">
                <div class="card gradient-3 text-muted flex-grow-1 py-2 mb-3">
                    <div class="card-body text-dark text-center">
                        <h4 class="card-title fw-bold mb-3">🏋️ Workout Plans</h4>
                        <p class="card-text fs-4 fw-bold">{{ $workoutPlansCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex flex-column ps-md-2">
                <div class="card gradient-4 text-muted flex-grow-1 py-2 mb-3">
                    <div class="card-body text-dark text-center">
                        <h4 class="card-title fw-bold mb-3">🍱 Meal Plans</h4>
                        <p class="card-text fs-4 fw-bold">{{ $mealPlansCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 👥 Recent Users Table --}}
        <div class="card mb-5">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Recent Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>BMI</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $index=>$user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->bmi }}</td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection