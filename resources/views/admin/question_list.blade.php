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
                    
                    @foreach ($questions as $question)
                    <div class="col-md-12">
                        <h5>{{ $question->content }}</h5>
                        

                        <li>Option A - {{ $question->option_A }}</li><br>
                        <li>Option B - {{ $question->option_B }}</li><br>
                        <li>Option C - {{ $question->option_C }}</li><br>
                        <li>Option D - {{ $question->option_D }}</li><br>
                        
                    </div>
                    <hr>
                    @endforeach
                    
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
