@extends("admin.layouts.app")

@section('title',"BlogCast - Draft Blogs")

@section('page-level-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .draft-thumb {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .action-box {
        min-width: 260px;
    }

    .action-buttons .btn {
        margin-right: 4px;
    }
</style>
@endsection

@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Drafted Blogs</h1>
        <a href="{{ route('admin.posts.create') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-pen fa-sm text-white-50"></i> Add New Blog
        </a>
    </div>

    @include('admin.layouts._alerts')

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Actions</th>
                </thead>

                <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td>{{ $blog->id }}</td>

                            <td>
                                <img src="{{ asset($blog->thumbnail_path) }}"
                                    alt="{{ $blog->title }}"
                                    class="draft-thumb">
                            </td>

                            <td>{{ $blog->title }}</td>

                            <td>{{ $blog->category->name }}</td>

                            <td class="action-box">
                                <!-- Publish -->
                                <form action="{{ route('admin.posts.publish', $blog->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Date picker -->
                                    <input type="text"
                                        name="published_at"
                                        class="form-control form-control-sm flatpickr-input mb-2"
                                        placeholder="Select publish date">

                                    <!-- Buttons in single line -->
                                    <div class="action-buttons d-flex align-items-center">
                                        <button class="btn btn-outline-success btn-sm publish-btn"
                                                disabled>
                                            <i class="fas fa-upload"></i> Publish
                                        </button>

                                        <a href="{{ route('admin.posts.edit', $blog->id) }}"
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm delete-post"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-post-id="{{ $blog->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                You have no drafted blogs!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $blogs->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Delete Blog</h1>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    Are you sure you wanna delete this blog?
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Cancel
                    </button>

                    <form action="" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('.flatpickr-input', {
        enableTime: true,
        time_24hr: true,
        defaultDate: null,        // nothing preselected
        minDate: "today",         // past dates disabled
        altInput: true,
        altFormat: "F j, Y H:i",
        dateFormat: "Y-m-d H:i",

        onOpen: function(selectedDates, dateStr, instance) {
            const now = new Date();
            instance.set('minTime', now);
        },

        onChange: function(selectedDates, dateStr, instance) {
            const form = instance.input.closest('form');
            const btn = form.querySelector('.publish-btn');
            btn.disabled = !dateStr;   // enable only after date+time chosen
        }
    });

    // Delete modal handler
    const deletePostButtons = document.querySelectorAll('.delete-post');
    const deleteForm = document.querySelector("#deleteForm");

    deletePostButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            deleteForm.setAttribute(
                'action',
                `/admin/posts/${this.dataset.postId}`
            );
        });
    });
</script>
@endsection
