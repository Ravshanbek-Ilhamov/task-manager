@extends('layouts.adminLayout')

@section('title', 'User Task List')

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

            <h5 class="mb-2 mt-4">Small Box</h5>
            <div class="row">
              <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3>150</h3>
    
                    <p>New Orders</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
    
                    <p>Bounce Rate</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3>44</h3>
    
                    <p>User Registrations</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-user-plus"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3>65</h3>
    
                    <p>Unique Visitors</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <!-- ./col -->
            </div>

            <div class="row my-3">
                {{-- <form method="GET" action="{{ route('responses.page') }}" class="form-inline"> --}}
                <form method="GET" action="{{ url('/user-tasks') }}" class="form-inline">

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
                @if($tasks->isEmpty())
                    <p>No responses found for the selected date range.</p>
                @else
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Area</th>
                                <th>Performer</th>
                                <th>Category</th>
                                <th>File</th>
                                <th>Period</th>
                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
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
                                            @if ($task->file)
                                                <a href="{{ asset('storage/' . $task->file) }}" target="_blank">View File</a>
                                            @else
                                                No File
                                            @endif
                                        </td>
                                        <td>{{ $task->period }}</td>
                                        <td>
                                        @if ($taskAreaStatus->status == 'sent')

                                            <form method="POST" action="{{ route('tasks.open', $taskAreaStatus->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info">Open</button>
                                            </form>

                                        @elseif ($taskAreaStatus->status == 'done')
                                            <button type="submit" class="btn btn-outline-success disabled">Done</button>
                                        @elseif ($taskAreaStatus->status == 'approved')
                                            <button type="submit" class="btn btn-success disabled">Approved</button>

                                        @else    
                                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#doTaskModal-{{ $taskAreaStatus->id }}">
                                                Do
                                            </button>
                                            
                                            <div class="modal fade" id="doTaskModal-{{ $taskAreaStatus->id }}" tabindex="-1" aria-labelledby="doTaskLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="POST" action="{{ route('tasks.do', $taskAreaStatus->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="doTaskLabel">Complete Task</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="note">Note</label>
                                                                    <textarea name="note" id="note" class="form-control" required></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputFile">File input</label>
                                                                    <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                    </div>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Upload</span>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Submit Task</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>                         
                    </table>
                    {{ $tasks->links() }}
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
