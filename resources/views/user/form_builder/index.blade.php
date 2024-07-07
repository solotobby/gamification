@extends('layouts.main.master')


@section('content')
<div class="content content-boxed">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
          <h2 class="content-heading">Preview Survey Form</h2>
          <div class="row items-push">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-9">
                {!! form($form) !!}
            </div>
          </div>
        </div>
    </div>
</div>

@endsection