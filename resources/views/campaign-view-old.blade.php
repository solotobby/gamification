<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freebyz - {{ $campaign['post_title'] }}</title>
    <meta name="description"
        content="Earn {{ $campaign['local_converted_currency'] }} {{ $campaign['local_converted_amount'] }} by completing this campaign on Freebyz">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $campaign['post_title'] }}">
    <meta property="og:description"
        content="Earn {{ $campaign['local_converted_currency'] }} {{ $campaign['local_converted_amount'] }} per job on Freebyz">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header styles matching freebyz.com/about-us */
        .site-header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .site-header .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .site-header .nav-link {
            color: #333;
            margin: 0 15px;
            font-weight: 500;
        }

        .site-header .nav-link:hover {
            color: #007bff;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #2c3e50 100%);
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

        .amount-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .amount-badge h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .campaign-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .campaign-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #2c3e50 100%);
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
            color: #667eea;
            font-size: 18px;
        }

        .cta-button {
            background: linear-gradient(135deg, #667eea 0%, #2c3e50 100%);
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
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
            color: #667eea;
            margin-bottom: 15px;
        }

        /* Footer styles matching freebyz.com/about-us */
        .site-footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 60px 0 30px;
            margin-top: 60px;
        }

        .footer-section h5 {
            color: #fff;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul li a:hover {
            color: #3498db;
        }

        .footer-bottom {
            border-top: 1px solid #34495e;
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
        }

        .social-links a {
            color: #ecf0f1;
            font-size: 20px;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #3498db;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand d-flex align-items-center" href="https://freebyz.com">
                    <img src="{{ url('src/assets/media/photos/freebyzlogo-blue.png') }}" alt="Freebyz Logo"
                        style="height: 30px; width: auto; margin-right: 8px;">
                    {{-- <span>Freebyz</span> --}}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="https://freebyz.com">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://freebyz.com/about-us">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://freebyz.com/contact">Contact</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-4" href="{{ route('login') }}">Login</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row hero-content align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 mb-3">{{ $campaign['post_title'] }}</h1>
                    <h4 class="mb-4">
                        <i class="fas fa-tag me-2"></i>{{ $campaign['campaignType']['name'] }}
                    </h4>
                    <p class="lead">
                        <i class="fas fa-folder me-2"></i>{{ $campaign['campaignCategory']['name'] }}
                    </p>
                </div>
                {{-- <div class="col-lg-4">
                    <div class="amount-badge">
                        <h2>{{ $campaign['local_converted_currency'] }} {{ $campaign['local_converted_amount'] }}</h2>
                        <p class="mb-0">Per Job Completed</p>
                    </div>
                </div> --}}
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
                        <i class="fas fa-file-alt me-2"></i>Campaign Description
                    </div>
                    <div class="card-body">
                        {{-- @if($campaign->post_link != '')
                            <div class="alert alert-info">
                                <i class="fas fa-link me-2"></i>
                                <strong>Campaign Link:</strong>
                                <a href="{{ $campaign->post_link }}" target="_blank"></a>
                            </div>
                        @endif --}}

                        <div class="campaign-description">
                            {!! $campaign->description !!}
                        </div>



                    </div>
                </div>

                <div class="card campaign-card">
                    <div class="card-header">
                        <i class="fas fa-tasks me-2"></i>Campaign Instructions
                    </div>
                    <div class="card-body">
                        {!! $campaign->proof !!}


                    </div>



                </div>

                <a href="{{ route('login') }}?redirect={{ $campaign->job_id }}" class="btn btn-primary">
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
                    <p class="mb-4">Login to perform this job and earn {{ $campaign['local_converted_currency'] }}
                        {{ $campaign['local_converted_amount'] }}
                    </p>
                    <a href="{{ route('login') }}?redirect={{ $campaign->job_id }}" class="btn btn-primary cta-button">
                        <i class="fas fa-sign-in-alt me-2"></i>Perform Tasks
                    </a>
                    <p class="mt-3">
                        Don't have an account?
                        <a href="{{ route('register') }}?redirect={{ $campaign->job_id }}" class="fw-bold">Sign up
                            now</a>
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card campaign-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>Campaign Summary
                    </div>
                    <div class="card-body p-0">
                        <div class="campaign-info-item">
                            <i class="fas fa-briefcase"></i>
                            <div>
                                <strong>Campaign Type</strong><br>
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
                                <span class="text-success fw-bold">{{ $campaign['local_converted_currency'] }}
                                    {{ $campaign['local_converted_amount'] }}</span>
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
                {{-- <div class="card campaign-card mt-4">
                    <div class="card-header">
                        <i class="fas fa-share-alt me-2"></i>Share This Campaign
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-secondary" onclick="copyShareLink()">
                                <i class="fas fa-link me-2"></i>Copy Link
                            </button>
                            <a href="https://wa.me/?text=Check out this amazing campaign on Freebyz! {{ urlencode(url()->current()) }}"
                                target="_blank" class="btn" style="background: #25D366; color: white;">
                                <i class="fab fa-whatsapp me-2"></i>Share on WhatsApp
                            </a>
                            <a href="https://x.com/intent/post?text=Check out this amazing campaign on Freebyz!&url={{ urlencode(url()->current()) }}"
                                target="_blank" class="btn" style="background: #1DA1F2; color: white;">
                                <i class="fab fa-x me-2"></i>Share on X
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank" class="btn" style="background: #1877F2; color: white;">
                                <i class="fab fa-facebook-f me-2"></i>Share on Facebook
                            </a>

                        </div>
                        <input type="hidden" id="shareLink" value="{{ url()->current() }}">
                        <small class="text-success d-block mt-2" id="copyMessage" style="display:none;"></small>
                    </div>
                </div> --}}

                <div class="card campaign-card mt-4">
  <div class="card-header">
    <i class="fas fa-share-alt me-2"></i>Share This Campaign
  </div>

  @php
    $campaignUrl = url()->current();
    $shareText = "Complete this {$campaign->post_title} task on Freebyz to earn {$campaignUrl}";
  @endphp

  <div class="card-body">
    <div class="d-grid gap-2">

      <!-- Copy link -->
      <button type="button" class="btn btn-secondary" onclick="copyShareLink()">
        <i class="fas fa-link me-2"></i>Copy Link
      </button>

      <!-- WhatsApp -->
      <a
        href="https://wa.me/?text={{ rawurlencode($shareText) }}"
        target="_blank"
        class="btn"
        style="background:#25D366;color:#fff;"
      >
        <i class="fab fa-whatsapp me-2"></i>Share on WhatsApp
      </a>

      <!-- X -->
      <a
        href="https://x.com/intent/tweet?text={{ rawurlencode($shareText) }}"
        target="_blank"
        class="btn"
        style="background:#1DA1F2;color:#fff;"
      >
        <i class="fab fa-x me-2"></i>Share on X
      </a>

      <!-- Facebook -->
      <a
        href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($campaignUrl) }}"
        target="_blank"
        class="btn"
        style="background:#1877F2;color:#fff;"
      >
        <i class="fab fa-facebook-f me-2"></i>Share on Facebook
      </a>

    </div>

    <input type="hidden" id="shareLink" value="{{ $campaignUrl }}">
    <small class="text-success d-block mt-2" id="copyMessage" style="display:none;"></small>
  </div>
</div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>
                        <img src="{{ url('src/assets/media/photos/Freebyz-logo-white.png') }}" alt="Freebyz Logo"
                            style="height: 30px; width: auto; margin-right: 8px;">
                    </h5>
                    <p>Earn from micro and remote job.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Freebyz. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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
</body>

</html>
