<?php ob_start(); ?>
<!DOCTYPE html>
<html>

    <head>
    
        <meta charset="utf-8">
        <title>Registro</title>
        
    </head>
    
    <body>
        <?php
            require '../comunes/comunes.php';
            
            if (count($_POST) != 0)
            {
                $con = conectar();
                
                $usuario = $_POST["usuario"];
                $cont = $_POST['password'];
                $contr = $_POST['rcont'];
                $email = $_POST['email'];
                
                $errores = comprobar_formulario($usuario, $email, $cont, $contr);
                
                if ($errores[3] == 0)
                {
                $res = pg_query($con, "insert into usuarios (usuario, pass, email)
            	                        values ('$usuario', md5('$cont'), '$email')");
            	}
            }
            else
            {
                $usuario = $email = $cont = "";
                $errores = array("", "", "");
            }
        
            ob_end_flush();
        ?>
    
        <form action="registro.php" method="post">
            <label for="usuario">Usuario*: </label>
            <input type="text" name="usuario" value="<?= $usuario ?>"/><?= $errores[0] ?><br/>
            <label for="email">E-mail*: </label>
            <input type="email" name="email" value="<?= $email ?>" /><?= $errores[1] ?><br/>
            <label for="cont">Contraseña*: </label>
            <input type="password" name="cont" value="<?= $cont ?>" /><br/>
            <label for="rcont">Repetir contraseña*: </label>
            <input type="password" name="rcont" /><?= $errores[2] ?><br/>
            <input type="submit" value="Registrar" />
        </form>
            
    </body>
    
</html>