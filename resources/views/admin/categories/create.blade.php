@extends('admin.layouts.app')

@section('title',"Pen It - Create Categories")

@section('main-content')
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create New Category</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action={{ route('admin.categories.store') }} method="POST" class="form">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text"
                                       class="form-control @error('name')is-invalid @enderror"
                                       name="name"
                                       value="{{old('name')}}"
                                       id="name"/>
                                @error('name')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <button type="submit" class="btn btn-outline-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
