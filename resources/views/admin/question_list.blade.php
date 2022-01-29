@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List of Games') }} - {{ $question_count }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <?php $i = 1; ?>

                    @foreach ($questions as $question)
                    <div class="col-md-12">
                        
                        <h5>{{ $i++ }} - {{ $question->content }}</h5>
                        <li>Option A - {{ $question->option_A }}</li><br>
                        <li>Option B - {{ $question->option_B }}</li><br>
                        <li>Option C - {{ $question->option_C }}</li><br>
                        <li>Option D - {{ $question->option_D }}</li><br>
                    </div>

                    {{-- <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModalCenter_{{ $question->id }}">Edit Question</button> --}}

                    <hr>

                    <div class="modal fade" id="exampleModalCenter_{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Question</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action={{ route('questions.update') }}>
                                    @csrf
                                    
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Question</label>
                                        <textarea class="form-control" name="content" required>{{ $question->content }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Option A</label>
                                        <input type="text" class="form-control" name="option_A" placeholder="Option A"  value="{{ $question->option_A }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Option B</label>
                                        <input type="text" class="form-control" name="option_B" placeholder="Option B" value="{{ $question->option_B }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Option C</label>
                                        <input type="text" class="form-control" name="option_C" placeholder="Option C" value="{{ $question->option_C }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Option D</label>
                                        <input type="text" class="form-control" name="option_D" placeholder="Option D" value="{{ $question->option_D }}" required>
                                    </div>

                                    <input type="hidden" name="id" value="{{ $question->id }}">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Correct Answer</label>
                                        <select name="correct_answer" class="form-control" required>
                                            <option value="option_A">Option A</option>
                                            <option value="option_B">Option B</option>
                                            <option value="option_C">Option C</option>
                                            <option value="option_D">Option D</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button> --}}
                            </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                
                </div>
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
