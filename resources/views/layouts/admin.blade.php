<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> --}}

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- @vite(['resources/js/app.js', 'resources/css/app_output.css']) --}}

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=chair" />
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 10
        }

        .material-symbols-outlined-hover {
            font-variation-settings:
                'FILL' 1;
            color: lightblue;
        }

        .material-symbols-outlined-filled {
            font-variation-settings:
                'FILL' 1;
            color: #126985ad;
        }
    </style>

    <style>
        body {
            background-color: #f5f8fa;
        }

        .topnav-header {
            position: fixed;
            width: 100%;
            z-index: 1000;
            height: 60px;
            top: 0;
        }

        #layoutSidenav {
            height: calc(100vh - 60px);
            margin-top: 50px; 
        }

        .card {
            box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);
            border: none;
            border-radius: 5px;
        }

        .nav-item {
            padding-block: 10px;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh !important;
            background-color: #343a40;
            /* Dark background */
            padding: 15px;
        }

        /* Default Link Styles */
        .sidebar .nav-link {
            color: white;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        /* Hover Effect */
        .sidebar .nav-link:hover {
            background-color: #495057;
            /* Slightly lighter dark */
            color: #f8f9fa;
            /* Light text */
        }

        /* Active Effect */
        .sidebar .nav-link.active {
            background-color: #495057;
            /* Bootstrap Primary Blue */
            color: white;
            font-weight: bold;
        }

        .main-container {
            margin-left: 250px;
            width: 100%;
            overflow-y: scroll;
            padding-block: 60px;
        }
    </style>
    @yield('styles')
</head>


<body>

    <!-- ======= Header ======= -->
    @include('layouts.partials.header')
    <!-- End Header -->

    <div id="layoutSidenav" class="d-flex">

        <!-- ======= Sidebar ======= -->
        @include('layouts.partials.sidebar')
        <!-- End Sidebar-->

        <div class="main-container">
            <main class="main">
                <div class="container-fluid">

                    @include('includes.messages')

                    @yield('content')
                </div>
            </main>
        </div>

    </div>

</body>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
@yield('scripts')

</html>
