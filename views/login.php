<?php

if(isset( $_SESSION["usuario"] )){
    header('location:?url=inicio');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="public/css/util.css">
    <link rel="stylesheet" type="text/css" href="public/css/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/main.css">

</head>

<body style="background-color: #666666;">
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">

            <form action="controllers/UsuarioController.php" class="login100-form validate-form" method="post" id="form_login">

                <input type="hidden" name="action" value="login">

                <span class="p-b-43" style="background: #FFF;margin-bottom: 2em;display: block;">
						<img class="w-full" src="public/images/ministerio.png" alt="diris">
                        <span style="text-align: left;" >DIRECCIÓN DE REDES INTEGRADAS DE SALUD LIMA CENTRO</span>
                </span>

                <span class="login100-form-title p-b-43">
						LOGIN
                </span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="usuario" id="txt_usuario">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Usuario</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="clave" id="txt_pass">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Contraseña</span>
                </div>

                <div class="container-login100-form-btn my-4">
                    <button class="login100-form-btn" onclick="validate(event)">
                        Ingresar
                    </button>
                </div>

            </form>

            <div class="login100-more" style="background-image: url('public/images/bg-01.jpg');">
                <div style="width: 100%;height: 100%;background: rgba(100,100,100,.5);">

                </div>
            </div>

        </div>
    </div>
</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/js/toastr.min.js"></script>
<script src="public/js/my_functions.js"></script>
<script src="public/js/main.js"></script>

<script>

    function validate(e) {

        e.preventDefault();

        var usuario = document.querySelector("#txt_usuario").value.trim();
        var pass = document.querySelector("#txt_pass").value.trim();

        var errorMessage = "";

        if (usuario === "") errorMessage = "Ingrese su usuario";
        else if (pass === "") errorMessage = "Ingrese su contraseña";

        if (errorMessage !== "") return showMessage(errorMessage, "error");
        document.querySelector("#form_login").submit();
    }

</script>

</body>
</html>




















