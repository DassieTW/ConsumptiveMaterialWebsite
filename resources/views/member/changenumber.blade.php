<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />
    <script src="{{ asset('../js/app.js') }}" defer></script>

    <script src="{{ asset('../js/login.js') }}" defer></script>

    <link href="../admin/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <main class="d-flex w-100 h-100">
            <div class="container d-flex flex-column">
                <div class="row vh-100">
                    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                        <div class="d-table-cell align-middle">

                            <div class="text-center mt-4">
                                <h1 class="h2">Welcome</h1>
                                <p class="lead">
                                    Change your job number and more.
                                </p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-4">
                                        <div class="text-center">
                                            <img src="../admin/img/avatars/avatar.jpg" alt="Charles Hall" class="img-fluid rounded-circle" width="132" height="132" />
                                        </div>
                                        <form action="{{ route('member.changenumber') }}" method="POST">
                                            @csrf
                                            <?php
                                                $username = Session::get('number');
                                                echo "now search job number : " . $username;
                                            ?>


                                            <div class="mb-3">
                                                <label class="form-label">New Name</label>
                                                <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id ="name" name="name" placeholder="Enter new name"/>
                                                @error('name')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">New Department</label>
                                                <input class="form-control form-control-lg @error('department') is-invalid @enderror" type="text" id = "department" name="department" placeholder="Enter new department"/>
                                                @error('department')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="text-center mt-3">
                                                <input type = "submit" class="btn btn-lg btn-primary" value="Change">

                                                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                            </div>
                                        </form>
                                        <div class="text-center mt-3">
                                            <input type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'" value="Home">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
