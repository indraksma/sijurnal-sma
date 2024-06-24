<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - {{ env('APP_NAME', 'Base Project IndraKsma') }}</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <!-- Basic login form-->
                            <div class="card card-angles shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center text-center">
                                    @if (file_exists(public_path() . '/storage/img/logo.png'))
                                        <img src="{{ url('storage/img/logo.png') }}" style="max-width:20%" />
                                    @else
                                        <img src="{{ asset('assets/img/logo-default.png') }}" style="max-width:20%" />
                                    @endif
                                    <p class="text-center mt-2 mb-0">Sistem Informasi Jurnal Pembelajaran</p>
                                </div>
                                <div class="card-body rounded-bottom">
                                    <!-- Login form-->
                                    <form action="{{ route('login') }}" method="POST">
                                        <div class="d-none">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input name="email" value="{{ old('email') }}"
                                                class="form-control  @error('email') is-invalid @enderror"
                                                id="inputEmailAddress" type="email"
                                                placeholder="Enter email address" />
                                        </div>
                                        @error('email')
                                            <div class="alert alert-danger">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input name="password" value="{{ old('password') }}"
                                                class="form-control  @error('password') is-invalid @enderror"
                                                id="inputPassword" type="password" placeholder="Enter password" />
                                        </div>
                                        @error('password')
                                            <div class="alert alert-danger">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <!-- Form Group (remember password checkbox)-->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input name="remember" {{ old('remember') ? 'checked' : '' }}
                                                    class="form-check-input" id="rememberPasswordCheck" type="checkbox"
                                                    value="" />
                                                <label class="form-check-label" for="rememberPasswordCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <!-- Form Group (login box)-->
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            {{-- <a class="small" href="auth-password-basic.html">Forgot Password?</a> --}}
                                            <button class="btn btn-primary" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-12 text-center small">Copyright &copy; IndraKsma 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
