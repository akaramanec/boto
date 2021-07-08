<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Boto shop: main</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/starter-template.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('home') }}">Boto</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="{{ route('home') }}">Products</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Route::has('login'))
                    @auth
                        <li>
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                        </li>
                        @if (Route::has('register'))
                            <li>
                                <a href="{{ route('register') }}"
                                   class="ml-4 text-sm text-gray-700 underline">Register</a>
                            </li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="starter-template">
        <h1>Products</h1>
        @if($products != null)
            <div class="container">
                @foreach($products as $product)
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <div class="labels">
                            </div>
                            <img src="{{ $product->image }}" alt="{{ $product->name }}">
                            <div class="caption">
                                <h3>{{ $product->name }}</h3>
                                <p>{{ $product->price }} UHR</p>
                                <p>{{ $product->description }}</p>
                                @if($product->availability) <p> <b> available </b> @else <del> not available</del> @endif</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $products->links() }}
        @endif
    </div>
</div>
</body>
</html>
