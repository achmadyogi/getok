@extends('layouts.app')

@section('title', $data->app_name)

@section('content')
    <br>
    <h4><b>{{$data->app_name}}</b></h4>
    {{json_encode($result->paramsToJsonObject())}}<br>
    {{json_encode($result->residueToJsonObject())}}<br>
@endsection
