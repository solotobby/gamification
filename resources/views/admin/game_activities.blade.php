@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> {{ $game->name }} {{ __('players') }} - {{ $activities->count() }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('assign.reward') }}" method="POST">
                        @csrf
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Score</th>
                                <th scope="col">Reward Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($activities as $act)
                                    <tr>
                                        @if($act->reward_type == '')
                                        <th scope="row"><input type="checkbox" name="id[]" value="{{ $act->id }}"></th>
                                        @else
                                          <th scope="row"><input type="checkbox" name="id[]" value="{{ $act->id }}" checked disabled></th>  
                                        @endif
                                        <td>{{ $act->user->name }}</td>
                                        <td>{{ @$act->user->phone }}</td>
                                        <td>{{ $act->reward_type }}</td>
                                        <td>{{ $act->score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <select class="form-control" name="name">
                            <option name="CASH">CASH</option>
                            {{-- <option name="AIRTIME">AIRTIME</option>
                            <option name="DATA">DATA</option> --}}
                        </select>
                        <br>
                        <input type="hidden" name="score_id" value="{{ $act->id }}">
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
