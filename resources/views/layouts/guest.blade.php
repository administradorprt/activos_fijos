<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', 'es-MX') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> PRT</title>

        <link href="{{ asset('https://serviciosespecializados.grupoprt.com/public/assets/img/logo/prt_Mesa.ico') }}" rel='icon' type='image/png'>

        <!-- Fonts -->

        <style>
            .container-animation{
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                overflow: hidden
            }
            .ocean{
                position: relative;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }
            .text-prt {
                color: #00073f !important;
                -webkit-text-stroke-width: 0px;
                -webkit-text-stroke-color: red;
            }

            .area{
                background: #9c9c9c;
                width: 100%;
                height:100vh;
            }

            .circles{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: -99999;
            }

            .circles svg {
                position: absolute;
                display: block;
                list-style: none;
                width: 20px;
                height: 20px;
                background: transparent;
                animation: animate 5s linear infinite;
                bottom: -150px;
                z-index: -99999;
            }

            .circles svg:nth-child(1){
                left: 25%;
                width: 80px;
                height: 80px;
                animation-delay: 0s;
            }

            .circles svg:nth-child(2){
                left: 10%;
                width: 20px;
                height: 20px;
                animation-delay: 2s;
                animation-duration: 12s;
            }

            .circles svg:nth-child(3){
                left: 70%;
                width: 20px;
                height: 20px;
                animation-delay: 4s;
            }

            .circles svg:nth-child(4){
                left: 40%;
                width: 60px;
                height: 60px;
                animation-delay: 0s;
                animation-duration: 18s;
            }

            .circles svg:nth-child(5){
                left: 65%;
                width: 20px;
                height: 20px;
                animation-delay: 0s;
            }

            .circles svg:nth-child(6){
                left: 75%;
                width: 110px;
                height: 110px;
                animation-delay: 3s;
            }

            .circles svg:nth-child(7){
                left: 35%;
                width: 150px;
                height: 150px;
                animation-delay: 7s;
            }

            .circles svg:nth-child(8){
                left: 50%;
                width: 25px;
                height: 25px;
                animation-delay: 15s;
                animation-duration: 45s;
            }

            .circles svg:nth-child(9){
                left: 20%;
                width: 15px;
                height: 15px;
                animation-delay: 2s;
                animation-duration: 35s;
            }

            .circles svg:nth-child(10){
                left: 85%;
                width: 150px;
                height: 150px;
                animation-delay: 0s;
                animation-duration: 11s;
            }

            @keyframes animate {

                0%{
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                    border-radius: 0;
                }

                100%{
                    transform: translateY(-1000px) rotate(720deg);
                    opacity: 0;
                    border-radius: 50%;
                }

            }

            h5 {
                font-family: "Montserrat", arial, sans-serif;
                font-weight: 700;
                max-width: 40ch;
                transform: scale(2.94);
                bottom: -15em;
                animation: scale 7s forwards cubic-bezier(0.5, 1, 0.89, 1);
            }

            h3 {
                font-family: "Montserrat", arial, sans-serif;
                font-weight: 700;
                bottom: -15em;
                max-width: 40ch;
                transform: scale(1.94);
                animation: scale 8s forwards cubic-bezier(0.5, 1, 0.89, 1);
            }

            @keyframes scale {
                100% {
                    transform: scale(1);
                }
            }

            span {
                display: inline-block;
                opacity: 0;
                filter: blur(4px);
            }

            span:nth-child(1) {
                animation: fade-in 0.8s 0.1s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(2) {
                animation: fade-in 0.8s 0.2s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(3) {
                animation: fade-in 0.8s 0.3s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(4) {
                animation: fade-in 0.8s 0.4s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(5) {
                animation: fade-in 0.8s 0.5s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(6) {
                animation: fade-in 0.8s 0.6s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(7) {
                animation: fade-in 0.8s 0.7s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(8) {
                animation: fade-in 0.8s 0.8s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(9) {
                animation: fade-in 0.8s 0.9s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(10) {
                animation: fade-in 0.8s 1s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(11) {
                animation: fade-in 0.8s 1.1s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(12) {
                animation: fade-in 0.8s 1.2s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(13) {
                animation: fade-in 0.8s 1.3s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(14) {
                animation: fade-in 0.8s 1.4s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(15) {
                animation: fade-in 0.8s 1.5s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(16) {
                animation: fade-in 0.8s 1.6s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(17) {
                animation: fade-in 0.8s 1.7s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            span:nth-child(18) {
                animation: fade-in 0.8s 1.8s forwards cubic-bezier(0.11, 0, 0.5, 0);
            }

            @keyframes fade-in {
                100% {
                    opacity: 1;
                    filter: blur(0);
                }
            }

            .box {
              position: fixed;
              top: 0;
              transform: rotate(80deg);
              left: 0;
            }

            .wave {
              position: absolute;
              opacity: .4;
              width: 1500px;
              height: 1300px;
              margin-left: -150px;
              margin-top: -250px;
              border-radius: 43%;
            }

            @keyframes rotate {
              from {transform: rotate(0deg);}
              from {transform: rotate(360deg);}
            }

            .wave.-one {
              animation: rotate 10000ms infinite linear;
              opacity: 25%;
              background: #ffffff;
            }

            .wave.-two {
              animation: rotate 6000ms infinite linear;
              opacity: 10%;
              background: #ffffff;
            }


        </style>

        <link href="//fonts.bunny.net" rel="dns-prefetch">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet" />

        <!-- CSS -->

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/turn.js/4.1.0/turn.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css' rel='stylesheet'>
        <link href="{{ url('css/toyota_style.css') }}" rel="stylesheet">
        <link href="{{ url('css/animacion.css') }}" rel="stylesheet">
        <link href="{{ url('css/loader.css') }}" rel="stylesheet">
        <link href="{{ url('css/style.css') }}" rel="stylesheet">


        <!-- JS-->

        <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-polyfills/0.1.43/polyfill.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.0.1/dist/progressbar.min.js"></script>

    </head>
    <body id="page-top" class="area">
        
        @include('layouts.loader')

        <div class="progress" style="height: 6.5px; border-rodius: 0px;">
            <div class="progress-bar" id="myProgressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="container-animation">
            <div class='ocean'>
                <div class='wave -one'> </div>
                <div class='wave -two'></div>
            </div>
        </div>

        @include('layouts.styles')
       {{--  @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"]) --}}

        <link href="{{ url('css/sweet_styles.css') }}" rel="stylesheet">

        {{ $slot }}

        @include('layouts.footer')

    </body>
    
    <script>
        $(document).ready(function () {

            $('#loginForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("login") }}',
                    data: $('#loginForm').serialize(),
                    success: function (response) {
                        console.log(response);
                        
                        if (response.success) {
                            $('#message').html('<b class="text-success"><i class="fa-solid fa-check-circle fa-beat-fade"></i> Accediendo... </b>');
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer);
                                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                                }
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Inicio de sesión exitoso.'
                            });
                            window.location.href = "dashboard";
                        } else {
                            $('#message').html('<b class="text-danger"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> Error: ' + response.message + '</b>');
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer);
                                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                                }
                            });
                            Toast.fire({
                                icon: 'error',
                                title: 'Error: ' + response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        $('#message').html('<b class="text-danger"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> Error de conexión.</b>');
                    }
                });
            });
        });
    </script>
</html>
