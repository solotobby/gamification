@extends('email_template.master')

@section('content')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
            <!-- Header -->
            <tr>
                <td style="padding:30px 30px 15px 30px;">
                    <h2 style="font-size:18px;color:#6576ff;font-weight:600;margin:0;">
                        🚀 New Career Opportunities on Freebyz
                    </h2>
                </td>
            </tr>

            <!-- Body -->
            <tr>
                <td style="padding:30px;">
                    <p style="margin-bottom:10px;">
                        Hi <strong>{{ $userName ?? 'there' }},</strong>
                    </p>

                    <p style="margin-bottom:20px;">
                        Exciting job opportunities are now available on
                        <a href="{{ route('jobs') }}" style="color:#6576ff;font-weight:600;">Freebyz</a>.
                        Find your next career move today! 💼
                    </p>

                    @foreach($jobs as $job)
                        <div style="
                                    background:#f8f9fa;
                                    border:1px solid #e5e9f2;
                                    border-radius:8px;
                                    padding:20px;
                                    margin-bottom:15px;
                                ">
                            <!-- Job Header -->
                            <div style="display:flex;align-items:center;margin-bottom:12px;">
                                @if($job['company_logo'])
                                    <img src="{{ $job['company_logo'] }}" alt="{{ $job['company_name'] }}"
                                        style="width:40px;height:40px;border-radius:6px;margin-right:12px;object-fit:cover;">
                                @endif
                                <div>
                                    <h4 style="margin:0 0 4px 0;color:#000;font-size:16px;">
                                        {{ $job['title'] }}
                                    </h4>
                                    <p style="margin:0;color:#666;font-size:13px;">
                                        {{ $job['company_name'] }}
                                    </p>
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div style="margin-bottom:12px;">
                                <p style="margin:0 0 5px 0;color:#555;font-size:14px;">
                                    <span style="color:#6576ff;">📍</span> <strong>Location:</strong>
                                    {{ $job['location'] }}
                                    @if($job['remote_allowed'])
                                        <span style="
                                                        background:#e8f5e9;
                                                        color:#2e7d32;
                                                        padding:2px 8px;
                                                        border-radius:4px;
                                                        font-size:11px;
                                                        margin-left:6px;
                                                    ">Remote</span>
                                    @endif
                                </p>

                                <p style="margin:0 0 5px 0;color:#555;font-size:14px;">
                                    <span style="color:#6576ff;">💼</span> <strong>Type:</strong> {{ ucfirst($job['type']) }}
                                </p>

                                @if($job['salary_range'])
                                    <p style="margin:0 0 5px 0;color:#555;font-size:14px;">
                                        <span style="color:#6576ff;">💰</span> <strong>Salary:</strong> {{ $job['salary_range'] }}
                                    </p>
                                @endif

                                <p style="margin:0;color:#999;font-size:12px;">
                                    Posted {{ $job['posted_at'] }}
                                </p>
                            </div>

                            <!-- Tier Badge -->
                            {{-- @if($job['tier'] === 'premium')
                                <span style="
                                                display:inline-block;
                                                background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                color:#fff;
                                                padding:4px 10px;
                                                border-radius:4px;
                                                font-size:11px;
                                                font-weight:600;
                                                margin-bottom:12px;
                                            ">⭐ PREMIUM</span>
                            @endif --}}

                            <!-- CTA Button -->
                            <a href="{{ route('career-hub.show', $job['slug']) }}" target="_blank" style="
                                           display:inline-block;
                                           padding:10px 18px;
                                           background-color:#6576ff;
                                           color:#ffffff;
                                           text-decoration:none;
                                           border-radius:5px;
                                           font-weight:600;
                                           font-size:14px;
                                       ">
                                View Job Details
                            </a>
                        </div>
                    @endforeach

                    <!-- Browse More -->
                    <div style="
                            text-align:center;
                            padding:20px;
                            background:#f0f4ff;
                            border-radius:8px;
                            margin-top:20px;
                        ">
                        <p style="margin:0 0 12px 0;color:#333;">
                            <strong>Want to see more opportunities?</strong>
                        </p>
                        <a href="{{ route('career-hub.index') }}" target="_blank" style="
                                   display:inline-block;
                                   padding:12px 24px;
                                   background-color:#6576ff;
                                   color:#ffffff;
                                   text-decoration:none;
                                   border-radius:5px;
                                   font-weight:600;
                               ">
                            Browse All Jobs
                        </a>
                    </div>

                    <p style="margin-top:25px;">
                        Get real-time updates on fresh jobs and tasks:
                        <br>
                        <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                            style="color:#25D366;font-weight:600;">
                            Join WhatsApp Channel
                        </a>
                    </p>

                    <!-- Footer -->
                    <p style="margin-top:45px;margin-bottom:15px;">
                        ---- <br>
                        Best regards, <br>
                        <i>{{ config('app.name') }} Team</i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
