
    @foreach($insureds as $insured)
        <li id="insured{{$insured->id}}" onclick="selectInsured({{$insured->id}})">{{$insured->fname}} {{$insured->lname}} - کد ملی: {{$insured->melli_code}}</li>
    @endforeach

