@extends('admin.layouts.app')

@section('page-level-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
:root { --primary-glow: #4e73df; --bg-soft: #f8f9fc; }
body { background-color: #f3f4f6; }

.studio-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    background: #fff;
    margin-bottom: 2rem;
}

.studio-header {
    padding: 1.5rem;
    border-bottom: 1px solid #edf2f7;
}

.section-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: #4a5568;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
}

.form-control-studio {
    border: 2px solid #edf2f7;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 0.95rem;
}

.upload-zone {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    background: var(--bg-soft);
}

.btn-glow {
    box-shadow: 0 4px 14px rgba(78,115,223,0.39);
    border-radius: 10px;
    font-weight: 700;
    padding: 12px;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid pb-5">

<form action="{{ route('admin.podcasts.update', $podcast->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="row">

<div class="col-lg-7">
<div class="studio-card">
<div class="studio-header">
<h4 class="font-weight-bold">Edit Podcast Episode</h4>
</div>

<div class="card-body p-4">

<div class="form-group mb-4">
<label>Title</label>
<input type="text" name="title" value="{{ old('title', $podcast->title) }}" class="form-control-studio w-100">
</div>

<div class="form-group mb-4">
<label>Description</label>
<textarea name="description" class="form-control-studio w-100">{{ old('description', $podcast->description) }}</textarea>
</div>

<div class="row mb-4">
<div class="col-md-6">
<label>Category</label>
<select name="category_id" class="form-control select2">
@foreach($categories as $category)
<option value="{{ $category->id }}" {{ $category->id == $podcast->category_id ? 'selected' : '' }}>
{{ $category->name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-6">
<label>Tags</label>
<select name="tags[]" class="form-control select2" multiple>
@foreach($tags as $tag)
<option value="{{ $tag->id }}" {{ in_array($tag->id, $podcast->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
{{ $tag->name }}
</option>
@endforeach
</select>
</div>
</div>

<div class="form-group mb-4">
<label>Publish Date</label>
<input type="text" name="published_at" value="{{ $podcast->published_at }}" class="form-control-studio w-100">
</div>

<span class="section-title">Media</span>

<div class="row">
<div class="col-md-6 mb-4">
<div class="upload-zone" onclick="document.getElementById('thumbnail').click();">
<p>Upload Thumbnail</p>
<input type="file" name="thumbnail" id="thumbnail" hidden>
<img src="{{ $podcast->thumbnail ? asset('storage/'.$podcast->thumbnail) : '' }}" style="max-height:100px;">
</div>
</div>

<div class="col-md-6 mb-4">
<div class="upload-zone" onclick="document.getElementById('audio_file').click();">
<p>Upload Audio</p>
<input type="file" name="audio_file" id="audio_file" hidden>
</div>
</div>
</div>

@if($podcast->audio_file)
<audio controls class="w-100">
<source src="{{ asset($podcast->audio_file) }}">
</audio>
@endif

</div>
</div>
</div>

<div class="col-lg-5">
<div class="studio-card p-4">
<button type="submit" class="btn btn-primary btn-block btn-glow">
Update Podcast
</button>
</div>
</div>

</div>

</form>
</div>
@endsection

@section('page-level-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$('.select2').select2();
flatpickr("[name='published_at']", { enableTime: true });
</script>
@endsection
