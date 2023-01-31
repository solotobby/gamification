
@extends('layouts.master')
@section('title', 'Payment Completed')

@section('content')


<!-- basic-breadcrumb start -->
<div class="basic-breadcrumb-area gray-bg ptb-70">
    <div class="container">
        <div class="basic-breadcrumb text-center">
            <h3 class="">Payment Completed</h3>
            <ol class="breadcrumb text-xs">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="active">Payment Completed</li>
            </ol>
        </div>
    </div>
</div>
<!-- basic-breadcrumb end -->

<div class="404-area ptb-120">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <div class="error-text">
                    <h1>Payment Completed</h1>
                    {{-- <h2>This Page Could Not B</h2> --}}
                    <p class="lead">
                        You have completed the payment. Please check your email for the content. Thank you for shopping with us!!!
                    </p>
                    <a href="{{ url('/home') }}" class="btn btn-lg">Back Home ›</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection