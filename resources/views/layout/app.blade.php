<!DOCTYPE html>
<html class="no-js" lang="fr">
  @include('layout/head')
  <body class="theme-black">
    @include('layout/nav')
    @yield('style')
    @yield('content')
    @include('layout/javascript')
    @yield('scripts')
  </body>
</html>