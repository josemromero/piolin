<?php session_start(); ob_start(); ?>
<!DOCTYPE html>
<html>

    <head>
    
        <meta charset="utf-8">
        <title>Bienvenido</title>
        
    </head>
    
    <body><?php
    		
        
            require 'comunes/comunes.php';
            
            $usuario = (isset($_POST['usuario'])) ? trim($_POST['usuario']) : '';
            $password = (isset($_POST['passwd'])) ? trim($_POST['passwd']) : '';
            
            if ($usuario != '' && $password != '')
            {
                $con = conectar();
                $res = pg_query($con, "select *
                                        from usuarios
                                        where usuario = '$usuario' and
                                              pass = md5('$password')");
                $num_rows = pg_num_rows($res);
                pg_close();
                
                if ($num_rows > 0)
                {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: tablon/index.php");
                }
                else
                { ?>
                    
                    <script languaje="javascript">
                        alert("Usuario no encontrado");
                    </script>
                    
                    <?php
                }
            }
        	ob_end_flush();
        ?>        
        <h1>PIOLIN</h1>
        
        <form action="index.php" method="post">
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" size="10" /><br/>
            <label for="passwd">Contraseña: </label>
            <input type="password" name="passwd" size="10" /><br/>
            <input type="submit" value="loggin" />
        </form>
        <br/><br/>
        <a href="registro/registro.php">Hazte una cuenta</a>
        
    </body>
    
</html>
