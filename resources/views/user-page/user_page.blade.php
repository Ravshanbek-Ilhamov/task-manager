@extends('layouts.adminLayout')

@section('title', 'User Task List')

@section('content')
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
// dd(124)

@endphp

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
                                            <a href="{{ asset('storage/' . $taskArea->tasks->file) }}" target="_blank">View File</a>
                                        @else
                                            No File
                                        @endif
                                    </td>
                                    <td>{{$taskArea->tasks->period}}</td>
                                    <td>
                                        @if ($taskArea->tasks && $taskArea->tasks->responses && $taskArea->tasks->responses->isNotEmpty())
                                            {{ $taskArea->tasks->responses->first()->comment }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($taskArea->status == 'sent')
                                        <form method="POST" action="{{ route('tasks.open', $taskArea->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info">Open</button>
                                        </form>
                                        @elseif ($taskArea->status == 'done')
                                            <button type="button" class="btn btn-outline-success disabled">Done</button>
                                        @elseif ($taskArea->status == 'approved')
                                            <button type="button" class="btn btn-success disabled">Approved</button>
                                        @else
                                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#doTaskModal-{{ $taskArea->id }}">
                                                Do
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="doTaskModal-{{ $taskArea->id }}" tabindex="-1" aria-labelledby="doTaskLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="POST" action="{{ route('tasks.do', $taskArea->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Complete Task</h5>
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
                                                                    <label for="file">File input</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" name="file" class="custom-file-input" id="file">
                                                                        <label class="custom-file-label" for="file">Choose file</label>
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
