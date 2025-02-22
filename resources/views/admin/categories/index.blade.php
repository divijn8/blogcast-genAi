@extends('admin.layouts.app')

@section('title',"Pen It - Categories")

@section('main-content')
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
                            <td></td>
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
@endsection
