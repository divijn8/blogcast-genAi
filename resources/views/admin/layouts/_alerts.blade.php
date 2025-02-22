@if (session()->has('success'))
<div class="alert alert-success fade show">
    {{session('success')}}
</div>
@endif

@if (session()->has('error'))
<div class="alert alert-danger fade show">
    {{session('error')}}
</div>
@endif
