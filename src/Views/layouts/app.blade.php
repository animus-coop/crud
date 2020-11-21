<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>CMS</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('cms/assets/css/daterangepicker.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('cms/assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('cms/assets/css/components.css')}}">
</head>

<body>
<div id="app">
  <div class="main-wrapper main-wrapper-1">
    <div class="navbar-bg"></div>
    <nav class="navbar navbar-expand-lg main-navbar">
      <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
          <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
          </li>
          <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                      class="fas fa-search"></i></a></li>
        </ul>
      </form>
      <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{asset('cms/assets/img/avatar/avatar-1.png')}}"
                 class="rounded-circle mr-1">
                 <div class="d-sm-none d-lg-inline-block">Bienvenido, {{ Auth::user()->name  }}</div>
          </a>
          <div class="dropdown-menu dropdown-menu-right">

              <form id="logout" method="POST" action="{{ route('logout') }}">
                  @csrf
              </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout').submit();" class="dropdown-item has-icon text-danger">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <div class="main-sidebar">
      <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="index.html">CMS</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
          <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
          @foreach(config('animus-crud-generator.menu') AS $group)
                <li class="menu-header">{{$group['group']}}</li>
                 @foreach($group['items'] AS $item)
                     @if (isset($item['subitems']))
                        <li class="dropdown active">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>{{ $item['label'] }}</span></a>
                            <ul class="dropdown-menu" style="display: none;">
                                @foreach($item['subitems'] AS $subitem)
                                <li><a class="nav-link" href="{{ route($subitem['route']) }}">{{ $subitem['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                     @else
                      <li class="{{ Request::route()->getName() == $item['route'] ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route($item['route']) }}">
                          <i class="{{ $item['icon'] }}"></i>
                          <span>{{ $item['label'] }}</span>
                        </a>
                      </li>
                    @endif
                @endforeach
            @endforeach
        </ul>

      </aside>
    </div>

    <!-- Main Content -->
    <div class="main-content">

      @yield('content')


    </div>
    <footer class="main-footer">

    </footer>
  </div>
</div>

<!-- General JS Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="{{asset('cms/assets/js/stisla.js')}}"></script>
<script src="{{asset('cms/assets/js/daterangepicker.js')}}"></script>

<!-- JS Libraies -->

<!-- Template JS File -->
<script src="{{asset('cms/assets/js/scripts.js')}}"></script>
<script src="{{asset('cms/assets/js/custom.js')}}"></script>

<!-- Page Specific JS File -->
</body>
</html>
