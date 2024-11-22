@extends('layouts.adminLayout')

@section('title', 'Task List')

@section('content')
<style>
    .task-dashboard {
        padding: 20px;
    }
    .stats-container {
        margin-bottom: 2rem;
    }
    .small-box {
        border-radius: 8px;
        transition: transform 0.2s;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .task-table {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    .status-icon {
        width: 25px;
        height: 25px;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .create-task-btn {
        margin-bottom: 1.5rem;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@php
    use Carbon\Carbon;
    use App\Models\TaskArea;


    $all = TaskArea::all()->count();
    
    $todays = TaskArea::whereDate('period', '=', Carbon::today())->count();

    $oneDay = TaskArea::whereDate('period', '=', Carbon::tomorrow())->count();

    $twoDays = TaskArea::whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])->count();

    $expired = TaskArea::whereDate('period', '<', Carbon::today())->count();

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
                    <!-- Small Box (Stat card) -->
                    <h5 class="mt-4">Small Box</h5>
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
                               <a href="{{ url('/tasks/filter/all') }}" class="small-box-footer">
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
                               <a href="{{ url('/tasks/filter/twodays') }}" class="small-box-footer">
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
                               <a href="{{ url('/tasks/filter/tomorrow') }}" class="small-box-footer">
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
                               <a href="{{ url('/tasks/filter/today') }}" class="small-box-footer">
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
                               <a href="{{ url('/tasks/filter/expired') }}" class="small-box-footer">
                                   See All <i class="fas fa-arrow-circle-right"></i>
                               </a>
                           </div>
                       </div>
                   </div>

                   <div class="row mx-2">
                       <form method="POST" action="{{ route('tasks.filter') }}" class="form-inline">
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
        
        <a href="{{ route('tasks.create') }}" class='btn btn-primary m-2'>Create Task</a>

            @if ($taskAreas->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>No tasks found
            </div>
        @else
            <div class="table-responsive task-table">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Area</th>
                        <th>Performer</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Period</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskAreas as $taskArea)
                            <tr>
                                <td>{{ $taskArea->id }}</td>
                                <td>{{ $taskArea->tasks->title }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $taskArea->areas->name }}</span>
                                </td>
                                <td>{{ $taskArea->tasks->performer }}</td>
                                <td>{{ $taskArea->tasks->categories->name }}</td>
                                <td>
                                    @switch($taskArea->status)
                                        @case('sent')
                                            <span class="badge badge-info">
                                                <i class="fas fa-paper-plane mr-1"></i> Sent
                                            </span>
                                            @break
                                        @case('opened')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-envelope-open mr-1"></i> Opened
                                            </span>
                                            @break
                                        @case('done')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i> Done
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="badge badge-primary">
                                                <i class="fas fa-thumbs-up mr-1"></i> Approved
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i> Rejected
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if ($taskArea->tasks->file)
                                        <a href="{{ asset('storage/' . $taskArea->tasks->file) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-file-download mr-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">No file</span>
                                    @endif
                                </td>

                                <td>{{ $taskArea->period }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tasks.edit', $taskArea->id) }}" class="btn btn-sm btn-warning mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $taskArea->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#start_date", { dateFormat: "Y-m-d" });
    flatpickr("#end_date", { dateFormat: "Y-m-d" });
});
</script>

@endsection
