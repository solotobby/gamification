@extends('layouts.main.master')
@section('style')
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')

    <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
        <div class="bg-black-75">
            <div class="content content-boxed text-center py-5">
                <h1 class="h2 text-white mb-2">
                    Dispute on - {{ @$campaign->campaign->post_title }}
                </h1>
            </div>
        </div>
    </div>
    <div class="content content-boxed">
        <!-- Post Job form -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('dispute.decision') }}" method="POST">
            @csrf
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <h2 class="content-heading">Campaign Information - <li class="badge badge-info"> {{@$campaign->status }}
                        </li>
                    </h2>
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Detailed information about the campaign
                            </p>
                        </div>
                        <div class="col-lg-9">

                            <div class="mb-4">
                                <label class="form-label" for="post-type">Category</label>
                                <br>
                                {{ @$campaign->campaign->campaignType->name }}
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-category">Sub-Category</label>
                                <br>
                                {{ @$campaign->campaign->campaignCategory->name }}
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="post-salary-min">Number of Workers</label>
                                    <br>
                                    {{@$campaign->campaign->number_of_staff}}
                                </div>
                                 <div class="col-6">
                                    @if(auth()->user()->wallet->base_currency === "Naira" || auth()->user()->wallet->base_currency === 'NGN')
                                        <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                                    @else
                                        <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                                    @endif
                                    <br>
                                    {{$campaign->campaign->campaign_amount}}
                                </div>
                            </div>
                            <hr>
                            <h4>Estimated Cost: {{ $campaign->currency }} {{ $campaign->campaign->total_amount }} </h4>


                        </div>
                    </div>
                </div>
                <!-- END Job Meta section -->

                <!-- Files section -->
                <div class="block-content">
                    <h2 class="content-heading">Campaign Description</h2>
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Give detailed decription of the campaign
                            </p>
                        </div>
                        <div class="col-lg-9">
                            <div class="mb-4">
                                <label class="form-label" for="post-title">Title</label><br>
                                {{ @$campaign->campaign->post_title }}
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-title">External Link</label><br>
                                <a href="{{@$campaign->campaign->post_link }}" target="_blank">
                                    {{@$campaign->campaign->post_link }}</a>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-files">Campaign Description </label><br>

                                {!! @$campaign->campaign->description !!}

                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="post-files">Expected Campaign Proof
                                </label>
                                <br>
                                {{ @$campaign->campaign->proof }}
                            </div>
                            <hr>
                            <div class="mb-4">
                                <label class="form-label" for="post-files">Worker Comment
                                </label>
                                <br>
                                {!! @$campaign->comment !!}
                            </div>


                            <div class="mb-4">
                                <label class="form-label">Proof Uploaded</label><br>
                                <img src="{{ displayImage(@$campaign->proof_url) }}" class="img-thumbnail img-responsive"
                                    alt="Proof">
                            </div>


                            {{-- @if($campaign->is_dispute == true)
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Enter Reason for decline or aparroval</label>
                                    <textarea class="form-control" id="exampleInputEmail1" name="reason"
                                        required></textarea>
                                </div>
                            </div>
                            @else
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Enter Reason for decline or approval(posted)</label>
                                    <code> {!! $campaign->reason !!} </code>
                                </div>
                            </div>
                            @endif --}}
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Job Poster Comment :
                                        <b>{{ @$campaign->campaign->user->name }} </b></label>
                                    <code> {!! @$campaign->reason !!} </code>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Worker Comment: <b> {{ @$campaign->user->name }}
                                        </b></label>
                                    <p><i> {!! @$campaign->dispute->reason !!} </i></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- END Files section -->
                <input type="hidden" name="id" value="{{ @$campaign->id }}">
                <!-- Submit Form -->
                <div class="block-content block-content-full pt-0">
                    <div class="row mb-4">
                        {{-- offset-lg-5 --}}
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            @if(@$campaign->is_dispute == true)
                                @if(!$campaign->is_dispute_resolved == true)
                                    @if($campaign->campaign->is_completed == true)
                                        <div class="alert alert-success" role="alert">
                                            This campaign has recieved complete Approved responses.
                                        </div>
                                    @else
                                        <div class="mb-4">
                                            <label class="form-label" for="post-files">Admin Decision</small></label>
                                            <textarea class="form-control" name="reason" id="js-ckeditor5-classic"
                                                required> {{ old('reason') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-alt-primary" name="status" value="Approved"> <i
                                                class="fa fa-save opacity-50 me-1"></i>Approve Disputed Job</button>
                                        <button type="submit" class="btn btn-alt-danger" name="status" value="Denied"><i
                                                class="fa fa-times opacity-50 me-1"></i> Deny Job Abruptly</button>

                                    @endif
                                @else
                                    <label class="form-label" for="post-files">Admin Decision</small></label>
                                    <i> {!! @$campaign->dispute->response !!} </i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <!-- END Submit Form -->
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>
@endsection
