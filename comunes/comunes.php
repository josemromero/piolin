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
        
        <p align="right">Sigues a: <?= $seguidos ?> | Te siguen: <?= $seguidores ?></p>
        <hr/><?php
        
        pg_close();
    }


?>