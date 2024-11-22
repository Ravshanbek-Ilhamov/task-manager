@extends('layouts.adminLayout')

@section('title', 'Repostation List')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Button Styles */
    .btn-action {
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 120px;
        border-radius: 6px;
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
    
    .task-modal .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 1.25rem;
    }
    
    .task-modal .modal-body {
        padding: 1.5rem;
    }
    
    .task-modal .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        padding: 1.25rem;
    }
    
    .task-modal .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .task-modal .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .task-modal .custom-file-label {
        padding: 0.75rem;
        border-radius: 6px;
        height: auto;
    }
    
    .task-modal .custom-file-input:focus ~ .custom-file-label {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Button hover states */
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #212529;
    }
    
    /* Additional animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .task-modal.show {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* File input enhancement */
    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        margin-bottom: 0;
    }
    
    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "Browse";
    }
</style>

@php
    use Carbon\Carbon;
    use App\Models\TaskArea;

    // $user = Auth::user()->area->id;
    // $all = TaskArea::all()->count();

    // foreach ($areas as $area) {
    //     foreach ($categories as $category) {
    //         $tasksData[$area->id][$category->id] = TaskArea::where('area_id', $area->id)
    //             ->where('category_id', $category->id)
    //             ->whereDate('period', '=', Carbon::tomorrow())
    //             ->count();
    //     }
    // }
    
    // dd($tasksData);

    // $todays = TaskArea::whereDate('period', '=', Carbon::today())
    //             ->where('area_id',$user)->count();

    // $oneDay = TaskArea::whereDate('period', '=', Carbon::tomorrow())
    //             ->where('area_id',$user)->count();

    // $twoDays = TaskArea::whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])
    //             ->where('area_id',$user)->count();

    // $expired = TaskArea::whereDate('period', '<', Carbon::today())
    //             ->where('area_id',$user)->count();

    // dd($all,$todays,$oneDay,$twoDays,$expired);

@endphp

<div class="content-wrapper">
    <section class="content">

        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row mx-2 mb-3">
                <form method="POST" action="{{ route('alltasks.filter') }}" class="form-inline">
                    @csrf
                    <div class="form-group mr-2 mt-4">
                        <label for="start_date" class="mr-2">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group mr-2 mt-4">
                        <label for="end_date" class="mr-2">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </form>
            </div>
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Caregories</th>
                                <th>Sent</th>
                                <th>Opened</th>
                                <th>Done</th>
                                <th>Approved</th>
                                <th>Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>

                                    @php
                                        
                                    @endphp

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

        </div>
    </section>
</div>

                                        
<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = this.files[0].name;
            const label = this.nextElementSibling;
            label.innerText = fileName;
        });
    });
 </script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#start_date", { dateFormat: "Y-m-d" });
    flatpickr("#end_date", { dateFormat: "Y-m-d" });
});
</script>

@endsection
