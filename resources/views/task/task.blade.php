@extends('layouts.adminLayout')

@section('title', 'Task List')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
            <!-- Stats Boxes with Filter Links -->
            <h5 class="mb-2 mt-4">Small Box</h5>
            <div class="row">
                <!-- All Tasks -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h5>{{ $taskCounts['all'] }} Tasks</h5>
                            <p>All of the Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('tasks.index', ['filter' => 'all']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Tasks in 2 Days -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h5>{{ $taskCounts['two_days'] }} Tasks</h5>
                            <p>Tasks need to be done in 2 days</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('tasks.index', ['filter' => 'two_days']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Tasks Tomorrow -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h5>{{ $taskCounts['tomorrow'] }} Tasks</h5>
                            <p>Tasks need to be done Tomorrow</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('tasks.index', ['filter' => 'tomorrow']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Tasks Today -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5>{{ $taskCounts['today'] }} Tasks</h5>
                            <p>Tasks need to be done Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="{{ route('tasks.index', ['filter' => 'today']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Expired Tasks -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5>{{ $taskCounts['expired'] }} Tasks</h5>
                            <p>Tasks that expired now</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="{{ route('tasks.index', ['filter' => 'expired']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>\
            </div>
        
        <a href="{{ route('tasks.create') }}" class='btn btn-primary m-2'>Create Task</a>

            <div class="row my-3">
                <form method="GET" action="{{ route('tasks.index') }}" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="start_date" class="mr-2">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="end_date" class="mr-2">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
            {{-- @php
                dd($tasks[0]->taskAreas)
            @endphp --}}
            
            <table class="table table-striped table-bordered">
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
                    @foreach ($tasks as $task)
                        @foreach ($task->areas as $area)
                            @php
                                // Find the taskArea that matches both the task and the current area
                                $taskAreaStatus = $task->taskAreas
                                    ->where('task_id', $task->id)
                                    ->where('area_id', $area->id)
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->title }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $area->name }}</span>
                                </td>
                                <td>{{ $task->performer }}</td>
                                <td>{{ $task->categories->name }}</td>
                                <td>
                                    @if ($taskAreaStatus->status== 'sent')
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color: lightblue" width="25" height="25" fill="currentColor" class="bi bi-send-check-fill ml-2" viewBox="0 0 16 16">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498C8 14 8 13 8 12.5a4.5 4.5 0 0 1 5.026-4.47zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
                                          </svg>

                                    @elseif ($taskAreaStatus->status== 'opened')

                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-envelope-open-fill ml-2" viewBox="0 0 16 16">
                                        <path d="M8.941.435a2 2 0 0 0-1.882 0l-6 3.2A2 2 0 0 0 0 5.4v.314l6.709 3.932L8 8.928l1.291.718L16 5.714V5.4a2 2 0 0 0-1.059-1.765zM16 6.873l-5.693 3.337L16 13.372v-6.5Zm-.059 7.611L8 10.072.059 14.484A2 2 0 0 0 2 16h12a2 2 0 0 0 1.941-1.516M0 13.373l5.693-3.163L0 6.873z"/>
                                      </svg>

                                    @elseif ($taskAreaStatus->status== 'done')

                                    <svg xmlns="http://www.w3.org/2000/svg" style="color: aquamarine" width="25" height="25" fill="currentColor" class="bi bi-check2-circle ml-2" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                                    </svg>
                                    
                                    @elseif ($taskAreaStatus->status== 'approved')
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" style="color: darkgreen" height="25" fill="currentColor" class="bi bi-check-circle-fill ml-2" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                      </svg>

                                    @elseif ($taskAreaStatus->status== 'rejected')

                                    <svg xmlns="http://www.w3.org/2000/svg" style="color:red" width="25" height="25" fill="currentColor" class="bi bi-x-circle ml-2" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                      </svg>
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($task->file)
                                        <a href="{{ asset('storage/' . $task->file) }}" target="_blank">View File</a>
                                    @else
                                        No File
                                    @endif
                                </td>
                                <td>{{ $task->period }}</td>
                                <td>
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>                         
            </table>
            {{ $tasks->links() }}
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
