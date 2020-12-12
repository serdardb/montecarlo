@extends('app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Ligler</h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">İsim</th>
                    <th scope="col">Takımlar</th>
                    <th scope="col">İşlem</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leagues as $key => $league)

                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{!! $league->name !!}</td>
                        <td>{!! $league->clubs->pluck('name')->join(', ') !!}</td>
                        <td>
                            <a href="{!! route('league.show',$league->id) !!}" class="btn btn-sm btn-info">Göster</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
