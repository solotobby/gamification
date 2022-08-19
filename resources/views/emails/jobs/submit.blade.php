@component('mail::message')
# Job Submitted successfully!

Hi {{ $name }}, <br>

Thank you for completing <b>{{$campaign_name}}</b> at <b>&#8358;{{ $amount }}</b>, your job has been submitted successfully. 
<br>
Click the button below to access more jobs...

@component('mail::button', ['url' => url('home')])
Access More Jobs
@endcomponent

Thanks,<br>
<b>Freebyz.com Team</b>
{{-- {{ config('app.name') }} --}}
@endcomponent
