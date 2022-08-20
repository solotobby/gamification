@component('mail::message')
# {{$subject}}

Hi {{ $name }},

The job you <b>{{ $campaign }}</b> submitted have been <b>{{ $status }}</b> at <b>&#8358;{{ $amount }}</b>. 

@component('mail::button', ['url' => url('home')])
Take More Jobs
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
