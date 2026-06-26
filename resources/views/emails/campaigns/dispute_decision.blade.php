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
                <p style="margin-bottom: 10px;">Hi <strong>{{ $name }},</strong></p>

                <p style="margin-bottom: 10px;">
                    We have completed the review of your dispute regarding your submission for the campaign
                    <strong>{{ $campaign }}</strong>.
                </p>

                <p style="margin-bottom: 10px;">
                    <strong>Dispute Outcome:</strong>
                    <span style="color: {{ $status == 'Approved' ? '#28a745' : '#dc3545' }};">
                        <strong>{{ $status }}</strong>
                    </span>
                </p>

                <p style="margin-bottom: 10px;">
                    <strong>Reason:</strong><br>
                    <span style="color: #666;">{!! $reason !!}</span>
                </p>

                @if($status == 'Approved')

                    <div
                        style="background-color:#d4edda;border-left:4px solid #28a745;padding:15px;margin:20px 0;border-radius:4px;">
                        <p style="margin:0;color:#155724;font-weight:600;">
                            ✅ <strong>Your dispute has been approved.</strong>
                        </p>

                        <p style="margin:10px 0 0 0;color:#155724;line-height:1.6;">
                            Your original submission has been accepted and the payment of
                            <strong>&#8358;{{ number_format($amount, 2) }}</strong>
                            has been credited to your wallet.
                        </p>

                        <p style="margin:10px 0 0 0;color:#155724;">
                            Thank you for your patience while we reviewed your case.
                        </p>
                    </div>

                    <p style="margin-bottom:10px;">
                        Ready for more opportunities?<br><br>

                        <a href="https://dashboard.freebyz.com/jobs" target="_blank" style="background-color:#6576ff;
                      border-radius:4px;
                      color:#ffffff;
                      display:inline-block;
                      font-size:13px;
                      font-weight:600;
                      line-height:44px;
                      text-align:center;
                      text-decoration:none;
                      text-transform:uppercase;
                      padding:0 30px;">
                            🎯 Take More Jobs
                        </a>
                    </p>

                @else

                    <div
                        style="background-color:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:20px 0;border-radius:4px;">
                        <p style="margin:0;color:#721c24;font-weight:600;">
                            ❌ <strong>Your dispute has been denied.</strong>
                        </p>

                        <p style="margin:10px 0 0 0;color:#721c24;line-height:1.6;">
                            After carefully reviewing the evidence provided, our team has decided to uphold the original
                            decision.
                        </p>

                        <p style="margin:10px 0 0 0;color:#721c24;">
                            This decision is final and no further action is required from you regarding this submission.
                        </p>
                    </div>

                @endif
            </tr>
        </tbody>
    </table>

@endsection
