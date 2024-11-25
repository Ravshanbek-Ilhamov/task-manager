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
                            <h5>{{$countall}} Tasks</h5>
                            <p>All of the Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('controltasks.filter', ['status'=>'all']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks in 2 Days -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h5>{{$twodays}} Tasks</h5>
                            <p>Tasks within 2 days</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('controltasks.filter', ['status'=>'twodays']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks Tomorrow -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h5>{{$tomorrow}} Tasks</h5>
                            <p>Tasks For Tomorrow</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('controltasks.filter', ['status'=>'tomorrow']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            
                <!-- Tasks Today -->
                <div class="col-lg-2 col-6 mx-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h5>{{$today}} Tasks</h5>
                            <p>Tasks For Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="{{ route('controltasks.filter', ['status'=>'today']) }}" class="small-box-footer">
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
                        <a href="{{ route('controltasks.filter', ['status'=>'expired']) }}" class="small-box-footer">
                            See All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Areas</th>
                                @foreach ($categories as $category)
                                    <th>{{$category->name}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                            <tr>
                                <td>{{ $area->name }}</td>
                                @foreach ($categories as $category)
                                @php
                                    $count = $taskAreas->where('area_id',$area->id)->where('category_id',$category->id)->count()
                                @endphp
                                    <td>
                                        @if ($count != 0)
                                            <a href="{{ route('tasks.byAreaAndCategory', ['area' => $area->id, 'category' => $category->id]) }}" 
                                            style="font-size: 15px" 
                                            class="badge badge-{{$btncolor}}">
                                                {{$count}}
                                            </a>
                                        @endif
                                        
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
        </div>
    </section>
</div>

                                        
<script>
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
