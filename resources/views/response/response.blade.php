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
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Area</th>
                        <th>Title</th>
                        <th>Task</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responses as $response)
                        <tr>
                            <td>{{ $response->id }}</td>
                            <td>
                                <span style="font-size:16px" class="badge badge-info">{{ $response->area->name ?? 'N/A' }}</span>
                            </td>
                            <td>{{ ucfirst($response->title) }}</td>
                            <td>
                                <button class="btn btn-info btn-sm task-btn" data-toggle="modal" data-target="#taskModal" 
                                        data-task-title="{{ $response->task->title ?? 'N/A' }}"
                                        data-category="{{ $response->task->categories->name ?? 'N/A' }}"
                                        data-performer="{{ $response->task->performer ?? 'N/A' }}"
                                        data-file="{{ $response->task->file ?? 'N/A' }}"
                                        data-period="{{ $response->task->period ?? 'N/A' }}">
                                    View Task
                                </button>
                                <!-- Task Details Modal -->
                                <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <p><strong>Title:</strong> <span id="modal-task-title">N/A</span></p>
                                                    <p><strong>Category:</strong> <span id="modal-category">N/A</span></p>
                                                    <p><strong>Performer:</strong> <span id="modal-performer">N/A</span></p>
                                                    <p><strong>Period:</strong> <span id="modal-period">N/A</span></p>
                                                    <p><strong>File:</strong> <span id="modal-file">N/A</span></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                @if ($response->file)
                                    <a href="{{ asset('storage/' . $response->file) }}" target="_blank">View File</a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td>
                                <span style="font-size:16px" class="badge badge-{{ $response->status == 'approved' ? 'success' : ($response->status == 'rejected' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($response->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($response->status == 'approved')
                                    <button class="btn btn-secondary btn-sm" title="Done" disabled>
                                        <i class="fas fa-check-double"></i> Done
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('responses.accept', $response->id) }}" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Accept">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                        <!-- Updated Reject Button -->
                                        <button type="button" class="btn btn-danger btn-sm reject-btn" 
                                                title="Reject" data-toggle="modal" data-target="#rejectModal" 
                                                data-response-id="{{ $response->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <!-- Modal for Reject Comment -->
                                                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route('responses.rejectWithComment') }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="response_id" id="modal_response_id">
                                                                    <div class="form-group">
                                                                        <label for="comment">Comment</label>
                                                                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                    {{-- </form> --}}
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $responses->links() }}
        </div>
    </section>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const taskButtons = document.querySelectorAll('.task-btn');
    taskButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modal-task-title').textContent = this.getAttribute('data-task-title');
            document.getElementById('modal-category').textContent = this.getAttribute('data-category');
            document.getElementById('modal-performer').textContent = this.getAttribute('data-performer');
            const fileLink = this.getAttribute('data-file');
            document.getElementById('modal-file').innerHTML = fileLink ? 
                `<a href="/storage/${fileLink}" target="_blank">View File</a>` : 'No File';
            document.getElementById('modal-period').textContent = this.getAttribute('data-period');
        });
    });
});

    document.addEventListener("DOMContentLoaded", function () {
        // Attach event listeners to all reject buttons
        const rejectButtons = document.querySelectorAll('.reject-btn');
        rejectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const responseId = this.getAttribute('data-response-id');
                document.getElementById('modal_response_id').value = responseId;
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#start_date", { dateFormat: "Y-m-d" });
        flatpickr("#end_date", { dateFormat: "Y-m-d" });
    });
</script>

@endsection
