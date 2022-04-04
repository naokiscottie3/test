<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>admin_login_test</title>
</head>
<body>

    <p class="login-box-msg">ログインしてください。</p>

    <form action="{{ route('admin_sign_in') }}" method="post">
    @csrf

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <input name="email" type="email" class="form-control" placeholder="Email">

        <input name="password" type="password" class="form-control" placeholder="Password">

        <!-- /.col -->
        <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">ログイン</button>
        </div>
    </form>

</body>
</html>
