@extends('layouts.adminLayout')

@section('title', 'Repostation List')

@section('content')

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* General Button Styles */
    .btn-action {
        padding: 0.5rem 1rem;
        font-weight: 500;
        min-width: 120px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-action:not(.disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-action.disabled {
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Modal Styles */
    .task-modal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Form Enhancements */
    .form-control {
        border-radius: 6px;
        padding: 0.75rem;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Table Enhancements */
    .table thead th {
        background-color: #343a40;
        color: white;
    }

    .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show fadeIn" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show fadeIn" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                <h3 class="ml-3">Reports</h3>
                
            <div class="row mb-4">
                <form method="POST" action="{{ route('report.index') }}" class="form-inline mx-auto">
                    @csrf
                    <div class="form-group mr-3">
                        <label for="start_date" class="mr-2">Start Date:</label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group mr-3">
                        <label for="end_date" class="mr-2">End Date:</label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-action">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Categories</th>
                            <th>Sent</th>
                            <th>Opened</th>
                            <th>Done</th>
                            <th>Approved</th>
                            <th>Rejected</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            @php
                                $sentCount = $category->taskAreas->where('status', 'sent')->count();
                                $openedCount = $category->taskAreas->where('status', 'opened')->count();
                                $doneCount = $category->taskAreas->where('status', 'done')->count();
                                $approvedCount = $category->taskAreas->where('status', 'approved')->count();
                                $rejectedCount = $category->taskAreas->where('status', 'rejected')->count();
                            @endphp                    
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $sentCount ?: '-' }}</td>
                                    <td>{{ $openedCount ?: '-' }}</td>
                                    <td>{{ $doneCount ?: '-' }}</td>
                                    <td>{{ $approvedCount ?: '-' }}</td>
                                    <td>{{ $rejectedCount ?: '-' }}</td>
                                </tr>
                        @endforeach
                    </tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#start_date", { dateFormat: "Y-m-d" });
        flatpickr("#end_date", { dateFormat: "Y-m-d" });
    });
</script>

@endsection
