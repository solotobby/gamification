@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding: 30px 30px 15px 30px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">{{$subject}}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Hi <strong>{{ $name }},</strong></p>
                <p style="margin-bottom: 10px;">
                   <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job ID</th>
                            <th>Campaign Amount</th>
                            <th>Post Title</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Is Completed</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->job_id }}</td>
                                <td>{{ $job->campaign_amount }}</td>
                                <td>{{ $job->post_title }}</td>
                                <td>{{ $job->type }}</td>
                                <td>{{ $job->category }}</td>
                                <td>{{ $job->is_completed ? 'Yes' : 'No' }}</td>
                                <td>{{ $job->currency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                   
                <br>
            </p>


                {{-- <p style="margin-bottom: 10px;">
                    Click the button below to access more jobs... <br><br>
                    <a href="{{ url($url) }}" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                        Take More Jobs
                    </a>
                </p> --}}
                {{-- <p style="margin-bottom: 10px;">Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                <p style="margin-bottom: 15px;">Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p> --}}
                <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
            </td>
        </tr>
    </tbody>
</table>

@endsection