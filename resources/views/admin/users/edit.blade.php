@extends('admin.layouts.app')

@section('title', "Pen It - Tag Updation")

@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Update Tag</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text"
                                   class="form-control @error('name')is-invalid @enderror"
                                   name="name"
                                   value="{{old('name', $user->name)}}"
                                   id="name"/>
                            @error('name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email')is-invalid @enderror"
                                   name="email"
                                   value="{{old('email', $user->email)}}"
                                   id="name"/>
                            @error('name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-group">
                            <button type="submit" class="btn btn-outline-warning">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
