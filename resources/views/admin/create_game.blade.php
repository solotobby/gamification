@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Question</label>
                            <textarea type="password" class="form-control" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Option A</label>
                            <input type="text" class="form-control" name="option_A" placeholder="Option A" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Option B</label>
                            <input type="text" class="form-control" name="option_B" placeholder="Option B" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Option C</label>
                            <input type="text" class="form-control" name="option_C" placeholder="Option C" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Option D</label>
                            <input type="text" class="form-control" name="option_D" placeholder="Option D" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Correct Answer</label>
                            <select name="correct_answer" required>
                                <option value="option_A">Option A</option>
                                <option value="option_B">Option B</option>
                                <option value="option_C">Option C</option>
                                <option value="option_D">Option D</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection