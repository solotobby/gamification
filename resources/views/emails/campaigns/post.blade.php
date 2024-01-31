@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding: 30px 30px 15px 30px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">Campaign Posted</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Hi <strong>{{ $poster }},</strong></p>
                <p style="margin-bottom: 10px;">
                    Thank you for yout interest in using this amazing feature on Freebyz.com. <br>
                    This is to let you know that your campaign has been created successfully and it is under review. You will receive an update in 24hours. Below are the details:<br><br>

                    <b> Campaign ID:</b> {{ $job_id }} <br>
                    <b>Campaign Name:</b> {{ $campaign_name }} <br>
                    <b>Campaign Type:</b> {{ $type }} <br>
                    <b>Campaign Category:</b> {{ $category }} <br>
                    @if(auth()->user()->wallet->base_currency == "Naira")
                    <b>Amount per Job:</b> &#8358;{{ $amount }} <br>
                    @else
                    <b>Amount per Job:</b> ${{ $amount }} <br>
                    @endif
                    <b>Number of Worker:</b> {{ $number_of_staff }} <br>
                    <b>Total Amount:</b> &#8358;{{ $total_amount }} <br>
                </p>
                <p style="margin-bottom: 10px;">
                    Click the button below to create more Campaigns... <br><br>
                    <a href="{{ url('campaign/create') }}" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                        Create More Campaign
                    </a>
                </p>
                {{-- <p style="margin-bottom: 10px;">Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                <p style="margin-bottom: 15px;">Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p> --}}
                <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
            </td>
        </tr>
    </tbody>
</table>

@endsection

