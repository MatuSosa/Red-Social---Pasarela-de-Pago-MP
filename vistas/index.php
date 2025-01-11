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

    <title>Registro de usuarios - Edu-Connect</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">


    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/login/util.css">
    <link rel="stylesheet" type="text/css" href="../css/login/main.css">

    <!-- Icons FontAwesome 4.7.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"  type="text/css" />




</head>
<body>
    <div class="limiter">
    <section class="form signup">
        <div class="container-login100">
            <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                    <a href="login.php"><img src="../images/logo.png" alt=""></a>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="login100-form validate-form">   
                <span class="login100-form-title">
                        Registro de usuarios
                    </span>

                    <div class="wrap-input100 validate-input" data-validate = "Valid user is required">
                        <input class="input100" type="text" name="fname" placeholder="Nombre" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Valid user is required">
                        <input class="input100" type="text" name="lname" placeholder="Apellido" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Valid image is required">
                        <input class="input100" type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-image" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="error-text"></div> 
                    <div class="field button container-login100-form-btn" >
                    <input class="login100-form-btn" type="submit" name="submit" value="Registrarme">
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="login.php">
                         INGRESAR
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>

                <div class="login100-pic js-tilt" data-tilt>
                    <a href="login.php"><img src="images/logo.png" alt=""></a>
                </div>
            </div>
        </div>
    </section>
    </div>


    <script src="../js/pass-show-hide.js"></script>
  <script src="../js/signup.js"></script>
    <script src="js/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/tilt.jquery.min.js"></script>
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>



</body>
</html>