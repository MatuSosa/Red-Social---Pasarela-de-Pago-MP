<?php 
  
  if(isset($_SESSION['unique_id'])){
    header("location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Ingresar - Edu-Connect</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">


    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/login/util.css">
    <link rel="stylesheet" type="text/css" href="../css/login/main.css">

    <!-- Icons FontAwesome 4.7.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"  type="text/css" />


<body>
<div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <a href="login.php"><img src="../images/logo.png" alt="500px"></a>
                </div>
    <section class="form login">
    <span class="login100-form-title">
                        Ingreso
                    </span>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="login100-form validate-form">
        <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="error-text"></div>

                    <div class="field button container-login100-form-btn" >
                    <input class="login100-form-btn" type="submit" name="submit" value="Ingresar">
                    </div>
                    </form>
      <div class="text-center p-t-12">
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="../vistas/index.php">
                            Crear una cuenta
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="js/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/tilt.jquery.min.js"></script>
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
  
  <script src="../js/pass-show-hide.js"></script>
  <script src="../js/login.js"></script>

</body>
</html>
