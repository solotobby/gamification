@extends('layouts.main.master')

@section('title', 'Create Campaign')

@section('style')
    <script src="https://cdn.tiny.cloud/1/no-api/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>
    <style>
        .preview-container {
            margin-top: 10px;
            max-width: 300px;
        }
        .preview-container img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .remove-preview {
            margin-top: 5px;
            cursor: pointer;
        }
    </style>
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

        <form id="campaignForm" method="POST" action="{{ route('post.campaign') }}" enctype="multipart/form-data">
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
                                detect unusual behaviour and attempt to buy followers or subscribers which can lead
                                to a 10-15% drop in the number of followers/subscribers you actually hired.
                                This may make you think our workers actually unsubcribed/unfollowed your page. Therefore
                                avoid using your direct links (as much as possible). You can also choose the Comment
                                before subscribe/Follow Subcategory or other creative means.
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="post-type">Category</label>
                                <select class="js-select2 form-select" id="post-type" name="campaign_type"
                                    style="width: 100%;" data-placeholder="Choose type.." required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-category">Sub-Category</label>
                                <select class="js-select2 form-select" id="post-category" name="campaign_subcategory"
                                    style="width: 100%;" data-placeholder="Choose category.." required>
                                    <option value="">Select Sub-Category</option>
                                </select>
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="post-salary-min">Number of Workers</label>
                                    <input type="number" class="form-control" id="number-of-staff" name="number_of_staff"
                                        min="15" value="15" required>
                                </div>
                                <div class="col-6">
                                    @if(auth()->user()->wallet->base_currency == "NGN")
                                        <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                                    @else
                                        <label class="form-label" for="post-salary-min">Cost per Campaign</label>
                                    @endif

                                    <input type="text" class="form-control" id="amount_per_campaign" name="campaign_amount"
                                        value="" readonly>
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
                                <h4>Estimated Cost: MWK <span id="demo"></span></h4>
                            @elseif(auth()->user()->wallet->base_currency == 'UGX')
                                <h4>Estimated Cost: UGX <span id="demo"></span></h4>
                            @elseif(auth()->user()->wallet->base_currency == 'ZAR')
                                <h4>Estimated Cost: ZAR <span id="demo"></span></h4>
                            @else
                                <h4>Estimated Cost: $<span id="demo"></span></h4>
                            @endif
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
                                <input type="text" class="form-control" id="post-title" name="post_title"
                                    value="{{ old('post_title') }}" required>
                                <small><i>Please give a simple campaign title e.g Facebook Like or Youtube
                                        comment</i></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-title">External Link</label>
                                <input type="url" class="form-control" id="post-title" name="post_link"
                                    value="{{ old('post_link') }}" required>
                                <small><i>Please provide an external link for your campaign e.g https://myhotjobz.com or
                                        https://youtube.com/abc</i></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-files">Campaign Description <small>(Ensure you provide
                                        simple and clear instruction on task to be done)</small></label>
                                <textarea class="form-control" name="description" id="js-ckeditor5-classic"
                                    required> {{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="post-files">Expected Campaign Proof <small>(You can request
                                        for social media handle, email or other means of identifying the worker)</small></label>
                                <textarea id="mytextareao" class="form-control" name="proof"
                                    required>{{ old('proof') }}</textarea>
                            </div>

                            <!-- Expected Result Image Upload -->
                            <div class="mb-4">
                                <label class="form-label" for="expected-result-image">
                                    Expected Result Image <span class="badge bg-info">Optional</span>
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="expected-result-image"
                                       name="expected_result_image"
                                       accept="image/png,image/jpeg,image/jpg,image/gif"
                                       onchange="previewImage(event)">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Upload an example image showing what the expected result should look like. This helps workers understand exactly what you want. (Max 2MB, PNG, JPEG, JPG, GIF)
                                </small>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="preview-container" style="display: none;">
                                    <img id="preview" src="" alt="Preview">
                                    <button type="button" class="btn btn-sm btn-danger remove-preview" onclick="removePreview()">
                                        <i class="fa fa-times"></i> Remove Image
                                    </button>
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="checkbox" name="allow_upload" value="1" class="">
                                <span><small> Allow image to be uploaded with proof at a cost of <b> {{ baseCurrency() }}
                                            {{  currencyParameter(baseCurrency())->allow_upload }} </b> per worker
                                    </small></span>
                            </div>

                            <div class="mb-2">
                                <input type="checkbox" name="priotize" value="1" class="">
                                <span><small> Make your Campaign appear at the top for <b> {{ baseCurrency() }}
                                            {{  currencyParameter(baseCurrency())->priotize }} </b> (Optional)
                                    </small></span>
                            </div>

                            @if(auth()->user()->is_business)
                                <div class="mb-4">
                                    <label class="form-label" for="approval-time">Auto-Approval Time</label>
                                    <select class="form-select" id="approval-time" name="approval_time" required>
                                        <option value="24" selected>24 Hours</option>
                                        <option value="36">36 Hours</option>
                                        <option value="48">48 Hours</option>
                                        <option value="56">56 Hours</option>
                                        <option value="72">72 Hours</option>
                                    </select>
                                    <small><i>Select when your campaign submissions should be automatically approved if you
                                            don't review them.</i></small>
                                </div>
                            @else
                                <div class="mb-2">
                                    <input type="checkbox" name="validate" required class="">
                                    <span><small> I agree that this campaign will be automatically approved after 24 hours If I
                                            fail to approve it. </small></span>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <!-- END Files section -->

                <!-- Submit Form -->
                <div class="block-content block-content-full pt-0">
                    <div class="row mb-2">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <button type="button" class="btn btn-alt-primary" data-bs-toggle="modal"
                                data-bs-target="#fairnessModal">
                                <i class="fa fa-plus opacity-50 me-1"></i> Post Campaign
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END Submit Form -->
            </div>
        </form>

        <!-- Fairness Warning Modal -->
        <div class="modal fade" id="fairnessModal" tabindex="-1" aria-labelledby="fairnessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="fairnessModalLabel">
                            <i class="fa fa-exclamation-triangle me-2"></i>Important Notice
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                       To ensure fairness, kindly avoid denying task submissions without genuine reasons.
                       Repeated denials will lead to your campaigns being suspended without refund.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" id="agreeSubmitBtn" class="btn btn-primary">
                            Agree & Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Post Job Form -->
    </div>
    <!-- END Page Content -->

@endsection

@section('script')

    <script>
        // Image Preview Function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                // Check file size (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('File size exceeds 2MB. Please choose a smaller file.');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove Preview Function
        function removePreview() {
            document.getElementById('expected-result-image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('preview').src = '';
        }

        // Form Submit Handler
        document.getElementById('agreeSubmitBtn').addEventListener('click', function () {
            document.getElementById('campaignForm').submit();
        });
    </script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Page JS Helpers (CKEditor 5 plugins) -->
    <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>

    <script>
        $(document).ready(function () {
            // Pass the business status from Laravel to JavaScript
            var isBusiness = {{ auth()->user()->is_business ? 'true' : 'false' }};

            $.ajax({
                url: '{{ url("api/get/categories") }}',
                type: "GET",
                data: {
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#post-type').html('<option value="">Select Type</option>');
                    $.each(result, function (key, value) {
                        $("#post-type").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

            $('#post-type').change(function () {
                var typeID = this.value;

                $("#post-category").html('');
                $.ajax({
                    url: '{{ url("api/get/sub/categories") }}/' + encodeURI(typeID),
                    type: "GET",
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var new_result = result.sort(function (a, b) {
                            return a.name.localeCompare(b.name);
                        });

                        console.log(result)

                        $('#post-category').html('<option value="">Select Category</option>');
                        $.each(new_result, function (key, value) {
                            document.getElementById("number-of-staff").value = value.amount;
                            $("#post-category").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });

            $('#post-category').change(function () {
                var categoryID = this.value;
                $.ajax({
                    url: '{{ url("api/get/sub/categories/info") }}/' + encodeURI(categoryID),
                    type: "GET",
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {

                        var amount = result.amount;
                        var num_staff = document.getElementById("number-of-staff").value;
                        var total_amount = Number(amount) * Number(num_staff);

                        document.getElementById("amount_per_campaign").value = result.amount;

                        // Set percentToGet based on business status
                        var percentToGet = isBusiness ? 100 : 60;
                        var percent = (percentToGet / 100) * total_amount;

                        document.getElementById("demo").innerHTML = total_amount + percent;
                    }
                });
            });

            $('#number-of-staff').change(function () {
                var y = document.getElementById("number-of-staff").value;
                var z = document.getElementById("amount_per_campaign").value;
                var x = Number(y) * Number(z);

                // Set percentToGet based on business status
                var percentToGet = isBusiness ? 100 : 60;
                var percent = (percentToGet / 100) * x;

                document.getElementById("demo").innerHTML = x + percent;
            });
        });
    </script>
@endsection
