@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Game') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action={{ route('game.store') }}>
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Number of Winners</label>
                            <input type="number" class="form-control" name="number_of_winners" placeholder="Enter Number of Winners" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select One</option>
                                <option value="INTEL">INTEL</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection