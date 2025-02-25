@extends('admin.layouts.app')

@section('page-level-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <style>
        .is-invalid+.select2 > .selection > .select2-selection.select2-selection--multiple {
            border: solid 1px red;
        }
    </style>
@endsection


@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Post</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text"
                                   class="form-control @error('title')is-invalid @enderror"
                                   name="title"
                                   value="{{old('title')}}"
                                   id="title"/>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control @error('excerpt')is-invalid @enderror"
                                      name="excerpt"
                                      id="excerpt"
                            >{{old('excerpt')}}</textarea>
                            @error('excerpt')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <trix-editor input="body"></trix-editor>
                            <input type="hidden"
                                   class="form-control @error('body')is-invalid @enderror"
                                   name="body"
                                   id="body"
                                   value="{{old('body')}}"
                            />
                            @error('body')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file"
                                   accept="image/*"
                                   class="form-control @error('thumbnail')is-invalid @enderror"
                                   name="thumbnail"
                                   value="{{old('thumbnail')}}"
                                   id="thumbnail"/>
                            @error('thumbnail')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                    name="category_id"
                                    id="category_id"
                            >
                                <option value="0" disabled selected>Select Category....</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tag_id" class="form-label">Tags</label>
                            <select class="form-control select2 @error('tags') is-invalid @enderror"
                                    name="tags[]"
                                    id="tag_id"
                                    multiple
                            >
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type="submit" class="btn btn-outline-primary" value="Create Post">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page-level-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection
