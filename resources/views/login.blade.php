<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diginamic IT Solutions Pvt Ltd</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Segoe UI, sans-serif;
        }

        .login-card {
            width: 900px;
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, .25);
            overflow: hidden;
        }

        .img-fluid {
            max-width: 28%;
            height: auto;
        }

        .workflow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            /* Always one row */
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
            overflow-x: auto;
            /* Scroll only if screen is very small */
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 75px;
            flex-shrink: 0;
        }

        .step i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .step small {
            font-size: 11px;
            font-weight: 600;
            color: #444;
            text-align: center;
            line-height: 1.2;
        }

        .arrow {
            color: #adb5bd;
            font-size: 18px;
            flex-shrink: 0;
            margin: 0 5px;
        }

        /* Colors */
        .login {
            color: #2563eb;
        }

        .create {
            color: #22c55e;
        }

        .fill {
            color: #f59e0b;
        }

        .review {
            color: #7c3aed;
        }

        .generate {
            color: #06b6d4;
        }

        .view {
            color: #1d4ed8;
        }

        .share {
            color: #ec4899;
        }




        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #fff;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
        }

        .company {
            color: #6c757d;
            font-size: 14px;
        }

        .card-header {
            background: white;
            border: none;
            padding-top: 35px;
        }

        .footer {
            font-size: 13px;
            color: #888;
        }

        .password-toggle {
            cursor: pointer;
        }
    </style>

</head>

<body>

    <div class="card login-card">

        <div class="card-header text-center">

            <!-- Workflow -->
            <div class="workflow">

                <div class="step">
                    <i class="bi bi-person-fill login"></i>
                    <small>Login</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-file-earmark-plus create"></i>
                    <small>Create Invoice</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-pencil-square fill"></i>
                    <small>Fill Details</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-search review"></i>
                    <small>Review</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-file-earmark-check generate"></i>
                    <small>Generate</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-eye view"></i>
                    <small>View Invoice</small>
                </div>

                <div class="arrow"><i class="bi bi-arrow-right"></i></div>

                <div class="step">
                    <i class="bi bi-share-fill share"></i>
                    <small>Download PDF / Share WhatsApp</small>
                </div>

            </div>
            {{-- <img src="{{ asset('images/Screenshot 2026-07-31 124520.png') }}"
                alt="Workflow"
                class="workflow-img img-fluid"> --}}

            <!-- Company Logo -->
            <img src="{{ asset('images/logo.png') }}" alt="Webzone Expertz" class="company-logo img-fluid">
            <h3 class="text-center" style="color: rgba(71, 40, 226, 0.95);">Welcome to Invoice Management System</h3>

        </div>

        <div class="card-body px-4 pb-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Email Address
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>

                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Enter your email" required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>

                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>

                        <span class="input-group-text password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>

                    </div>

                    {{-- <a href="#" class="text-decoration-none">
                    Forgot Password?
                </a> --}}

                </div>

                <button class="btn btn-primary btn-login w-100" type="submit">

                    <i class="bi bi-box-arrow-in-right"></i>

                    Login

                </button>

            </form>

        </div>

        <div class="card-footer text-center bg-white border-0 pb-4">

            <div class="footer">
                Designed and developed by © {{ date('Y') }} <a href="https://webzoneexpertz.com"
                    target="_blank">Webzone Expertz</a>
            </div>

        </div>

    </div>

    <script>
        function togglePassword() {

            let password = document.getElementById('password');
            let eye = document.getElementById('eyeIcon');

            if (password.type === "password") {
                password.type = "text";
                eye.classList.remove("bi-eye");
                eye.classList.add("bi-eye-slash");
            } else {
                password.type = "password";
                eye.classList.remove("bi-eye-slash");
                eye.classList.add("bi-eye");
            }

        }
    </script>

</body>

</html>
