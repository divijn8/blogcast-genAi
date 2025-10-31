@extends('admin.layouts.app')

@section('title',"BlogCast - Categories")

@section('main-content')
    <!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    <a href={{ route('admin.categories.create') }} class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-cog fa-sm text-white-50"></i> Create Category</a>
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
                        @foreach ($categories as $category )
                        <tr>
                            <td>{{$category->id}}</td>
                            <td>{{$category->name}}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-outline-danger delete-category" data-toggle="modal" data-target="#deleteModal" data-category-id="{{ $category->id }}">
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
                {{$categories->links('vendor.pagination.bootstrap-5')}}
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
                <p>Are you sure you wanna delete this category?</p>
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
        const deleteCategoryButtons = document.querySelectorAll('.delete-category');
        const deleteForm = document.querySelector("#deleteForm");
        function loadDeleteModal(evt) {
            let categoryId = null;
            if(evt.target.classList.contains('delete-category')) {
                categoryId = evt.target.dataset.categoryId;
            } else if(evt.target.parentElement.classList.contains('delete-category')) {
                categoryId = evt.target.parentElement.dataset.categoryId;
            }
            deleteForm.setAttribute('action', `/admin/categories/${categoryId}`);
        }

        deleteCategoryButtons.forEach(function(deleteCategoryButton) {
            deleteCategoryButton.addEventListener('click', loadDeleteModal);
        });
    </script>
@endsection

