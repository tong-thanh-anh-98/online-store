<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrative Panel</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- library for creating and managing text editors -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/summernote/summernote.min.css') }}">
    <!-- Image upload library  -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}">
    <!--   -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- navbar section -->
        @include('admin.layouts.inc.navbar')

        <!-- sidebar section -->
        @include('admin.layouts.inc.sidebar')

        <!-- contents section -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- footer section -->
        @include('admin.layouts.inc.footer')        
    </div>

    <!-- jQuery -->
    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    <!-- Library for creating and managing text editors  -->
    <script src="{{ asset('admin-assets/plugins/summernote/summernote.min.js') }}"></script>
    <!-- Image upload library -->
    <script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <!--  -->
    <script src="{{ asset('admin-assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('admin-assets/js/demo.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Dropzone.autoDiscover = false;

        // Summernote section
        $(document).ready(function () {
            $('.summernote').summernote({
                height: '250px'
            });
        });
    </script>

    @yield('customJs')

</body>
</html>