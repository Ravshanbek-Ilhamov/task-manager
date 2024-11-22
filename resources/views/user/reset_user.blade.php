@extends('layouts.adminLayout')

@section('title', 'Update User')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <!-- Greeting -->
            <h1 class="mt-4">Hello, {{auth()->user()->name}}</h1>
            <p class="lead">You can update the your email and password using the form below.</p>

            <!-- Success and Error Messages -->
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

            <!-- Update Form -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Update User</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', auth()->user()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endsection
