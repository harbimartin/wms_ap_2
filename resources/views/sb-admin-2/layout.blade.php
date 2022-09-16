<!DOCTYPE html>
<html lang="en">
<head>
<!-- head -->
@include('sb-admin-2.head')
</head>

<body>

    <div id="wrapper">
        
        <!-- nav --> 
        @include('sb-admin-2.nav')

        <!-- content -->
        @yield('content')

    </div>

    <!-- javascript -->
    @include('sb-admin-2.javascript')

</body>

</html>
