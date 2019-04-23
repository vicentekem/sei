<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<button type="button" onclick="enviar()"> prueba </button>

<script src="push.min.js"></script>
<script src="socket.io.js"></script>
<script>

    var socket = io.connect('http://10.0.1.94:8080');

    function enviar(){
        if (Notification.permission === "granted") {
            console.log("activado");
        }else{
            //Push.create("notificacion activada");
        }
        socket.emit("message",{mensaje:"Hola mundo"});
    }

    socket.on("message",function( data ){
        Push.create("notificacion enviada");
    });

</script>
</body>
</html>