<b>Data In Use</b><br>
<div class="table">
    <table>
        <tr>
            <th>Point Name</th>
            <th>X1</th>
            <th>Y1</th>
            <th>Z1</th>
            <th>X2</th>
            <th>Y2</th>
            <th>Z2</th>
            <th></th>
        </tr>
        @foreach($points as $p)
            @if($p->is_used == 1)
                @if($p->bursawolf_passing_status == 0)
                    <tr>
                @elseif($p->bursawolf_passing_status == 1 && $p->molobas_passing_status == 1)
                    <tr style="background-color: #c2ffce">
                @else
                    <tr style="background-color: #ffcac7">
                @endif
                    <td>{{$p->point_name}}</td>
                    <td>{{$p->X1}}</td>
                    <td>{{$p->Y1}}</td>
                    <td>{{$p->Z1}}</td>
                    <td>{{$p->X2}}</td>
                    <td>{{$p->Y2}}</td>
                    <td>{{$p->Z2}}</td>
                    <td><button type="button" onclick="setStash('{{$p->id_point}}')" class="button-close">throw</button></td>
                </tr>
            @endif
        @endforeach
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    </table>
</div>
<br>
<b>Data In Stash</b><br>
<div class="table">
    <table>
        <tr>
            <th>Point Name</th>
            <th>X1</th>
            <th>Y1</th>
            <th>Z1</th>
            <th>X2</th>
            <th>Y2</th>
            <th>Z2</th>
            <th></th>
        </tr>
        @foreach($points as $p)
            @if($p->is_used == 0)
                @if($p->bursawolf_passing_status == 0)
                    <tr>
                @elseif($p->bursawolf_passing_status == 1 && $p->molobas_passing_status == 1)
                    <tr style="background-color: #c2ffce">
                @else
                    <tr style="background-color: #ffcac7">
                @endif
                    <td>{{$p->point_name}}</td>
                    <td>{{$p->X1}}</td>
                    <td>{{$p->Y1}}</td>
                    <td>{{$p->Z1}}</td>
                    <td>{{$p->X2}}</td>
                    <td>{{$p->Y2}}</td>
                    <td>{{$p->Z2}}</td>
                    <td><button type="button" onclick="reuse('{{$p->id_point}}')" class="button-default">reuse</button></td>
                </tr>
            @endif
        @endforeach
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    </table>
</div>
