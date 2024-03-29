@extends('layouts.main.master')
@section('style')
<script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
@endsection

@section('content')


<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Campaigns</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active" aria-current="page">View Activities</li>
          </ol>
        </nav>
      </div>
     </div>
    </div>


  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">{{ $lists->post_title }} campaign: Pending - {{ $lists->where('status', 'pending')->count() }} 
          | Approved - {{ @$lists->completed()->where('status', 'Approved')->count() }} 
          | Denied - {{ @$lists->completed()->where('status', 'Denied')->count() }} 
          @if($lists->currency == 'NGN')
          | Amount Spent -   &#8358;{{ number_format(@$lists->completed()->where('status', 'Approved')->count() * $lists->campaign_amount) }}/&#8358;{{ number_format($lists->campaign_amount * $lists->number_of_staff) }}
          @else
          | Amount Spent -   ${{ number_format(@$lists->completed()->where('status', 'Approved')->count() * $lists->campaign_amount,2) }}/${{ number_format($lists->campaign_amount * $lists->number_of_staff, 2) }}
          @endif
        </h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
          <form action="{{ url('mass/approval') }}" method="POST">
            @csrf 
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Worker Name</th>
                <th>Campaign Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                
            @if(auth()->user()->hasRole('admin'))

                  @foreach ($lists->completed()->orderBy('created_at', 'DESC')->where('status', 'Pending')->take($count)->get() as $list)
                      <tr>
                        <th scope="row"><input type="checkbox" name="id[]" value="{{ $list->id }}"></th>
                        <td>
                            {{ @$list->user->name }}
                            </td>
                        <td>
                        {{ @$list->campaign->post_title }}
                        </td>
                        <td>
                          @if($list->campaign->currency == 'NGN')
                            &#8358;{{ $list->amount }}
                            @else
                            ${{ $list->amount }}
                          @endif
                        </td>
                        <td>{{ $list->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() }}</td>
                        
                    </tr>
                  @endforeach

            @else

                  @foreach ($lists->completed()->orderBy('created_at', 'DESC')->get() as $list)
                      <tr>
                          <td>
                              {{ @$list->user->name }}
                              </td>
                          <td>
                          {{ @$list->campaign->post_title }}
                          </td>
                          <td>
                            @if($list->campaign->currency == 'NGN')
                              &#8358;{{ $list->amount }}
                              @else
                              ${{ $list->amount }}
                              @endif
                          </td>
                          <td>{{ $list->status }}</td>
                          <td>
                              @if($list->status == 'Pending')
                              
                                  @if($lists->completed()->where('status', 'Approved')->count() >= @$list->campaign->number_of_staff)
                                      <button type="button" class="btn btn-alt-warning btn-sm disabled">Worker Completed</button>
                                  @else
                                      <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $list->id }}">View</button>
                                  @endif

                                @else
                                  <a href="#" class="btn btn-alt-info btn-sm disabled">Completed</a>
                              @endif
                          </td>
                      </tr>

                      <!-- Pop Out Default Modal -->
                      <div class="modal fade" id="modal-default-popout-{{ $list->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Respond to <i>{{$list->campaign->post_title}}</i> </h5> |  &#8358; {{ $list->amount }}
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body pb-1">
                                    {!! $list->comment !!}

                                    @if($list->proof_url != null)
                                      <hr>
                                      <h5>Proof of work Image</h5>
                                      <img src="{{ $list->proof_url }}" class="img-thumbnail rounded float-left " alt="Proof">
                                      @else
                                      <div class="alert alert-warning text-small">
                                        No Image attached
                                      </div>
                                    @endif
                                    <hr>
                                    <form action="{{ route('campaign.decision') }}" method="POST">
                                      @csrf
                                      <div class="mb-4">
                                        <label class="form-label" for="post-files">Reason</small></label>
                                            <textarea class="form-control" name="reason" id="js-ckeditor5-classic" required> {{ old('reason') }}</textarea>
                                      </div>
                                      <input type="hidden" name="id" value="{{ $list->id }}">
                                      <div class="mb-4">
                                        <button type="submit" name="action" value="approve" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>
                                        <button type="submit" name="action" value="deny" class="btn btn-danger"><i class="fa fa-times"></i> Deny</button>
                                      </div>
                                    </form>
                                    <br>
                                </div>
                                
                                <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                                {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                                </div>
                            </div>
                          </div>
                      </div>
                    

                  @endforeach

            @endif
            </tbody>
            
          </table>
            @if(auth()->user()->hasRole('admin'))
                <button class="btn btn-primary mb-2" type="submit">Approve All</button>
              @endif
        </form>
        </div>
      </div>
    </div>
    <!-- END Full Table -->
  </div>


@endsection