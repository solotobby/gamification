@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <h2>{{  $userCount }}</h2>
                    <h5>Users</h5>
                </div>
                <div class="col-md-4">
                    <h2>{{  $questionCount }}</h2>
                    <h5>Questions</h5>
                </div>
                <div class="col-md-4">
                    <h2>{{  $gamesPlayed }}</h2>
                    <h5>Games Played</h5>
                </div>
            </div>
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
                            <th scope="col">Status</th>
                            <th scope="col"></th>
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
                                    <td>{{ $game->status == "1" ? 'Active' : 'Not Active' }}</td>
                                    <td><a href="{{ route('game.status', $game->id) }}" class="btn btn-primary btn-sm">Change Status</a></td>
                                    <td><a href="{{ route('view.activities', $game->id) }}" class="btn btn-info btn-sm">View Activities</a></td>
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
