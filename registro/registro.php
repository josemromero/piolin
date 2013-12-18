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
                
                if ($cont == $contr)
                {
                $res = pg_query($con, "insert into usuarios (usuario, pass, email)
            	                        values ('$usuario', md5('$cont'), '$email')");
            	}
            	else
            	{ ?>
            	    <p>Las contrase«Ğas no coinciden</p><?php
            	}
            }
            else
            {
                $usuario = $email = $cont = "";
            }
        
        ?>
    
        <form action="registro.php" method="post">
            <label for="usuario">Usuario*: </label>
            <input type="text" name="usuario" value="<?= $usuario ?>"/><br/>
            <label for="email">E-mail*: </label>
            <input type="email" name="email" value="<?= $email ?>" /><br/>
            <label for="cont">ContraseÃ±a*: </label>
            <input type="password" name="cont" value="<?= $cont ?>" /><br/>
            <label for="rcont">Repetir contraseÃ±a*: </label>
            <input type="password" name="rcont" /><br/>
            <input type="submit" value="Registrar" />
        </form>
            
    </body>
    
</html>