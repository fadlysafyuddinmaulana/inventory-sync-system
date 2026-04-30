<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(32, 201, 151, 0.14), transparent 28%),
                linear-gradient(135deg, #0f172a 0%, #111827 42%, #1f2937 100%);
        }

        .login-shell {
            min-height: 100vh;
        }

        .login-panel {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d6efd, #20c997);
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            letter-spacing: .04em;
        }
    </style>
</head>

<body>
    <div class="container login-shell d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center align-items-stretch g-0" style="max-width: 1040px;">
            <div class="col-lg-5 d-none d-lg-flex align-items-stretch">
                <div class="text-white p-5 rounded-start-4 w-100 d-flex flex-column justify-content-between"
                    style="background: linear-gradient(160deg, rgba(13,110,253,0.95), rgba(32,201,151,0.85));">
                    <div>
                        <div class="brand-mark mb-4">IS</div>
                        <h1 class="display-6 fw-semibold mb-3">Sistem Monitoring Inventory & Backup Data</h1>
                        <p class="lead text-white-75 mb-0">Akses cepat ke produk, stok warehouse, pergerakan barang,
                            dan backup data dalam satu panel yang rapi.</p>
                    </div>
                    <div class="small text-white-50">
                        Dibangun dengan Bootstrap CDN agar lebih ringan dan konsisten.
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="login-panel rounded-4 h-100 p-4 p-md-5">
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3 d-lg-none">
                            <div class="brand-mark">IS</div>
                            <div>
                                <h1 class="h3 mb-0 fw-bold">{{ config('app.name', 'Laravel') }}</h1>
                                <div class="text-muted">Sistem Monitoring Inventory & Backup Data</div>
                            </div>
                        </div>
                        <h2 class="h3 fw-bold text-dark mb-2 d-none d-lg-block">Masuk ke akun Anda</h2>
                        <p class="text-secondary mb-0">Gunakan username atau email yang terdaftar untuk melanjutkan.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            {{ $errors->first('username') ?? 'Username atau password tidak sesuai.' }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="alert alert-primary border-0 shadow-sm">
                        <div class="fw-semibold mb-1">Demo Credentials</div>
                        <div>admin / admin123</div>
                        <div>admin@example.com / admin123</div>
                        <div>user / user123</div>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="mt-4">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}" placeholder="Username"
                                required autofocus>
                            <label for="username">Username</label>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="remember"
                                    name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <span class="text-muted small">Akses aman via session Laravel</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0Y3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdlE7N7N6jI5l1xgq" crossorigin="anonymous">
    </script>
</body>

</html>
