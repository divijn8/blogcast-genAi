@extends('admin.layouts.app')

@section('title',"BlogCast - All Podcasts")

@section('main-content')
    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Podcasts</h1>
    <a href={{ route('admin.podcasts.create') }} class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-pen fa-sm text-white-50"></i> Add New Podcast</a>
</div>
@include('admin.layouts._alerts')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Id</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach($podcasts as $podcast)
                        <tr>
                                <td>{{ $podcast->id }}</td>
                                <td width="20%">
                                    <img src="{{ asset($podcast->thumbnail_path) }}" alt="{{ $podcast->title }}" class="img-fluid">
                                </td>
                                <td>{{ $podcast->title }}</td>
                                <td>{{ $podcast->category->name }}</td>
                                <td>{{ implode(', ', $podcast->tags->pluck('name')->toArray()) }}</td>
                                <td>
                                    <a href="{{ route('admin.podcasts.edit', $podcast) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger delete-podcasts" data-toggle="modal" data-target="#deleteModal" data-podcast-id="{{ $podcast->id }}">
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
                {{$podcasts->links('vendor.pagination.bootstrap-5')}}
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
                <p>Are you sure you wanna delete this podcasts?</p>
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
        const deletePodcastButtons = document.querySelectorAll('.delete-podcasts');
        const deleteForm = document.querySelector("#deleteForm");
        function loadDeleteModal(evt) {
            let podcastId = null;
            if(evt.target.classList.contains('delete-podcasts')) {
                podcastId = evt.target.dataset.podcastId;
            } else if(evt.target.parentElement.classList.contains('delete-podcasts')) {
                podcastId = evt.target.parentElement.dataset.podcastId;
            }
            deleteForm.setAttribute('action', `/admin/podcastss/${podcastId}`);
        }

        deletePodcastButtons.forEach(function(deletePodcastButtons) {
            deletePodcastButtons.addEventListener('click', loadDeleteModal);
        });
    </script>
@endsection


