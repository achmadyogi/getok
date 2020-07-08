<style type="text/css">
	.myBox{
		padding: 15px 15px 15px 15px;
		color: #0769B0;
		cursor: pointer;
		font-size: 20px;
		border-bottom: 1px solid grey;
	}

	.top{
		border-top: 1px solid grey;
	}

	.myBox:hover{
		background-image: linear-gradient(to right, transparent, lightgrey, transparent);
	}

	.myBox_active{
		padding: 15px 15px 15px 15px;
		color: #0769B0;
		cursor: pointer;
		font-size: 20px;
		border-bottom: 1px solid grey;
		background-color: lightgrey;
	}

	.myBox_active a{
		text-decoration: none;
	}
</style>

@if(session()->has('menu') && session('menu') == 'dashboard')
	<div class="myBox_active top">
		<a href="{{route('dashboard')}}">Dashboard</a>
	</div>
@else
	<a href="{{route('dashboard')}}" style="text-decoration: none">
		<div class="myBox top">
			Dashboard
		</div>
	</a>
@endif
@if(session()->has('menu') && session('menu') == 'user-setting')
	<div class="myBox_active">
		<a href="{{route('user-setting')}}" >User Setting</a>
	</div>
@else
	<a href="{{route('user-setting')}}" style="text-decoration: none">
		<div class="myBox">
			User Setting
		</div>
	</a>
@endif
@if(session()->has('menu') && session('menu') == 'ellipsoid-list')
	<div class="myBox_active">
		<a href="{{route('ellipsoid-list')}}" >Ellipsoid List</a>
	</div>
@else
	<a href="{{route('ellipsoid-list')}}" style="text-decoration: none">
		<div class="myBox">
			Ellipsoid List
		</div>
	</a>
@endif
@if(session()->has('menu') && session('menu') == 'datum-list')
    <div class="myBox_active">
        <a href="{{route('datum-list')}}" >Datum List</a>
    </div>
@else
    <a href="{{route('datum-list')}}" style="text-decoration: none">
        <div class="myBox">
            Datum List
        </div>
    </a>
@endif
