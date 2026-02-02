<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Not Found - Freebyz</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .site-header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .site-header .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .not-found-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            padding: 20px;
        }

        .not-found-card {
            background: white;
            border-radius: 20px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .not-found-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }

        .not-found-icon i {
            font-size: 60px;
            color: white;
        }

        .site-footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="https://freebyz.com">
                    <img src="{{ url('src/assets/media/photos/freebyzlogo-blue.png') }}" alt="Freebyz Logo"
                        style="height: 30px; width: auto; margin-right: 8px;">
                </a>
                <div class="ms-auto">
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="not-found-container">
        <div class="not-found-card">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="mb-3">Campaign Not Found</h1>
            <p class="lead text-muted mb-4">
                We couldn't find the campaign you're looking for. It may have been removed or the link is incorrect.
            </p>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                This campaign may no longer be available or never existed.
            </div>
            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="https://freebyz.com" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Go to Homepage
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to View Available Campaigns
                </a>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>
                        <img src="{{ url('src/assets/media/photos/freebyz-logo-white.png') }}" alt="Freebyz Logo"
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
</body>

</html>
