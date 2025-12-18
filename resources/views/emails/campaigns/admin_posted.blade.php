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
                        A new campaign has just been posted on Freebyz. Below are the details:<br><br>

                        <b>Campaign ID:</b> {{ $job_id }} <br>
                        <b>Campaign Name:</b> {{ $campaign_name }} <br>
                        <b>Campaign Type:</b> {{ $type }} <br>
                        <b>Campaign Category:</b> {{ $category }} <br>
                        <b>Amount per Job:</b> &#8358;{{ $amount }} <br>
                        <b>Number of Worker:</b> {{ $number_of_staff }} <br>
                        <b>Total Amount:</b> &#8358;{{ $total_amount }} <br>
                    </p>

                    <b>Posted By:</b> <br>
                    <b>Name:</b> {{ $poster }} <br>
                    <b>Email:</b> {{ $poster_email }} <br> <br>

                    <p style="margin-bottom: 10px;">
                       <br><br>
                        <a href="{{ url('campaign/info/' . $id) }}" target="_blank"
                            style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                            Review Campaign
                        </a>
                    </p>

                     <p style="margin-top: 20px; margin-bottom: 20px;">
                        Get Real Time Updates on Fresh Jobs and Tasks on our channel
                        <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                            style="color: #25D366; font-weight: 600;">Join WhatsApp Channel</a>
                    </p>
                    
                    <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
