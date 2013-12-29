<?php

    function conectar()
    {
        return pg_connect("host=softcaos.heliohost.org port=5432 dbname=josecaos_dbpiolin user=josecaos_piolin password=piolin");
    }
    
    function encabezado($top = false)
    {
        $pre = ($top) ? '' : '../';
        
        if (!isset($_SESSION['usuario'])): 
          header("Refresh: 2;url={$pre}usuarios/login.php"); ?>
          <p>No te dejo entrar</p><?php          
          die();
        endif; 
        
        $usuario = $_SESSION['usuario']; ?>
        
        <form action="<?= $pre ?>logout.php" method="post">
          <p align="right">Usuario: <?= $usuario ?>
          <input type="submit" value="Cerrar" />
          </p>
        </form> <?php
        
        $con = conectar();
        
        $res = pg_query($con, "select * from relaciones
                                where seguidor_id = (select id from usuarios where usuario = '$usuario')");
        $seguidos = pg_num_rows($res);                        
        
        $res = pg_query($con, "select * from relaciones
                                where seguido_id = (select id from usuarios where usuario = '$usuario')");
        $seguidores = pg_num_rows($res); ?>
        
        <p align="right">Sigues a: <a href="../tablon/seguidos.php"><?= $seguidos ?></a> | Te siguen: <a href="../tablon/seguidores.php"><?= $seguidores ?></a></p>
        <hr/><?php
        
        pg_close($con);
    }



    // VALIDACIONES DE FORMULARIO DE REGISTRO DE USUARIO
    
    function comprobar_usuario_valido($usuario)
    {
        $con = conectar();
        
        $res = pg_query($con, "select * from usuarios where usuario = '$usuario'");
        
        $num_rows = pg_num_rows($res);
        
        if ($num_rows != 0)
        {
            return "<p style='color:red;'>Usuario no válido</p>";
        }
        else
        {
            return "";
        }
        
        pg_close($con);
    }
    
    function comprobar_email_valido($email)
    {
        $con = conectar();
        
        $res = pg_query($con, "select * from usuarios where email = '$email'");
        
        $num_rows = pg_num_rows($res);
        
        if ($num_rows != 0)
        {
            return "<p style='color:red;'>Email no válido</p>";
        }
        else
        {
            return "";
        }
                
        pg_close($con);
    }

    function comprobar_cont_valida($cont, $contr)
    {
        if (strcmp($cont, $contr) != 0)
        {
            return "<p style='color:red;'>Las contraseñas no coinciden</p>";
        }
        else
        {
            return "";
        }
    }
    
    function comprobar_formulario($usuario, $email, $cont, $contr)
    {
        $error_usuario = comprobar_usuario_valido($usuario);
        $error_email = comprobar_email_valido($email);
        $error_cont = comprobar_cont_valida($cont, $contr);
        $contador = 0;  // Contador de caracteres
        
        $errores = array ($error_usuario, $error_email, $error_cont);
        
        for ($i = 0; $i < count($errores); $i++)
        {
            $contador += strlen($errores[$i]);
        }
        
        $errores[] = $contador;
        
        return $errores;
        
    }

?>