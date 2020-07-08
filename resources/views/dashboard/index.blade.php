@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<br><br>
<div class="row">
    <div class="col-md-10">
        <h3>Dashboard</h3><br>
    </div>
    <div class="col-md-2">
        @include('dashboard.menu')
    </div>
</div>
@endsection
