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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- @vite(['resources/js/app.js', 'resources/css/app_output.css']) --}}

    <style>
        .nav-item {
            padding-block: 10px;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40; /* Dark background */
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
            background-color: #495057; /* Slightly lighter dark */
            color: #f8f9fa; /* Light text */
        }

        /* Active Effect */
        .sidebar .nav-link.active {
            background-color: #0d6efd; /* Bootstrap Primary Blue */
            color: white;
            font-weight: bold;
        }
    </style>
</head>


<body>

    <!-- ======= Header ======= -->
    @include('layouts.partials.header')
    <!-- End Header -->

    <div id="layoutSidenav" class="d-flex">

        <!-- ======= Sidebar ======= -->
        @include('layouts.partials.sidebar')
        <!-- End Sidebar-->

        <div class="w-100" style="padding-top: 40px;">
            <main class="main">
                <div class="container-fluid">

                    @include('includes.messages')

                    @yield('content')
                </div>
            </main>
        </div>

    </div>

</body>

</html>
