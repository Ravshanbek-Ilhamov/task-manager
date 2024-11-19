@extends('layouts.adminLayout')

@section('title', 'Task Area List')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <a href="#" class='btn btn-primary m-2' data-toggle="modal" data-target="#createTaskAreaModal">Create Task Area</a>

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

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Area</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskAreas as $taskArea)
                        <tr>
                            <td>{{ $taskArea->id }}</td>
                            <td>{{ $taskArea->areas->name }}</td>
                            <td>{{ $taskArea->tasks->title }}</td>
                            <td>{{ $taskArea->status }}</td>
                            <td>
                                <a href="#" 
                                   class="btn btn-sm btn-warning edit-btn" 
                                   data-id="{{ $taskArea->id }}" 
                                   data-area_id="{{ $taskArea->area_id }}" 
                                   data-task_id="{{ $taskArea->task_id }}" 
                                   data-status="{{ $taskArea->status }}" 
                                   data-toggle="modal" 
                                   data-target="#editTaskAreaModal">Edit</a> 
                                <form action="/taskArea/delete/{{ $taskArea->id }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $taskAreas->links() }}
        </div>
    </section>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createTaskAreaModal" tabindex="-1" role="dialog" aria-labelledby="createTaskAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/taskArea/store" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskAreaModalLabel">Create Task Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="area_id">Area</label>
                        
                        <input type="number" class="form-control" id="area_id" name="area_id" required>
                    </div>
                    <div class="form-group">
                        <label for="task_id">Task ID</label>
                        <input type="number" class="form-control" id="task_id" name="task_id" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="" selected disabled>Select Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editTaskAreaModal" tabindex="-1" role="dialog" aria-labelledby="editTaskAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/taskArea/update" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskAreaModalLabel">Edit Task Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-area_id">Area ID</label>
                        <input type="number" class="form-control" id="edit-area_id" name="area_id" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-task_id">Task ID</label>
                        <input type="number" class="form-control" id="edit-task_id" name="task_id" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select class="form-control" id="edit-status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const areaId = button.getAttribute('data-area_id');
                const taskId = button.getAttribute('data-task_id');
                const status = button.getAttribute('data-status');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-area_id').value = areaId;
                document.getElementById('edit-task_id').value = taskId;
                document.getElementById('edit-status').value = status;
            });
        });

        const deleteForms = document.querySelectorAll('form[action^="/taskArea/delete"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', (event) => {
                const confirmation = confirm('Are you sure you want to delete this task area?');
                if (!confirmation) {
                    event.preventDefault();
                }
            });
        });
    });
</script>

@endsection
