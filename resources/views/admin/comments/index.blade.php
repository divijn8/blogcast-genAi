@extends('admin.layouts.app')

@section('main-content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Comments</h1>
    </div>

    @include('admin.layouts._alerts')

    @forelse ($comments as $comment)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <!-- Commentable title -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 text-dark">
                        @if ($comment->commentable instanceof App\Models\Post)
                            <i class="fas fa-newspaper text-primary"></i>
                        @elseif ($comment->commentable instanceof App\Models\Podcast)
                            <i class="fas fa-microphone text-primary"></i>
                        @endif

                        {{ $comment->commentable->title ?? 'Unknown content' }}
                    </h5>

                    @if ($comment->approved_by)
                        <span class="badge badge-success px-3 py-2">Approved</span>
                    @else
                        <span class="badge badge-danger px-3 py-2">Unapproved</span>
                    @endif
                </div>

                <hr>

                <!-- Author -->
                <div class="mb-2 text-muted">
                    <i class="fas fa-user"></i>
                    {{ $comment->guest_name ?? $comment->user?->name ?? 'Guest' }}

                    @if ($comment->parent)
                        <span class="badge badge-secondary ml-2">Reply</span>
                    @endif
                </div>

                <!-- Comment text -->
                <p class="mb-3">
                    {{ $comment->comment }}
                </p>

                <!-- Actions -->
                <div class="text-right">
                    @if ($comment->approved_by)
                        <button class="btn btn-outline-warning btn-sm"
                                data-toggle="modal"
                                data-target="#unapproveModal"
                                onclick="setUnapproveAction('{{ route('admin.comments.unapprove', $comment) }}')">
                            <i class="fas fa-times"></i> Unapprove
                        </button>
                    @else
                        <button class="btn btn-outline-success btn-sm"
                                data-toggle="modal"
                                data-target="#approveModal"
                                onclick="setApproveAction('{{ route('admin.comments.approve', $comment) }}')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-comments fa-2x mb-3"></i>
                <h5>No comments found</h5>
                <p>There are no comments to review right now.</p>
            </div>
        </div>
    @endforelse

</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="approveForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Comment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this comment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Unapprove Modal -->
<div class="modal fade" id="unapproveModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="unapproveForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unapprove Comment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to unapprove this comment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-warning">Yes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-level-scripts')
<script>
    function setApproveAction(url) {
        document.getElementById('approveForm').action = url;
    }

    function setUnapproveAction(url) {
        document.getElementById('unapproveForm').action = url;
    }
</script>
@endsection
