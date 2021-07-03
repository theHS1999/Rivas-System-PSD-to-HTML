
@foreach($doctors as $doctor)
    <li id="doc{{$doctor->id}}" onclick="selectDoc({{$doctor->id}})">{{$doctor->fname}} {{$doctor->lname}} - کد نظام پزشکی : {{$doctor->medical_code}}</li>
@endforeach

