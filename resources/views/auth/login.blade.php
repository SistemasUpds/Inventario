
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UPDS Logín</title>
    <!-- Template Main CSS File -->
    <link href="{{ asset('css/login.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
    <style>
      @import url('https://fonts.googleapis.com/css?family=Quicksand&display=swap');
      *{
        margin:0;
        padding:0;
        box-sizing:border-box;
      }
      .alert{
        width:50%;
        margin:20px auto;
        padding:30px;
        position:relative;
        border-radius:5px;
        box-shadow:0 0 15px 5px #ccc;
      }
    </style>
</head>
<body style="background-color: #003399">
<div class="container">
          @if(session('success'))
            <div class="alert simple-alert">
              <h5>{{ session('success') }}</h5>
            </div>            
          @endif
  <form method="POST" action="{{ url('/login') }}">
    {{ csrf_field() }}
    <h1>UPDS INVENTARIO</h1>
    <div class="form-group">
      <select class="form-control" name="departamento" id="departamento">
        <option value="PO">Potosí</option>
        <option value="SU">Sucre</option>
        <option value="TA">Tarija</option>
        <option value="LP">La Paz</option>
        <option value="CB">Cochabamba</option>
        <option value="OR">Oruro</option>
        <option value="SC">Santa Cruz</option>
        <option value="PA">Pando</option>
        <option value="BE">Beni</option>
      </select>
      <label for="select" class="control-label">Departamento</label><i class="bar"></i>
    </div>
    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
      <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus required="required"/>
      <label for="input" class="control-label">E-mail</label><i class="bar"></i>
      @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
      <input id="password" type="password" name="password" required="required"/>
      <label for="input" class="control-label">Contraseña</label><i class="bar"></i>
        @if ($errors->has('password'))
          <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
          </span>
        @endif
    </div>
      <div class="button-container">
        <button type="submit" class="button"><span>Iniciar Sesión</span></button>
      </div>
        @if (Route::has('password.request'))
          <!--a class="btn btn-link" href="{{ url('/password/reset') }}">
            {{ __('¿Olvidaste tu contraseña?') }}
          </a-->
        @endif
  </form>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div style="color: black" class="copyright">
      &copy; <strong><span>{{date('Y')}}</span></strong> | Diseño desarrollo <a style="color: black" href="#">UPDS POTOSÍ</a>
    </div>
  </footer>
  <!-- End Footer -->
</div>
<script>
  $(".close").click(function() {
    $(this)
      .parent(".alert")
      .fadeOut();
  });
</script>
</body>
</html>