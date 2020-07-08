@extends('layouts.app')

@section('title', 'Home')

@section('content')
<br><br>
<div class="row">
    @if(session()->has('alert-success'))
        <div class="alert alert-success" role="alert">
            {{session('alert-success')}}
        </div>
    @endif
    @foreach($apps as $a)
    <div class="col-md-3">
        <div class="card" style="height: 450px;">
            <div style='width: 100%; height: 200px; overflow: hidden;margin-top: 0px; position: relative; border-top-left-radius: 5px; border-top-right-radius: 5px;'>
                @if($a->image == NULL)
                    <img class = "card-image" src="{{asset('img/1.jpg')}}" style='position:absolute; left: -100%; right: -100%; top: -100%; bottom: -100%; margin: auto; min-height: 100%; min-width: 100%;' height='450px;' alt='user'>
                @else
                    <img class = "card-image" src="{{$a->image}}" style='position:absolute; left: -100%; right: -100%; top: -100%; bottom: -100%; margin: auto; min-height: 100%; min-width: 100%;' height='450px;' alt='user'>
                @endif
                <div class="overlay">
                    <div class="overlay-text">
                        @if(Auth::guest() && $a->akses == 1)
                            <p>Please login to access</p>
                        @else
                            <a href="{{ route($a->page) }}" style="text-decoration: none;"><button type="button" class="button-default">buka</button></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-content">
                <h4 style="margin-top: 10px;">{{$a->app_name}}</h4>
                <p>{{$a->description}}</p>
                <br>
                @if($a->is_private == 0)
                    <span style="color: #c2c2c2" title="public free access"><span class="fa fa-users"></span> public</span><br>
                @else
                    <span style="color: #c2c2c2" title="limited access"><span class="fa fa-university"></span> limited</span><br>
                @endif
                <a href="#">>> about the app</a><br><br>
            </div>
        </div><br><br>
    </div>
    @endforeach
</div>
@endsection
