@extends('layouts.app')

@section('title', 'Account Activation')

@section('content')
<br><br>
<div class="jumbotron" style="background-color: white">
	<h2>Account Activation</h2><br>
	@if($go == 0)
		<div class="alert alert-warning" role="alert">
			Link ini sudah tidak tersedia karena sudah terpakai.
		</div>
	@else
		<p>Anda telah diundah untuk menggunakan aplikasi GETOK. Isi formulir di bawah ini untuk memulai.</p> 
		<form method="POST" action="{{ route('new-user') }}">
			<div class="row">
				<div class="col-md-6">
					{{ csrf_field() }}
					<input type="hidden" name="code" value="{{$code}}">
					<input type="hidden" name="email" value="{{$email}}">
					<label>Email</label><br>
					<input type="text" name="mail" class="form-control" value="{{$email}}" disabled><br>
					<label>Nama Lengkap</label><br>
					<input type="text" name="name" class="form-control" required><br>
					<label>Position</label><br>
					<input type="text" name="position" class="form-control" required><br>
				</div>
				<div class="col-md-6">
					<label>Username</label><br>
					<input type="text" name="username" class="form-control" required><br>
					<label>Password</label><br>
					<input type="password" name="password" class="form-control" required><br>
					<div style="text-align: right;">
						<button type="submit" class="button-default">Submit</button>
					</div>
				</div>
			</div>
		</form>
	@endif
</div>
@endsection
