<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Register - {{ env('APP_NAME', 'Base Project IndraKsma') }}</title>
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
                            <!-- Basic register form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center text-center">
                                    <h3 class="fw-light my-3">Register</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Register form-->
                                    <form action="{{ route('register') }}" method="POST">
                                        <!-- Form Group (name)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputName">Name</label>
                                            <input name="name" value="{{ old('name') }}"
                                                class="form-control  @error('name') is-invalid @enderror" id="inputName"
                                                type="text" placeholder="Full Name" />
                                        </div>
                                        @error('name')
                                            <div class="alert alert-danger">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
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
                                        <div class="row gx-3">
                                            <div class="col-md-6">
                                                <!-- Form Group (password)-->
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="inputPassword">Password</label>
                                                    <input name="password"
                                                        class="form-control  @error('password') is-invalid @enderror"
                                                        id="inputPassword" type="password"
                                                        placeholder="Enter password" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Form Group (confirm password)-->
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="inputConfirmPassword">Confirm
                                                        Password</label>
                                                    <input name="password_confirmation" class="form-control"
                                                        id="inputConfirmPassword" type="password"
                                                        placeholder="Confirm password">
                                                </div>
                                            </div>
                                        </div>
                                        @error('password')
                                            <div class="alert alert-danger">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <div class="d-none">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                        <button class="btn btn-primary" type="submit">Create Account</button>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small">
                                        <a href="#">Register</a>
                                    </div>
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
