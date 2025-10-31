@extends("admin.layouts.app")
@section('page-level-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('main-content')
<div class="container-fluid">
    <!-- PAge Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Drafted Blogs</h1>
        <a href="{{ route("admin.posts.create")}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Add New Blogs</a>
    </div>
    <!-- End of Page Heading -->

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered responsive">
                <thead>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Excerpt</th>
                    <th>Category</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach ($blogs as $blog)
                        <tr>
                            <td>{{ $blog->id }}</td>
                            <td><img src="{{ asset($blog->image_path) }}" alt="{{$blog->title}}" width="80px"></td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ $blog->excerpt }}</td>
                            <td>{{ $blog->category->name }}</td>
                            <td>
                                <form action="{{route('admin.posts.publish',$blog->id)}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="flatpickr" id="published_at" name="published_at">
                                        <input type="text" id="published_at" name="published_at" placeholder="Select Date.." data-input> <!-- input is mandatory -->
                                        <a class="input-button" title="toggle" data-toggle>
                                            <i class="icon-calendar"></i>
                                        </a>
                                        <a class="input-button" title="clear" data-clear>
                                            <i class="icon-close"></i>
                                        </a>
                                    </div>
                                    <button class="btn btn-primary mt-3" type="submit">
                                        Publish</button>
                                </form>
                                <a href="{{ route('admin.posts.edit', $blog->id)}}" class="btn btn-primary"><i class="fas fa-pen"></i></a>
                            <button class="btn btn-outline-danger delete-post" data-toggle="modal" data-target="#deleteModal" data-post-id="{{ $blog->id }}">
                                            <i class="fas fa-trash"></i>
                            </button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($blogs->count() == 0)
                <p>You have no drafted blogs! </p>
            @endif
            {{$blogs->links('vendor.pagination.bootstrap-5')}}
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
                <p>Are you sure you wanna delete this post?</p>
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

<!--Publish Blogs-->
<div class="modal fade" id="publishBlog" tabindex="-1" role="dialog" aria-labelledby="publishBlogLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="POST" id="publishBlogForm">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publishBlogLabel">Publish Blog?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Are you sure you want to publish blog?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                <button class="btn btn-primary" type="submit">Yes</button>
            </div>
        </div>
        </form>
    </div>
</div>


@endsection

@section('page-level-scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function deleteMadalHelper(url) {
        document.getElementById("deleteForm").setAttribute('action', url)
    }

    function publishBlog(url){
        console.log(url);
        document.getElementById('publishBlogForm').setAttribute('action',url);
    }

    flatpickr("#published_at", {
        enableTime: true,
        altInput: true,
        altFormat: "F j, Y H:i",
        dateFormat: "Y-m-d H:i",
        wrap: true,
        minDate: "today",
    })

    </script>
@endsection


