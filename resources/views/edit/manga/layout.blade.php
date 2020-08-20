@extends ('layout')

@section ('title')
    Edit &middot; {{ $manga->name }}
@endsection

@section ('custom_navbar_right')
@endsection

@section ('content')
    <div class="container mt-3">
        <div class="d-flex d-sm-none justify-content-center">
            <h3><b>Edit &middot; <a href="{{ URL::action('MangaController@show', [$manga]) }}">{{ $manga->name }}</a></b></h3>
        </div>
        <div class="d-none d-sm-flex justify-content-center">
            <h2><b>Edit &middot; <a href="{{ URL::action('MangaController@show', [$manga]) }}">{{ $manga->name }}</a></b></h2>
        </div>

        @include ('shared.success')
        @include ('shared.errors')

        <div class="row justify-content-center">
            <div class="col-12 col-md-3">
                @yield('side-top-menu')
            </div>
            <div class="col-12 col-md-9 mt-3">
                @yield('tab-content')
            </div>
        </div>
    </div>
@endsection