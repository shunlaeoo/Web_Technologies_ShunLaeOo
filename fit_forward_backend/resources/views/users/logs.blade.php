@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Users</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center" id="UserTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User Name</th>
                    <th>Workout Plan</th>
                    <th>Exercise</th>
                    <th>Completed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $index=>$log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ $log->workout_plan->title }}</td>
                    <td>{{ $log->exercise->name }}</td>
                    <td>{{ $log->created_at }}</td>
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