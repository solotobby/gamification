@extends('layouts.task')

@section('title', 'Freebyz - ' . $campaign['post_title'])

@section('meta')
    <meta name="description"
        content="Earn {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }} by completing this campaign on Freebyz">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $campaign['post_title'] }}">
    <meta property="og:description"
        content="Earn {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }} per job on Freebyz">
@endsection

@section('content')
    <style>
        .campaign-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .campaign-card .card-header {
            background: linear-gradient(135deg, #0066FF 0%, #004DB3 100%);
            color: white;
            padding: 20px;
            font-weight: 600;
        }

        .campaign-info-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }

        .campaign-info-item:last-child {
            border-bottom: none;
        }

        .campaign-info-item i {
            width: 30px;
            color: #0066FF;
            font-size: 18px;
        }

        .cta-button {
            background: linear-gradient(135deg, #0066FF 0%, #004DB3 100%);
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 102, 255, 0.4);
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 102, 255, 0.6);
            color: white;
        }

        .features-section {
            background: #f8f9fa;
            padding: 40px 0;
            margin: 40px 0;
            border-radius: 15px;
        }

        .feature-item {
            text-align: center;
            padding: 20px;
        }

        .feature-item i {
            font-size: 40px;
            color: #0066FF;
            margin-bottom: 15px;
        }

        .hero-section {
            background: linear-gradient(135deg, #0066FF 0%, #004DB3 100%);
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{asset('src/assets/media/photos/photo12@2x.jpg')}}');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row hero-content align-items-center">
                <div class="col-lg-12">
                    <h1 class="display-4 mb-3">{{ $campaign['post_title'] }}</h1>
                    <h4 class="mb-4">
                        <i class="fas fa-tag me-2"></i>{{ $campaign['campaignType']['name'] }}
                    </h4>
                    <p class="lead">
                        <i class="fas fa-folder me-2"></i>{{ $campaign['campaignCategory']['name'] }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Campaign Details -->
            <div class="col-lg-8">
                <div class="card campaign-card">
                    <div class="card-header">
                        <i class="fas fa-file-alt me-2"></i>Task Description
                    </div>
                    <div class="card-body">
                        <div class="campaign-description">
                            {!! $campaign['description'] !!}
                        </div>
                    </div>
                </div>

                <div class="card campaign-card">
                    <div class="card-header">
                        <i class="fas fa-tasks me-2"></i>Task Instructions
                    </div>
                    <div class="card-body">
                        {!! $campaign['proof'] !!}
                    </div>
                </div>

                <a href="{{ route('login') }}?redirect={{ $campaign['job_id'] }}" class="btn cta-button">
                    <i class="fas fa-sign-in-alt me-2"></i>Perform Task
                </a>

                <!-- Features Section -->
                <div class="features-section">
                    <div class="row">
                        <div class="col-md-4 feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <h5>Secure Payment</h5>
                            <p>Get paid safely after approval</p>
                        </div>
                        <div class="col-md-4 feature-item">
                            <i class="fas fa-clock"></i>
                            <h5>Quick Approval</h5>
                            <p>24-hour automatic approval</p>
                        </div>
                        <div class="col-md-4 feature-item">
                            <i class="fas fa-users"></i>
                            <h5>Join Thousands</h5>
                            <p>Of satisfied workers</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center my-5">
                    <h3 class="mb-4">Ready to Get Started?</h3>
                    <p class="mb-4">Login to perform this job and earn {{ $campaign['currency'] }}
                        {{ number_format($campaign['campaign_amount'], 2) }}
                    </p>
                    <a href="{{ route('login') }}?redirect={{ $campaign['job_id'] }}" class="btn cta-button">
                        <i class="fas fa-sign-in-alt me-2"></i>Perform Tasks
                    </a>
                    <p class="mt-3">
                        Don't have an account?
                        <a href="{{ route('register') }}?redirect={{ $campaign['job_id'] }}"
                            class="fw-bold text-primary">Sign up now</a>
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card campaign-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>Task Summary
                    </div>
                    <div class="card-body p-0">
                        <div class="campaign-info-item">
                            <i class="fas fa-briefcase"></i>
                            <div>
                                <strong>Task Type</strong><br>
                                <span class="text-muted">{{ $campaign['campaignType']['name'] }}</span>
                            </div>
                        </div>
                        <div class="campaign-info-item">
                            <i class="fas fa-folder"></i>
                            <div>
                                <strong>Category</strong><br>
                                <span class="text-muted">{{ $campaign['campaignCategory']['name'] }}</span>
                            </div>
                        </div>
                        <div class="campaign-info-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>
                                <strong>Payment per Job</strong><br>
                                <span class="text-success fw-bold">{{ $campaign['currency'] }}
                                    {{ number_format($campaign['campaign_amount'], 2) }}</span>
                            </div>
                        </div>
                        <div class="campaign-info-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Workers Needed</strong><br>
                                <span class="text-muted">{{ $campaign['number_of_staff'] }} workers</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Card -->
                <div class="card campaign-card mt-4">
                    <div class="card-header">
                        <i class="fas fa-share-alt me-2"></i>Share This Task
                    </div>

                    @php
                        $campaignUrl = url()->current();
                        $shareText = "Complete this {$campaign['post_title']} task on Freebyz to earn {$campaignUrl}";
                    @endphp

                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- Copy link -->
                            <button type="button" class="btn btn-secondary" onclick="copyShareLink()">
                                <i class="fas fa-link me-2"></i>Copy Link
                            </button>

                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ rawurlencode($shareText) }}" target="_blank" class="btn"
                                style="background:#25D366;color:#fff;">
                                <i class="fab fa-whatsapp me-2"></i>Share on WhatsApp
                            </a>

                            <!-- X -->
                            <a href="https://x.com/intent/tweet?text={{ rawurlencode($shareText) }}" target="_blank"
                                class="btn" style="background:#1DA1F2;color:#fff;">
                                <i class="fab fa-x me-2"></i>Share on X
                            </a>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($campaignUrl) }}"
                                target="_blank" class="btn" style="background:#1877F2;color:#fff;">
                                <i class="fab fa-facebook-f me-2"></i>Share on Facebook
                            </a>
                        </div>

                        <input type="hidden" id="shareLink" value="{{ $campaignUrl }}">
                        <small class="text-success d-block mt-2" id="copyMessage" style="display:none;"></small>
                    </div>
                </div>

                {{-- Quick Gigs CTA Card --}}
                <div class="card campaign-card mt-4"
                    style="background: linear-gradient(135deg, #4F8EF7 0%, #2563eb 100%); color: white; border: none;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size: 24px; margin-right: 10px;">⚡</span>
                            <h5 class="mb-0 fw-bold">Need a Full/Part Time Job?</h5>
                        </div>
                        <p class="mb-3" style="font-size: 14px; opacity: 0.9;">
                            Check out companies hiring around you today!
                        </p>
                        <a href="{{ url('/jobs') }}" class="btn btn-light w-100 fw-semibold" style="color: #2563eb;">
                            Explore Jobs →
                        </a>
                    </div>
                </div>

                {{-- Payhankey CTA Card --}}
                <div class="card campaign-card mt-4"
                    style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color: white; border: none;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size: 24px; margin-right: 10px;">⚡</span>
                            <h5 class="mb-0 fw-bold">Has your Content ever made you enough money?</h5>
                        </div>
                        <p class="mb-3" style="font-size: 14px; opacity: 0.9;">
                            Monetize your posts and content on our social media platform - Payhankey
                        </p>
                        <a href="https://payhankey.com" target="_blank" class="btn btn-light w-100 fw-semibold"
                            style="color: #7c3aed;">
                            Get Started →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyShareLink() {
                const shareLink = document.getElementById('shareLink').value;
                const copyMessage = document.getElementById('copyMessage');

                navigator.clipboard.writeText(shareLink).then(function () {
                    copyMessage.textContent = '✓ Link copied to clipboard!';
                    copyMessage.style.display = 'block';

                    setTimeout(function () {
                        copyMessage.style.display = 'none';
                    }, 3000);
                }, function (err) {
                    alert('Failed to copy link. Please copy manually: ' + shareLink);
                });
            }
        </script>
    @endpush
@endsection
