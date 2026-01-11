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
                    Thank you for completing the task on <b>{{ $campaign }}</b>. This is to let you know your submission has been <b>{{ $status }}</b> at <b>&#8358;{{ number_format($amount, 2) }}</b>
                    <br><br>
                    <strong>Reason for {{ $status }}:</strong> <br>
                    <span style="color: #666;">{!! $reason !!}</span>
                </p>

                @if($status == 'Denied')
                    <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                        <p style="margin: 0; color: #856404; font-weight: 600; font-size: 15px;">
                            ⏰ <strong>Important Notice</strong>
                        </p>
                        <p style="margin: 10px 0 0 0; color: #856404; line-height: 1.6;">
                            You have <strong style="color: #d63031;">12 hours</strong> from the time of this denial to challenge this decision.
                            If you believe this denial was made in error, please submit a dispute through your dashboard immediately.
                        </p>
                        <p style="margin: 10px 0 0 0; color: #856404; font-size: 12px; line-height: 1.5;">
                            <strong>⚠️ Please Note:</strong> After 12 hours, your slot will be automatically released to other workers and you will no longer be able to dispute this decision.
                        </p>
                    </div>

                    <p style="margin-bottom: 10px; margin-top: 25px;">
                       <br><br>
                        <a href="{{ url('campaign/completed/jobs') }}" target="_blank"
                           style="background-color:#ffc107;
                                  border-radius:4px;
                                  color:#000000;
                                  display:inline-block;
                                  font-size:13px;
                                  font-weight:600;
                                  line-height:44px;
                                  text-align:center;
                                  text-decoration:none;
                                  text-transform: uppercase;
                                  padding: 0 30px;
                                  box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            ⚡ Review & Dispute Now
                        </a>
                    </p>

                    {{-- <div style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #dee2e6;">
                        <p style="margin: 0; font-size: 13px; color: #6c757d; line-height: 1.6;">
                            <strong>How to Dispute:</strong><br>
                            1. Click the button above to go to your completed jobs<br>
                            2. Find this campaign in your list<br>
                            3. Click the "Dispute" button<br>
                            4. Provide your reason for the dispute<br>
                            5. Our team will review within 24-48 hours
                        </p>
                    </div> --}}

                @else
                    <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 4px;">
                        <p style="margin: 0; color: #155724; font-weight: 600;">
                            ✅ <strong>Congratulations!</strong>
                        </p>
                        <p style="margin: 10px 0 0 0; color: #155724;">
                            Your payment of <strong>&#8358;{{ number_format($amount, 2) }}</strong> has been credited to your wallet.
                            Keep up the great work!
                        </p>
                    </div>

                    <p style="margin-bottom: 10px;">
                        Ready for more opportunities? Click the button below to access more jobs... <br><br>
                        <a href="{{ url('home') }}" target="_blank"
                           style="background-color:#6576ff;
                                  border-radius:4px;
                                  color:#ffffff;
                                  display:inline-block;
                                  font-size:13px;
                                  font-weight:600;
                                  line-height:44px;
                                  text-align:center;
                                  text-decoration:none;
                                  text-transform: uppercase;
                                  padding: 0 30px;
                                  box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            🎯 Take More Jobs
                        </a>
                    </p>
                @endif

                <p style="margin-top: 30px; margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                    📢 Get Real Time Updates on Fresh Jobs and Tasks on our channel
                    <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                        style="color: #25D366; font-weight: 600; text-decoration: none;">
                        Join WhatsApp Channel →
                    </a>
                </p>

                <p style="margin-top: 45px; margin-bottom: 15px; color: #6c757d;">
                    ----<br>
                    Best Regards,<br>
                    <strong style="color: #6576ff;">Freebyz Team</strong><br>
                    <span style="font-size: 12px;">Working together for your success</span>
                </p>
            </td>
        </tr>
    </tbody>
</table>

@endsection
