@extends('layouts.app')

@section('title', 'User Setting')

@section('content')
<br><br>
<div class="row">
    <div class="col-md-10">
        <div class="jumbotron" style="background-color: white"> 
            @include('dashboard.alert')
            <h3>User Setting</h3><br>
            <div class="table">
            	<table>
            		<tr>
            			<th>No</th>
            			<th>Name</th>
            			<th>Email</th>
            			<th>Position</th>
            			<th>Setting</th>
            		</tr>
            		<?php $c = 1; ?>
            		@foreach($user as $u)
            			<tr>
            				<td>{{$c}}</td>
            				<td>{{$u->name}}</td>
            				<td>{{$u->email}}</td>
            				<td>{{$u->position}}</td>
            				<td>
                                @if(Auth::User()->email != $u->email)
            					   <span class="fa fa-trash tableButton" title="hapus" style="cursor: pointer; font-size: 20px;" onclick="deleteMe('Modal-delete', '{{$u->id}}')" id="tdelete"></span>
                                @endif
            				</td>
            			</tr>
                        <?php $c += 1; ?>
            		@endforeach
            		<tr><td></td><td></td><td></td><td></td><td></td></tr>
            	</table>
            </div>
            <div style="text-align: right;">
                <button class="addButton" onclick="addUser('Modal-addUser')"><span class="fa fa-plus"></span> New User</button>
            </div>
            {{$user->links()}}
        </div>
    </div>
    <div class="col-md-2">
        @include('dashboard.menu')
    </div>
</div>
@endsection
<!-- Modal Pembatalan -->
<div id="Modal-addUser" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="border-radius: 7px;">
        <div class="modal-header">
            <table style="width: 100%">
                <tr>
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Tambah Pengguna Baru</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>                                    
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('add-user') }}">
                {{ csrf_field() }}
                <label>Email</label>
                <input type="email" name="email" class="form-control"><br>
                <button type="submit" class="button-default">Kirim</button>
                <button type="button" class="button-close" onclick="cancel()">cancel</button>
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
                    <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Hapus Pengguna</h3></td>
                    <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                </tr>
            </table>                                    
        </div><br>
        <div class="modal-body">
            <form method="POST" action="{{ route('delete-user') }}">
                <p>Apakah Anda yakin akan menghapus pengguna ini?</p>
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

    function addUser(myModal){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
    }

    function deleteMe(myModal, id){
        modal = document.getElementById(myModal);
        modal.style.display = "block";
        document.getElementById("theId").innerHTML = "<input type=\"hidden\" name=\"id\" value=\""+id+"\">";
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
