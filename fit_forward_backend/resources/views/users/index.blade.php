@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Users</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center" id="UserTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Height (cm)</th>
                    <th>Weight (kg)</th>
                    <th>BMI</th>
                    <th>Activity Level</th>
                    <th>Workout & Nutrition Plans</th>
                    <th>Workout Logs</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index=>$user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->age }}</td>
                    <td>{{ $user->gender == 0 ? 'Female' : 'Male' }}</td>
                    <td>{{ $user->height }}</td>
                    <td>{{ $user->weight }}</td>
                    <td>{{ $user->bmi }}</td>
                    <td>
                        @php
                            $levels = ['Sedentary', 'Lightly Active', 'Moderately Active', 'Very Active', 'Extra Active'];
                        @endphp
                        {{ $levels[$user->activity_level - 1] ?? 'N/A' }}
                    </td>
                    <td>
                        <a href="{{ route('users.plans', $user->id) }}" 
                            class="btn btn-primary">Plans</a>
                    </td>
                    <td>
                        <a href="{{ route('users.logs', $user->id) }}" 
                            class="btn btn-primary">Logs</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#UserTable').DataTable({
            responsive: true,
            autoWidth: true,
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
