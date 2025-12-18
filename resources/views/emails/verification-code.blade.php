@extends('email_template.master')

@section('content')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
            <tr>
                <td style="padding: 30px 30px 15px 30px;">
                    <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">
                        Email Verification
                    </h2>
                </td>
            </tr>
            <tr>
                <td style="padding: 30px 30px 20px">
                    <p style="margin-bottom: 10px;">Hi <strong>{{ $userName }},</strong></p>
                    <p style="margin-bottom: 10px;">

                        Dear friend, I am super excited to welcome you to <a href="{{ url('/') }}">Freebyz</a>, Africa’s
                        trusted platform for micro and remote jobs since 2022.
                        We are an 100% registered and Legit company. With your smartphone or PC, you can start earning
                        legitimately today on Freebyz
                        as your side hustle platform to pay your Bills 💳 from now on.

                        Please use the verification code below to verify your email address:
                    </p>

                    <div
                        style="background:#f8f9fa;border:2px dashed #6576ff;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">
                        <span
                            style="font-size:32px;font-weight:bold;color:#6576ff;letter-spacing:8px;font-family:'Courier New', monospace;">
                            {{ $code }}
                        </span>
                    </div>

                    <p style="margin-bottom: 10px;">
                        This code will expire in <strong>10 minutes</strong>.
                    </p>

                    <p style="margin-top: 20px; font-size:14px; color:#dc3545;">
                        ⚠️ If you didn't request this code, please ignore this email.
                    </p>

                    <p style="margin-top: 20px; margin-bottom: 20px;">
                        Get Real Time Updates on Fresh Jobs and Tasks on our channel
                        <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                            style="color: #25D366; font-weight: 600;">Join WhatsApp Channel</a>
                    </p>

                    <p style="margin-top: 45px; margin-bottom: 15px;">
                        ---- <br>
                        Regards, <br>
                        <i>{{ config('app.name') }} Team.</i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
