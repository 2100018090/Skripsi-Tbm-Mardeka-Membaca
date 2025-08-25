<!doctype html>
<html lang="en" class="dark">
  <head>
    @include('user.layouts.partials.header')
  </head>
  @php
    $whiteBg = isset($params['white_bg']) && $params['white_bg'];
  @endphp

<body class="bg-white dark:bg-white">


  @yield('main')
  @include('user.layouts.partials.scripts')
</body>
</html>
