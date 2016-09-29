@section("styles")
    <link href="/css/all.css" rel="stylesheet">
@endsection

@section("scripts")
    <script src="/js/all.js"></script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Email validator</title>
    <!-- Bootstrap -->
    @yield("styles")
</head>
<body>
<div class="container">
    <div class="jumbotron">
        @yield("content")
    </div>
</div>
@yield("scripts")
</body>
</html>