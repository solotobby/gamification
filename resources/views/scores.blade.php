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
                                    <th scope="col">Reward</th>
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
                                            <td>
                                                @if($score->reward_type == '')
                                                    No Reward Available
                                                @else
                                                    {{ $score->reward_type }}
                                                @endif
                                            </td>
                                            <td>{{ $score->score }} %</td>
                                            <td>
                                                @if($score->reward_type == null || $score->reward_type == "")
                                                <a href="#" class="btn btn-primary btn-sm disabled">No Reward Available</a>
                                                @else
                                                    @if($score->is_redeem == '0')
                                                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal-{{$score->id}}">
                                                        Redeem
                                                    </button> --}}
                                                        <a href="{{ route('redeem.reward', $score->id) }}" class="btn btn-primary btn-sm">Redeem</a>
                                                    @else
                                                        <a href="#" class="btn btn-primary btn-sm" disabled>Reward Claimed</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="exampleModal-{{ $score->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">Simple Testimonial</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                  {{ $score->game->name }}
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                  <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>

					</div>
				</div>
			</div>
	</div>




@endsection