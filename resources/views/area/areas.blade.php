@extends('layouts.adminLayout')

@section('title', 'Area List')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <a href="#" class='btn btn-primary m-2' data-toggle="modal" data-target="#createAreaModal">Create Area</a>

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

            {{-- @php
                dd($areas[0]->users->name);
            @endphp --}}

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>User ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areas as $area)
                        <tr>
                            <td>{{ $area->id }}</td>
                            <td>{{ $area->name }}</td>
                            <td>{{ $area->users->name }}</td>
                            <td>
                                <a href="#" 
                                   class="btn btn-sm btn-warning edit-btn" 
                                   data-id="{{ $area->id }}" 
                                   data-name="{{ $area->name }}" 
                                   data-user_id="{{ $area->user_id }}" 
                                   data-toggle="modal" 
                                   data-target="#editAreaModal">Edit</a> 
                                   <form action="/area/delete/{{ $area->id }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-btn">Delete</button>
                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $areas->links() }}
        </div>
    </section>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createAreaModal" tabindex="-1" role="dialog" aria-labelledby="createAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/area/store" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAreaModalLabel">Create Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user-id">User</label>
                        <select class="form-control" id="user-id" name="user_id" required>
                            <option value="" disabled selected>Select a User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="area-name">Area Name</label>
                        <input type="text" class="form-control" id="area-name" name="name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
<div class="modal fade" id="editAreaModal" tabindex="-1" role="dialog" aria-labelledby="editAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/area/update" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAreaModalLabel">Edit Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-area-id" name="id">
                    <div class="form-group">
                        <label for="edit-user-id">User</label>
                        <select class="form-control" id="edit-user-id" name="user_id" required>
                            <option value="" disabled>Select a User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-area-name">Area Name</label>
                        <input type="text" class="form-control" id="edit-area-name" name="name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
              const name = button.getAttribute('data-name');
              const userId = button.getAttribute('data-user_id');

              document.getElementById('edit-area-id').value = id;
              document.getElementById('edit-user-id').value = userId;
              document.getElementById('edit-area-name').value = name;
          });
      });

      const deleteForms = document.querySelectorAll('.delete-btn');
      deleteForms.forEach(form => {
          form.addEventListener('click', (event) => {
              const confirmation = confirm('Are you sure you want to delete this area?');
              if (!confirmation) {
                  event.preventDefault();
              }
          });
      });
  });
</script>

@endsection
