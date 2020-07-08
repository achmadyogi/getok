@extends('layouts.app')

@section('title', $data->nama_app)

@section('content')
    <br>
    <div class="container">
        @include("alerts")
        <h3>{{$data->app_name}}</h3><br>
        <h4><b>Survey List Available</b></h4><br>
        <div class="table">
            <table>
                <tr>
                    <th>Survey ID</th>
                    <th>Survey Name</th>
                    <th>Survey Date</th>
                    <th>Old Datum</th>
                    <th>New Datum</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                @foreach($survey as $s)
                    <tr>
                        <td>{{$s->id_survey}}</td>
                        <td>{{$s->survey_name}}</td>
                        <td>{{$s->survey_date}}</td>
                        <td>{{$s->oldDatum->datum_name}}</td>
                        <td>{{$s->newDatum->datum_name}}</td>
                        <td>{{$s->description}}</td>
                        <td><a href="/apps/transformasi-datum/{{$s->id_survey}}" <span class="fa fa-edit tableButton" title="edit" style="cursor: pointer; font-size: 20px;"></span></td>
                    </tr>
                @endforeach
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </table>
        </div>
        <div style="text-align: right;">
            <button class="addButton" onclick="addDatum('Modal-addDatum')"><span class="fa fa-plus"></span> New Datum</button>
        </div>
        {{$survey->links()}}
        <hr>
    </div>
    <div id="Modal-addDatum" class="modal">

        <!-- Modal content -->
        <div class="modal-content" style="border-radius: 7px;">
            <div class="modal-header">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 90%; border: none; padding: 0px;"><h3 style="color: white">Add New Survey</h3></td>
                        <td style="width: 10%; border: none; padding: 0px; text-align: right;"><span class="close" onclick="cancel()">&times;</span></td>
                    </tr>
                </table>
            </div><br>
            <div class="modal-body">
                <form method="POST" action="{{ route('add-survey') }}">
                    {{ csrf_field() }}
                    <label>Survey Name</label>
                    <input type="text" name="survey_name" class="form-control" required><br>
                    <label>Survey Date</label>
                    <input type="date" name="survey_date" class="form-control" required><br>
                    <label>Old Datum</label>
                    <select name="old_datum" id="datum_lama" class="form-control" required>
                        @foreach($datum as $d)
                            <option value="{{$d->id_datum}}">{{$d->datum_name}}</option>
                        @endforeach
                    </select><br><br>
                    <label>New Datum</label>
                    <select name="new_datum" id="datum_lama" class="form-control" required>
                        @foreach($datum as $d)
                            <option value="{{$d->id_datum}}">{{$d->datum_name}}</option>
                        @endforeach
                    </select><br><br>
                    <label>description</label>
                    <input name="description" type="text" class="form-control" required><br>
                    <button type="submit" class="button-default">Tambah</button>
                    <button type="button" class="button-close" onclick="cancel()">cancel</button>
                </form><br><br>
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

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function cancel(){
            modal.style.display = "none";
        }

    </script>
@endsection

