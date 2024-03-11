<!DOCTYPE html>
<html>

<head>
  <title>@lang('app.marketplace_down')</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <style>
    .content {
      display: flex;
      position: relative;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      top: 135px;
    }

    .title {
      font-size: 21px;
      margin-top: 20px;
      margin-bottom: 40px;
    }

    .brand-logo {
      max-width: 140px;
      max-height: 50px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="content">
      <img src="{{ get_logo_url('system', 'full') }}" class="brand-logo" alt="LOGO" title="LOGO" />
      <div class="title">@lang('app.marketplace_down')</div>
    </div>
  </div>
</body>

</html>
