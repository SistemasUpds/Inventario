
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UPDS Logín</title>
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
  <style>
    /* 'Open Sans' font from Google Fonts */
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

    body {
      background: rgb(0, 74, 148);
      font-family: 'Open Sans', sans-serif;
    }

    .login {
      width: 400px;
      margin: 16px auto;
      font-size: 16px;
    }

    /* Reset top and bottom margins from certain elements */
    .login-header,
    .login p {
      margin-top: 0;
      margin-bottom: 0;
    }

    /* The triangle form is achieved by a CSS hack */
    .login-triangle {
      width: 0;
      margin-right: auto;
      margin-left: auto;
      border: 12px solid transparent;
      border-bottom-color: #28d;
    }

    .login-header {
      background: #28d;
      padding: 20px;
      font-size: 1.4em;
      font-weight: normal;
      text-align: center;
      text-transform: uppercase;
      color: #fff;
    }

    .login-container {
      background: #ebebeb;
      padding: 12px;
    }

    /* Every row inside .login-container is defined with p tags */
    .login p {
      padding: 12px;
    }

    .login input {
      box-sizing: border-box;
      display: block;
      width: 100%;
      border-width: 1px;
      border-style: solid;
      padding: 16px;
      outline: 0;
      font-family: inherit;
      font-size: 0.95em;
    }

    .login input[type="email"],
    .login input[type="password"] {
      background: #fff;
      border-color: #bbb;
      color: #555;
    }

    /* Text fields' focus effect */
    .login input[type="email"]:focus,
    .login input[type="password"]:focus {
      border-color: #888;
    }

    .login input[type="submit"] {
      background: #28d;
      border-color: transparent;
      color: #fff;
      cursor: pointer;
    }

    .login input[type="submit"]:hover {
      background: #17c;
    }

    /* Buttons' focus effect */
    .login input[type="submit"]:focus {
      border-color: #05a;
    }
  </style>
</head>
<body>
    <div class="login">
        <div class="login-triangle"></div>
        <h2 class="login-header">UPDS DEPARTAMENTO</h2>
        <!-- resources/views/welcome.blade.php -->
        <form method="post" action="{{ route('seleccionar-departamento') }}">
            {{ csrf_field() }}
            <label for="departamento">Selecciona un departamento:</label>
            <select name="departamento" id="departamento">
                @foreach ($departamentos as $dept)
                    <option value="{{ $dept->sigla }}">{{ $dept->nombre }}</option>
                @endforeach
            </select>
            <button type="submit">Seleccionar</button>
        </form>
        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div style="color: black" class="copyright">
            &copy; <strong><span>{{date('Y')}}</span></strong> | Diseño desarrollo <a style="color: black" href="#">UPDS POTOSÍ</a>
            </div>
        </footer><!-- End Footer -->
    </div>
</body>
</html>