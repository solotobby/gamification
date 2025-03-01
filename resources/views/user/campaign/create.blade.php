@extends('layouts.main.master')

@section('title', 'Create Campaign')

@section('style')
{{-- <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origind"></script> --}}
<script src="https://cdn.tiny.cloud/1/no-api/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
@endsection

@section('content')
 <!-- Hero Section -->
 <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
            Create Campaign
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->

  <!-- Page Content -->
  <div class="content content-boxed">
    <!-- Post Job form -->
    {{-- <h2 class="content-heading">
      <i class="fa fa-plus text-success me-1"></i> Create Campaign
    </h2> --}}
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

    <form action="{{ route('post.campaign') }}" method="POST">
        @csrf
      <div class="block block-rounded">
        <div class="block-content block-content-full">
          <h2 class="content-heading">Campaign Information</h2>
          <div class="row items-push">
            <div class="col-lg-3">
              <p class="text-muted">
              Please provide detailed information about your campaign
              </p>
            </div>
            <div class="col-lg-9">
              <div class="alert alert-warning">
                Note: Social media Apps like Facebook, TikTok, YouTube, Instagram has algorithms to 
                detect unusual behaviour  and attempt to buy followers or subscribers which can lead 
                to a 10-15% drop in the number of followers/subscribers you actually hired. 
                This may make you think our workers actually unsubcribed/unfollowed your page. Therefore 
                avoid using your direct links (as much as possible). You can also choose the Comment 
                before subscribe/Follow Subcategory or other creative means.
              </div>
              <div class="mb-4">
                <label class="form-label" for="post-type">Category</label>
                <select class="js-select2 form-select" id="post-type" name="campaign_type" style="width: 100%;" data-placeholder="Choose type.." required>
                    <option value="">Select Category</option>
                </select>
              </div>

              <div class="mb-4">
                <label class="form-label" for="post-category">Sub-Category</label>
                <select class="js-select2 form-select" id="post-category" name="campaign_subcategory" style="width: 100%;" data-placeholder="Choose category.." required>
                    <option value="">Select Sub-Category</option>
                </select>
              </div>

              <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="post-salary-min">Number of Workers</label>
                  <input type="number" class="form-control" id="number-of-staff" name="number_of_staff" min="15" value="15" required>
                </div>
                <div class="col-6">

                  @if(auth()->user()->wallet->base_currency == "NGN")
                      <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                    @else
                      <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                  @endif


                    <input type="text" class="form-control" id="amount_per_campaign" name="campaign_amount" value="" readonly>
                </div>
              </div>
              <hr>
              @if(auth()->user()->wallet->base_currency == "NGN")
                <h4>Estimated Cost: &#8358;<span id="demo"></span></h4>
              @elseif(auth()->user()->wallet->base_currency == 'GHS')
                <h4>Estimated Cost: &#8373;<span id="demo"></span></h4>
                 
              @elseif(auth()->user()->wallet->base_currency == 'KES')
                  <h4>Estimated Cost: KES <span id="demo"></span></h4>
                  
              @elseif(auth()->user()->wallet->base_currency == 'TZS')
                <h4>Estimated Cost: TZS <span id="demo"></span></h4>
              @elseif(auth()->user()->wallet->base_currency == 'RWF')
                <h4>Estimated Cost: RWF <span id="demo"></span></h4>
              @elseif(auth()->user()->wallet->base_currency == 'MWK')
                <h4>Estimated Cost:  MWK <span id="demo"></span></h4>
              @elseif(auth()->user()->wallet->base_currency == 'UGX')
                <h4>Estimated Cost:  UGX <span id="demo"></span></h4>
              @elseif(auth()->user()->wallet->base_currency == 'ZAR')
                <h4>Estimated Cost: ZAR <span id="demo"></span></h4>
              @else 
                <h4>Estimated Cost: $<span id="demo"></span></h4>
              @endif

{{--          @if(auth()->user()->wallet->base_currency == "Naira" || auth()->user()->wallet->base_currency == "NGN")
              <h4>Estimated Cost: &#8358;<span id="demo"></span></h4>
              @else
              <h4>Estimated Cost: $<span id="demo"></span></h4>
              @endif --}}
              
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
                <label class="form-label" for="post-title">Title</label>
                <input type="text" class="form-control" id="post-title" name="post_title" value="{{ old('post_title') }}" required>
                <small><i>Please give a simple campaign title e.g Facebook Like or Youtube comment</i></small>
              </div>

            <div class="mb-4">
                <label class="form-label" for="post-title">External Link</label>
                <input type="url" class="form-control" id="post-title" name="post_link" value="{{ old('post_link') }}" required>
                <small><i>Please provide an external link for your campaign e.g https://myhotjobz.com or https://youtube.com/abc</i></small>
            </div>

            <div class="mb-4">
                <label class="form-label" for="post-files">Campaign Description <small>(Ensure you provide simple and clear instruction on task to be done)</small></label>
                <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {{ old('description') }}</textarea>
            </div>
            <div class="mb-2">
                <label class="form-label" for="post-files">Expected Campaign Proof <small>(You can request for social 
                    media handle, email or other means of identifying the worker)</small></label>
                    {{-- <iframe name="server_answer" style="display:none"></iframe> --}}
                <textarea id="mytextareao" class="form-control" name="proof" required>{{ old('proof') }}</textarea>
            </div>
            <div class="mb-2">
              <input type="checkbox" name="allow_upload" value="1" class="">
             
              <span><small> Allow image to be uploaded with proof at a cost of <b> {{ baseCurrency() }} {{  currencyParameter( baseCurrency() )->allow_upload }} </b> per worker </small></span>
              
            </div>
            <div class="mb-2">
              <input type="checkbox" name="priotize" value="1" class="">
             
              <span><small> Make your Campaign appear at the top for <b> {{ baseCurrency() }} {{  currencyParameter( baseCurrency() )->priotize }}  </b> (Optional) </small></span>
             
            </div>

            <div class="mb-2">
                <input type="checkbox" name="validate" required class="">
                <span><small> I agree that this campaign will be automatically approved after 24 hours If I fail to approve it. </small></span>
            </div>
            
            </div>
          </div>
        </div>
        <!-- END Files section -->

        <!-- Submit Form -->
        <div class="block-content block-content-full pt-0">
          <div class="row mb-2">
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
              <button type="submit" class="btn btn-alt-primary">
                <i class="fa fa-plus opacity-50 me-1"></i> Post Campaign
              </button>
            </div>
          </div>
        </div>
        <!-- END Submit Form -->
      </div>
    </form>
    <!-- END Post Job Form -->
  </div>
  <!-- END Page Content -->

@endsection

@section('script')

 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
 {{-- <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>

 <script>
    $(document).ready(function(){
            $.ajax({
                    url: '{{ url("api/get/categories") }}',
                    type: "GET",
                    data: {
                        //  country_id: country_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    // context: document.body,
                    success: function(result) {
                        // console.log(result);
                        $('#post-type').html('<option value="">Select Type</option>');
                        $.each(result, function(key, value) {
                            $("#post-type").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });

                $('#post-type').change(function(){
                    var typeID = this.value;

                    $("#post-category").html('');
                        $.ajax({
                            url: '{{ url("api/get/sub/categories") }}/' + encodeURI(typeID),
                            type: "GET",
                            data: {
                                //  country_id: country_id,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function(result) {
                                var new_result = result.sort(function(a, b) {
                                    return a.name.localeCompare(b.name);
                                });

                                console.log(result)

                                $('#post-category').html('<option value="">Select Category</option>');
                                $.each(new_result, function(key, value) {
                                    document.getElementById("number-of-staff").value = value.amount;
                                    $("#post-category").append('<option value="' + value.id + '">' + value.name + '</option>');  
                                });
                                // $('#city-dropdown').html('<option value="">Select Region/State First</option>');
                            }
                    });
                });

                $('#post-category').change(function(){
                    var categoryID = this.value;
                    // api/get/sub/categories/info/{id}
                    $.ajax({
                        // url: ' url("api/get/sub/categories/info") }}/' + encodeURI(typeID))',
                        url: '{{ url("api/get/sub/categories/info") }}/' + encodeURI(categoryID),
                        type: "GET",
                        data: {
                            //  country_id: country_id,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function(result) {
                          
                            var amount = result.amount;
                            var num_staff = document.getElementById("number-of-staff").value; //amount_per_campaign
                            // var amount_per_campaign = document.getElementById("amount_per_campaign").result.amount;
                             var total_amount = Number(amount) * Number(num_staff);
                            
                            document.getElementById("amount_per_campaign").value = result.amount;

                            var percentToGet = 60;
                            var percent = (percentToGet / 100) * total_amount;

                            document.getElementById("demo").innerHTML = total_amount + percent;
                        }
                    });
                });

                $('#number-of-staff').change(function(){
                    var y = document.getElementById("number-of-staff").value;
                    var z = document.getElementById("amount_per_campaign").value;
                    var x = Number(y) * Number(z);
                    // document.getElementById("demo").innerHTML = x;
                    var percentToGet = 60;
                    var percent = (percentToGet / 100) * x;

                    document.getElementById("demo").innerHTML = x + percent;
                    // alert(x);
                });
    });
 </script>
@endsection