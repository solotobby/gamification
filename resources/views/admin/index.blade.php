@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List of Games') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Winners</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($games as $game)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $game->name }}</td>
                                    <td>{{ $game->type }}</td>
                                    <td>{{ $game->number_of_winners }}</td>
                                    <td><a href="{{ url('games/create/'.$game->id) }}" class="btn btn-sm btn-primary"> Create Game</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
