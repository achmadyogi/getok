@extends('layouts.app')

@section('title', 'Ellipsoid List')

@section('content')
<br><br>
<div class="row">
    <div class="col-md-10">
        <div class="jumbotron" style="background-color: white">
            @include('dashboard.alert')
            <h3>Ellipsoid List</h3><br>
            <div class="table">
            	<table>
            		<tr>
            			<th>Year</th>
            			<th>Ellipsoid Name</th>
            			<th>a</th>
            			<th>b</th>
            			<th>1/f</th>
                        <th>Setting</th>
            		</tr>
            		@foreach($ellipsoids as $u)
            			<tr>
            				<td>{{$u->year}}</td>
            				<td>{{$u->ellipsoid_name}}</td>
            				<td>{{$u->a}}</td>
            				<td>{{$u->b}}</td>
                            <td>{{$u->f}}</td>
            				<td>
                                @if($u->id_ellipsoid == 1)
                                    <i>default</i>
                                @else
                                    <span class="fa fa-edit tableButton" title="edit" style="cursor: pointer; font-size: 20px;" onclick="editMe('Modal-edit', '{{$u->id_ellipsoid}}', '{{$u->ellipsoid_name}}')" id="tedit"></span>
                					<span class="fa fa-trash tableButton" title="hapus" style="cursor: pointer; font-size: 20px;" onclick="deleteMe('Modal-delete', '{{$u->id_ellipsoid}}')" id="tdelete"></span>
                                @endif
            				</td>
            			</tr>
            		@endforeach
            		<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            	</table>
            </div>
            <div style="text-align: right;">
                <button class="addButton" onclick="addEllipsoid('Modal-addEllipsoid')"><span class="fa fa-plus"></span> New Ellipsoid</button>
            </div>
            {{$ellipsoids->links()}}
        </div>
    </div>
    <div class="col-md-2">
        @include('dashboard.menu')
    </div>
</div>
@endsection
<!-- Modal Pembatalan -->
<div id="Modal-addEllipsoid" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="border-radius: 7px;">
        <div class="modal-header">
            <table style="width: 100%">
                <tr>
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Tambah Ellipsoid Baru</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('add-ellipsoid') }}">
                {{ csrf_field() }}
                <label>Ellipsoid Name</label>
                <input type="text" name="ellipsoid" class="form-control"><br>
                <label>Year</label>
                <input type="number" name="year" class="form-control" step="any"><br>
                <label>a</label>
                <input type="number" name="a" class="form-control" step="any"><br>
                <label>b</label>
                <input type="number" name="b" class="form-control" step="any"><br>
                <label>1/f</label>
                <input type="number" name="f" class="form-control" step="any"><br>
                <button type="submit" class="button-default">Kirim</button>
                <button type="button" class="button-close" onclick="cancel()">cancel</button>
            </form>
        </div>
    </div>
</div>
<div id="Modal-edit" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="border-radius: 7px;">
        <div class="modal-header">
            <table style="width: 100%">
                <tr>
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Edit Ellipsoid</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('edit-ellipsoid') }}">
                {{ csrf_field() }}
                <div id="editId"></div>
                <label>Year</label>
                <input type="number" name="year" class="form-control" step="any"><br>
                <label>a</label>
                <input type="number" name="a" class="form-control" step="any"><br>
                <label>b</label>
                <input type="number" name="b" class="form-control" step="any"><br>
                <label>1/f</label>
                <input type="number" name="f" class="form-control" step="any"><br>
                <button type="submit" class="button-default">submit</button>
            </form>
        </div>
    </div>
</div>
<div id="Modal-delete" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="border-radius: 7px;">
        <div class="modal-header">
            <table style="width: 100%">
                <tr>
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Hapus Ellipsoid</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('delete-ellipsoid') }}">
                <p>Apakah Anda yakin akan menghapus ellipsoid ini?</p>
                {{ csrf_field() }}
                <div id="theId"></div>
                <button type="submit" class="button-close">Lanjut Hapus</button>
                <button type="button" class="button-default" onclick="cancel()">cancel</button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Get the modal
    var modal;

    function addEllipsoid(myModal){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
    }

    function editMe(myModal, id, ellipsoid){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
        document.getElementById("editId").innerHTML = "<input type=\"hidden\" name=\"id\" value=\"" + id + "\">\
                                                       <label>Ellipsoid Name</label><br>\
                                                       <input type=\"text\" class=\"form-control\" name=\"ellipsoid\" value=\"" + ellipsoid + "\"><br>";
    }

    function deleteMe(myModal, id){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
        document.getElementById("theId").innerHTML = "<input type=\"hidden\" name=\"id\" value=\"" + id + "\">";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function cancel(){
        modal.style.display = "none";
    }

</script>
