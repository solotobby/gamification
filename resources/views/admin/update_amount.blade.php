@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('View/Update Amount') }}</div>

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
                            <th scope="col">Amount</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($rewards as $reward)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $reward->name }}</td>
                                <td>&#8358; {{ $reward->amount }}</td>
                                <td><a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal_{{ $reward->id }}">Update</a></td>
                            </tr>

                            <div class="modal fade" id="exampleModal_{{ $reward->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update {{ $reward->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action={{ route('update.amount') }}>
                                            @csrf
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $reward->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Amount</label>
                                                <input type="number" class="form-control" name="amount" value="{{ $reward->amount }}" required>
                                            </div>
                                            <input type="hidden" name="id" value="{{ $reward->id }}">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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