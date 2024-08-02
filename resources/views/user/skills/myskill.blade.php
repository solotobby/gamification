@extends('layouts.main.master')

@section('content')

 <!-- Hero -->
 <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
    <div class="bg-black-50">
      <div class="content content-full">
        <h1 class="fs-2 text-white my-2">
        @if($skill)
          <i class="fa fa-plus text-white-50 me-1"></i>{{ $skill->title }}
          @else
          <i class="fa fa-plus text-white-50 me-1"></i>Setup Skill
          @endif
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero -->
 
  <!-- Page Content -->
  <div class="content">
    @if($skill)
    <div class="block block-rounded block-bordered">
      <div class="block-content">
        <!-- Vital Info -->
        <h2 class="content-heading pt-0">Vital Info</h2>
        <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Some vital information about your new project
              </p>
            </div>
          
            <div class="col-lg-8">
                <div class="mb-4">
                    <label class="form-label" for="dm-project-new-name">
                      Title 
                    </label><br>
                    {{ $skill->title }}
                </div>
                <div class="mb-4">
                    <label class="form-label" for="dm-project-new-name">
                     Brief Description
                    </label><br>
                    {!! $skill->description !!}
                </div>
                <div class="mb-4">
                    <label class="form-label" for="dm-project-new-name">
                    Price Range
                    </label><br>
                    From {!! $skill->min_price !!} to  {!! $skill->max_price !!} 
                </div>
            </div>
            <h2 class="content-heading pt-0">Portfolio</h2>
           

            <div class="row push">
                <div class="col-lg-4">
                  <p class="text-muted">
                   Your portfolio
                  </p>
                </div>
              
                <div class="col-lg-8">
                    @if(count($skill->portfolios) > 0)
                        @foreach ($skill->portfolios as $portfolio)
                            <div class="mb-4">
                                <label class="form-label" for="dm-project-new-name">
                                Title
                                </label><br>
                                 {!! $portfolio->title !!} 
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="dm-project-new-name">
                                Desccription
                                </label><br>
                                 {!! $portfolio->description !!} 
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="dm-project-new-description">Skills</label>
                               
                               <p> @foreach ($portfolio->skills as $key=>$value)
                                    {{ $value->name }},
                                @endforeach  
                               </p>  
                            </div>
                            <hr>
                        @endforeach
                   
                    @else
                    <div class="alert alert-warning">
                        You have not added portfolio
                    </div>
                    @endif

                </div>
            </div>
            

        </div>

      </div>
    </div>
    @else
  
        <div class="alert alert-info">
            You have not created a skill, follow the link below to create one!
        </div>
            
    @endif
  </div>

  




@endsection