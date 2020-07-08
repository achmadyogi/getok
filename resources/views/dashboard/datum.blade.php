@extends('layouts.app')

@section('title', 'Datum List')

@section('content')
<br><br>
<div class="row">
    <div class="col-md-10">
        <div class="jumbotron" style="background-color: white">
            @include('dashboard.alert')
            <h3>Datum List</h3><br>
            <div class="table">
            	<table>
            		<tr>
            			<th>ID Datum</th>
            			<th>Datum Name</th>
            			<th>Ellipsoid Name</th>
                        <th>Setting</th>
            		</tr>
            		@foreach($datums as $u)
            			<tr>
            				<td>{{$u->id_datum}}</td>
            				<td>{{$u->datum_name}}</td>
            				<td>{{$u->ellipsoid->ellipsoid_name}}</td>
            				<td>
                                <span class="fa fa-edit tableButton" title="edit" style="cursor: pointer; font-size: 20px;" onclick="editMe('Modal-edit', '{{$u->id_datum}}', '{{$u->datum_name}}')" id="tedit"></span>
                				<span class="fa fa-trash tableButton" title="hapus" style="cursor: pointer; font-size: 20px;" onclick="deleteMe('Modal-delete', '{{$u->id_datum}}')" id="tdelete"></span>
            				</td>
            			</tr>
            		@endforeach
            		<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            	</table>
            </div>
            <div style="text-align: right;">
                <button class="addButton" onclick="addDatum('Modal-addDatum')"><span class="fa fa-plus"></span> New Datum</button>
            </div>
            {{$datums->links()}}
        </div>
    </div>
    <div class="col-md-2">
        @include('dashboard.menu')
    </div>
</div>
@endsection
<!-- Modal Pembatalan -->
<div id="Modal-addDatum" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="border-radius: 7px;">
        <div class="modal-header">
            <table style="width: 100%">
                <tr>
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Tambah Datum Baru</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('add-datum') }}">
                {{ csrf_field() }}
                <label>Datum Name</label>
                <input type="text" name="datum" class="form-control"><br>
                <label>Ellisoid Name</label>
                <select name="ellipsoid"class="form-control">
                    @foreach($ellipsoids as $e)
                        <option value="{{$e->id_ellipsoid}}">{{$e->ellipsoid_name}}</option>
                    @endforeach
                </select><br><br>
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
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Edit Datum</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('edit-datum') }}">
                {{ csrf_field() }}
                <div id="editId"></div>
                <label>Datum Name</label>
                <input type="text" name="datum" class="form-control"><br>
                <label>Ellisoid Name</label>
                <select name="ellipsoid"class="form-control">
                    @foreach($ellipsoids as $e)
                        <option value="{{$e->id_ellipsoid}}">{{$e->ellipsoid_name}}</option>
                    @endforeach
                </select><br><br>
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
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Hapus Datum</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('delete-datum') }}">
                <p>Apakah Anda yakin akan menghapus datum ini?</p>
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

    function addDatum(myModal){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
    }

    function editMe(myModal, id, datum){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
        document.getElementById("editId").innerHTML = "<input type=\"hidden\" name=\"id\" value=\"" + id + "\">\
                                                       <label>Datum Name</label><br>\
                                                       <input type=\"text\" class=\"form-control\" name=\"datum\" value=\"" + datum + "\"><br>";
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
