@extends('admin.layouts.app')

@section('title',"Pen It - Tags")

@section('main-content')
    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tags</h1>
    <a href={{ route('admin.tags.create') }} class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-cog fa-sm text-white-50"></i> Create Tag</a>
</div>
@include('admin.layouts._alerts')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag )
                        <tr>
                            <td>{{$tag->id}}</td>
                            <td>{{$tag->name}}</td>
                            <td>
                                <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-outline-danger delete-tag" data-toggle="modal" data-target="#deleteModal" data-tag-id="{{ $tag->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-12">
                {{$tags->links('vendor.pagination.bootstrap-5')}}
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Modal</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you wanna delete this tag?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete it!</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-level-scripts')
    <script>
        const deleteTagButtons = document.querySelectorAll('.delete-tag');
        const deleteForm = document.querySelector("#deleteForm");
        function loadDeleteModal(evt) {
            let tagId = null;
            if(evt.target.classList.contains('delete-tag')) {
                tagId = evt.target.dataset.tagId;
            } else if(evt.target.parentElement.classList.contains('delete-tag')) {
                tagId = evt.target.parentElement.dataset.tagId;
            }
            deleteForm.setAttribute('action', `/admin/tags/${tagId}`);
        }

        deleteTagButtons.forEach(function(deleteTagButton) {
            deleteTagButton.addEventListener('click', loadDeleteModal);
        });
    </script>
@endsection

