<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicons -->
    <link href="{{asset('theme/common/img/favicon.png')}}" rel="icon">
    <link href="{{asset('theme/common/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('theme/common/img/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('theme/common/img/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme/common/img/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('theme/common/img/site.webmanifest')}}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('theme/resi/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('theme/resi/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('theme/resi/assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('theme/resi/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('theme/resi/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('theme/resi/assets/css/main.css')}}" rel="stylesheet">
</head>
<body class="portfolio-details-page">
    @include('layouts.common.header')
    
    <main class="main">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
    
    @include('layouts.common.footer')
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{asset('theme/common/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('theme/common/js/sweetalert2.all.min.js')}}"></script>    
    <script src="{{asset('theme/resi/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/php-email-form/validate.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    
    <!-- Main JS File -->
    <script src="{{asset('theme/resi/assets/js/main.js')}}"></script>
    <script src="{{asset('theme/common/js/jquery.Rut.js')}}"></script>
    @yield('js')
</body>
</html>
