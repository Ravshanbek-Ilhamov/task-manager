@extends('layouts.adminLayout')

@section('title', 'Edit Task')

@section('content')
<!-- Select2 CSS -->
<style>
    /* Override the background color and text color of selected items */
.choices__inner {
    background-color: #f8f9fa; /* Light gray for better contrast */
    color: #333; /* Dark text color */
    border-color: #ced4da; /* Adjust border color if needed */
}

.choices__list--multiple .choices__item {
    background-color: #007bff; /* Primary color for selected items */
    color: white; /* White text on selected items */
    border-radius: 5px; /* Rounded corners for selected items */
    padding: 5px 10px; /* Adjust padding for readability */
}

.choices__list--dropdown .choices__item--selectable {
    background-color: #ffffff; /* White background for dropdown items */
    color: #333; /* Dark text color */
}

.choices__list--dropdown .choices__item--selectable:hover {
    background-color: #e9ecef; /* Slight hover effect for dropdown items */
}

</style>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h2>Edit Task</h2>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category_id" id="category" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $task->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="performer">Performer</label>
                    <input type="text" name="performer" id="performer" class="form-control" value="{{ old('performer', $task->performer) }}" required>
                    @error('performer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="permissions">Area</label>
                    <select id="permissions" name="area_id[]" multiple>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ (collect(old('area_id'))->contains($area->id)) ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <label style="color: red">{{ $message }}</label>
                    @enderror
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
                    @if ($task->file)
                        <small>Current File: <a href="{{ asset('storage/' . $task->file) }}" target="_blank">View File</a></small>
                    @endif
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                  </div>

                <div class="form-group">
                    <label for="period">Period</label>
                    <input type="date" name="period" id="period" class="form-control" value="{{ old('period', $task->period) }}" required>
                    @error('period')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </section>
</div>

<!-- Scripts -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('permissions');
        const choices = new Choices(element, {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: "Select Areas"
        });
    });
</script>
@endsection
