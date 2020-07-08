@if(session()->has('alert-success'))
    <div class="alert alert-success" role="alert">
        {{session('alert-success')}}
    </div>
@endif
@if(session()->has('alert-failed'))
	<div class="alert alert-danger" role="alert">
        {{session('alert-failed')}}
    </div>
@endif