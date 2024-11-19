@extends('layouts.adminLayout')

@section('title', 'Task List')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="content-wrapper">
    <section class="content">
        <a href="{{ route('tasks.create') }}" class='btn btn-primary m-2'>Create Task</a>

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
                dd(auth()->user()->areas[0]->id)
            @endphp
             --}}
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        @foreach ($task->areas as $area)                        
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
