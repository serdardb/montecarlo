@extends('app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>{{ $league->name }}</h2>
            <h3>Fikstür</h3>
            @foreach($weekends as $key => $week)
                <div class="row">
                    <div class="col-12">
                        <h3>{{ $key }} - Hafta</h3>
                    </div>
                </div>
                    @foreach($week as $match)
                        <div class="row">
                            <div class="col-5">{{ $match['home']['club']['name'] }}</div>
                            <div class="col-2">
                                <strong>{{ $match['home']['score'] }}</strong> - <strong>{{ $match['away']['score'] }}</strong>
                            </div>
                            <div class="col-5">{{ $match['away']['club']['name'] }}</div>
                            <hr />
                        </div>
                    <hr />
                    @endforeach
                <a href="{!! route('league.run',[$league->id,$key]) !!}" class="btn btn-success">Oyna</a>
                <hr />
            @endforeach
        </div>
    </div>
@endsection
