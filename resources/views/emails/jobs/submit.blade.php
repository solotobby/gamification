@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding: 30px 30px 15px 30px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">Job Submitted Successfully</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Hi <strong>{{ $name }},</strong></p>
                <p style="margin-bottom: 10px;">
                    Thank you for completing <b>{{$campaign_name}}</b> at <b>&#8358;{{ $amount }}</b>, your job has been submitted successfully. 
                    
                </p>
                <p style="margin-bottom: 10px;">
                    Click the button below to access more jobs... <br>
                    <a href="{{ url('home') }}" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">Access More Jobs</a>
                </p>
                {{-- <p style="margin-bottom: 10px;">Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                <p style="margin-bottom: 15px;">Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p> --}}
                <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards <br><i>Freebyz Team</i></p>
            </td>
        </tr>
    </tbody>
</table>

@endsection