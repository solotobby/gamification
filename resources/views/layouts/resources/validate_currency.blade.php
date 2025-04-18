

  {{-- <div class="modal fade" id="modal-onboarding" tabindex="-1" data-bs-backdrop="static" 
  data-bs-keyboard="false" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0" style="background-image: url({{asset('src/assets/media/photos/photo23.jpg')}});">
        <div class="row">
          <div class="col-md-6">
            
            <div class="bg-body-extra-light shadow-lg">
              <div class="js-slider slick-dotted-inner" data-dots="true" data-arrows="false" data-infinite="false">
                <div class="p-5">
                
                 
                  
                    <h4 class="fw-normal text-muted text-center">
                        Kindly select your country to continue
                    </h4>

                 <form>
                    <div class="form-group mb-3">
                      {{-- <label for="exampleInputEmail1">Select Country</label> 
                      <select name="currency" class="form-control @error('method') is-invalid @enderror" required>
                        <option value="">Select Country</option>
                        <option value="GHS">Ghana</option>
                        <option value="RWF">Rwanda</option>
                        <option value="KES">Kenya</option>
                        <option value="USD">Other</option>
                      </select>
                    </div>
                   
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                  

                  
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</div> --}}



<div class="modal fade" id="modal-onboarding" tabindex="-1" data-bs-backdrop="static" 
  data-bs-keyboard="false" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Country</h5>
          
        </div>
        <div class="modal-body">
            <h4 class="fw-normal text-muted text-center">
                Kindly select your Country of residence
            </h4>
            <div class="alert alert-warning"> This will enable us pay you in your local currency.</div>

          <form action="{{ route('continue.country') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <select name="currency" class="form-control @error('method') is-invalid @enderror" required>
                    <option value="">Select Country</option>
                    @foreach (currencyList(null,true) as $currency)
                      <option value="{{$currency->code}},{{$currency->country}}">{{$currency->country}}</option>
                    @endforeach
                    
                    
                    <option value="USD,Other">Others</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Continue</button>
         </form>

        </div>
       
      </div>
    </div>
  </div>