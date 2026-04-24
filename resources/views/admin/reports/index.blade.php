@extends('admin.layouts.app')

@section('title',"BlogCast - Reports")

@section('main-content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reported Content</h1>
</div>

@include('admin.layouts._alerts')

{{-- ================= POSTS ================= --}}
<div class="card mb-4">
    <div class="card-header">
        <h5>Reported Posts</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Reports</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>

                    <td>{{ $post->title }}</td>

                    <td>
                        <span class="badge badge-danger">
                            {{ $post->report_count }}
                        </span>
                    </td>

                    <td>
                        @if($post->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @elseif($post->status == 'under_review')
                            <span class="badge badge-warning">Under Review</span>
                        @else
                            <span class="badge badge-danger">Disabled</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('frontend.show', $post->slug) }}"
                           target="_blank"
                           class="btn btn-outline-info btn-sm">
                           <i class="fas fa-eye"></i>
                        </a>

                        <form action="{{ route('admin.reports.approve', ['type'=>'post','id'=>$post->id]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.reports.disable', ['type'=>'post','id'=>$post->id]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No reported posts</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= PODCASTS ================= --}}
<div class="card">
    <div class="card-header">
        <h5>Reported Podcasts</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Reports</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($podcasts as $podcast)
                <tr>
                    <td>{{ $podcast->id }}</td>

                    <td>{{ $podcast->title }}</td>

                    <td>
                        <span class="badge badge-danger">
                            {{ $podcast->report_count }}
                        </span>
                    </td>

                    <td>
                        @if($podcast->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @elseif($podcast->status == 'under_review')
                            <span class="badge badge-warning">Under Review</span>
                        @else
                            <span class="badge badge-danger">Disabled</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('frontend.podcasts.show', $podcast->slug) }}"
                           target="_blank"
                           class="btn btn-outline-info btn-sm">
                           <i class="fas fa-eye"></i>
                        </a>

                        <form action="{{ route('admin.reports.approve', ['type'=>'podcast','id'=>$podcast->id]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.reports.disable', ['type'=>'podcast','id'=>$podcast->id]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No reported podcasts</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
