@extends('layouts.master')
@section('title', 'Score List')

@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Score List</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Score List</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <div class="404-area ptb-120">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
                         @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

						<div class="table-responsive">
                            <table class="table caption-top">
                                <caption>Scores</caption>
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Game</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($scores as $score)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $score->game->name }}</td>
                                        <td>{{ $score->score }} %</td>
                                        <td>
                                            @if($score->reward_type == null)
                                            <a href="" class="btn btn-primary btn-sm disabled">No Reward Available</a>
                                            @else
                                                @if($score->is_redeem == '0')
                                                    <a href="{{ route('redeem.reward', $score->id) }}" class="btn btn-primary btn-sm">Redeem</a>
                                                @else
                                                    <a href="#" class="btn btn-primary btn-sm">Reward Claimed</a>
                                                @endif
                                            @endif
                                        </td>
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