@extends('layouts.adminLayout')

@section('title', 'User Task List')

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

    $user = Auth::user()->area->id;
    $all = TaskArea::where('area_id',$user)->count();
    
    $todays = TaskArea::whereDate('period', '=', Carbon::today())
                ->where('area_id',$user)->count();

    $oneDay = TaskArea::whereDate('period', '=', Carbon::tomorrow())
                ->where('area_id',$user)->count();

    $twoDays = TaskArea::whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])
                ->where('area_id',$user)->count();

    $expired = TaskArea::whereDate('period', '<', Carbon::today())
                ->where('area_id',$user)->count();

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

             <h5 class="mb-2 mt-4">Small Box</h5>
             <div class="row no-gutters">
                <!-- All Tasks -->
                <div class="col-lg-2 col-6 mx-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h5>{{$all}} Tasks</h5>
                            <p>All of the Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ url('/user-tasks/filter/all') }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks in 2 Days -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h5>{{$twoDays}} Tasks</h5>
                            <p>Tasks within 2 days</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ url('/user-tasks/filter/twodays') }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks Tomorrow -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h5>{{$oneDay}} Tasks</h5>
                            <p>Tasks For Tomorrow</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ url('/user-tasks/filter/tomorrow') }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks Today -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5>{{$todays}} Tasks</h5>
                            <p>Tasks For Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="{{ url('/user-tasks/filter/today') }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Expired Tasks -->
                <div class="col-lg-2 col-6 mx-3 mx-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5>{{ $expired }} Tasks</h5>
                            <p>Tasks that expired now</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="{{ url('/user-tasks/filter/expired') }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row my-3">
                <form method="GET" action="{{ route('user.tasks.filter') }}" class="form-inline">
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
                @if($taskAreas->isEmpty())
                    <p>No responses found for the selected date range.</p>
                @else
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Area</th>
                                <th>Title</th>
                                <th>Performer</th>
                                <th>Category</th>
                                <th>File</th>
                                <th>Period</th>
                                <th>Comment</th>
                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taskAreas as $taskArea)
                            <tr>
                                    <td>{{$taskArea->tasks->id}}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $taskArea->areas->name }}</span>
                                    </td>
                                    <td>{{$taskArea->tasks->title}}</td>
                                    <td>{{$taskArea->tasks->performer}}</td>
                                    <td>{{$taskArea->tasks->categories->name}}</td>
                                    <td>
                                        @if ($taskArea->tasks->file)
                                            <a href="{{ asset('storage/' . $taskArea->tasks->file) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-file-download mr-1"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                    <td>{{$taskArea->period}}</td>
                                    <td>
                                        @if ($taskArea->tasks && $taskArea->tasks->responses && $taskArea->tasks->responses->isNotEmpty())
                                            {{ $taskArea->tasks->responses->first()->comment }}
                                        @endif
                                        <td class="task-actions">
                                            @if ($taskArea->status == 'sent')
                                                <form method="POST" action="{{ route('tasks.open', $taskArea->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-info btn-action">
                                                        <i class="fas fa-envelope-open mr-1"></i>
                                                        <span>Open</span>
                                                    </button>
                                                </form>
                                            @elseif ($taskArea->status == 'done')
                                                <button type="button" class="btn btn-outline-success btn-action disabled">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <span>Done</span>
                                                </button>
                                            @elseif ($taskArea->status == 'approved')
                                                <button type="button" class="btn btn-success btn-action disabled">
                                                    <i class="fas fa-trophy mr-1"></i>
                                                    <span>Approved</span>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-warning btn-action" data-toggle="modal" data-target="#doTaskModal-{{ $taskArea->id }}">
                                                    <i class="fas fa-play-circle mr-1"></i>
                                                    <span>Do Task</span>
                                                </button>
                                                
                                                <!-- Enhanced Modal -->
                                                <div class="modal fade task-modal" id="doTaskModal-{{ $taskArea->id }}" tabindex="-1" aria-labelledby="doTaskLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form method="POST" action="{{ route('tasks.do', $taskArea->id) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-clipboard-check mr-2"></i>
                                                                        Complete Task
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group mb-4">
                                                                        <label for="note" class="font-weight-bold">
                                                                            <i class="fas fa-comment-alt mr-2"></i>
                                                                            Task Notes
                                                                        </label>
                                                                        <textarea 
                                                                            name="note" 
                                                                            id="note" 
                                                                            class="form-control" 
                                                                            rows="4" 
                                                                            required 
                                                                            placeholder="Enter your notes about the task completion..."
                                                                        ></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="file" class="font-weight-bold">
                                                                            <i class="fas fa-paperclip mr-2"></i>
                                                                            Attachment
                                                                        </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" name="file" class="custom-file-input" id="file">
                                                                            <label class="custom-file-label" for="file">Choose file</label>
                                                                        </div>
                                                                        <small class="form-text text-muted mt-2">
                                                                            <i class="fas fa-info-circle mr-1"></i>
                                                                            Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (max 10MB)
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                                        <i class="fas fa-times mr-1"></i>
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="fas fa-paper-plane mr-1"></i>
                                                                        Submit Task
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    {{ $taskAreas->links() }}
                @endif
        </div>
    </section>
</div>

                                        
<script>
    // Update file input label with selected filename
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
