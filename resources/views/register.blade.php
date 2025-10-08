<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Register | Able Pro Dashboard Template</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies.">
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard">
    <meta name="author" content="Phoenixcoded">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />

    <!-- [Tabler Icons] https://tablericons.com/ -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com/ -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <form method="post" action="{{ route('doRegister') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <div class="card my-5">
                        <div class="card-body">
                            <div class="text-center">
                                <center>
                                    <a href="#"><img src="{{ asset('assets/images/logo-dark.svg') }}"
                                            alt="img"></a>
                                </center>
                                <div class="d-grid my-3">

                                </div>
                            </div>
                            <div class="saprator my-3">

                            </div>
                            <h4 class="text-center f-w-500 mb-3 ">Sign up with your work email.</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <input type="text" name="last_name" class="form-control"
                                            placeholder="Apellido">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" value="{{ $email }}" name="email" disabled
                                    class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Contraseña">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control" name="cpassword"
                                    placeholder="Confirmar Contraseña">
                            </div>
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1"
                                        checked="" required>
                                    <label class="form-check-label text-muted" for="customCheckc1">Al registrarme acepto
                                        los
                                        terminos y condiciones</label>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Registrate</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <h6 class="f-w-500 mb-0">Ya tienes una cuenta?</h6>
                                <a href="{{ route('login') }}" class="link-primary">Inicia Sesión</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<!-- [Body] end -->
<!-- Required Js -->
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if (session()->has('error'))
        Swal.fire('', '{{ session()->get('error') }}', 'error');
    @endif
    @if (session()->has('success'))
        Swal.fire('', '{{ session()->get('success') }}', 'success');
    @endif
</script>


</html>
