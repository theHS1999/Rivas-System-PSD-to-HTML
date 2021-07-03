
@foreach($medicines as $medicine)
    <li id="med_r{{$medicine->id}}" onclick="selectMed({{$medicine->id}} , {{$id}})">{{$medicine->name}}</li>
@endforeach

