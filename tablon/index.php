<?php session_start(); ?>
<!DOCTYPE html>
<html>

    <head>
    
        <meta charset="utf-8">
        <title><?= $_SESSION['usuario'] ?></title>
        <link rel="stylesheet" href="" type="text/css" href="../estilo/estilo.css"/>
        
    </head>
    
    <body><?php
        
            require '../comunes/comunes.php';
            
            encabezado();
            
            $con = conectar();
            $usuario = $_SESSION['usuario'];
            
            $res = pg_query($con, "select id
                                    from usuarios
                                    where usuario = '$usuario'");
            $fila =pg_fetch_array($res, 0);
            $id = $fila['id'];
            
            if (count($_POST) != 0)
            {
            	$pio = $_POST['piar'];
            	
            	$res = pg_query($con, "insert into pios (pio, usuarios_id)
            	                        values ('$pio', $id)");
            }
            
            $res =pg_query($con, "select usuario, pio, fecha from pios_usuarios_v
                                   where usuario = '$usuario'
                                   union
                                   select nombre, pio, fecha_cre from seguidos_v
                                   where seguidor_id = $id
                                   order by fecha desc");
            
            $num_rows = pg_num_rows($res);
            
            if ($num_rows > 0)
            { ?>
                <h1 align="center">TIMELINE</h1>
                <h2 align="center"><?= $usuario ?></h2> 
                <br/><br/>
                <div align="center" id="pio"></div>
                    <form action="index.php" method="post" >
                        <textarea maxlength="140" rows="4" cols="40" name="piar" ></textarea><br/>
                        <input type="submit" value="Enviar" />
                    </form>
                </div>
                <br/><br/>
                
                <div align="center" id="pios"><?php
                
                for ($i = 0; $i < $num_rows; $i++)
                {
                    $fila = pg_fetch_array($res, $i);
                    $pio = $fila['pio'];
                    $fecha = $fila['fecha'];
                    $usuario_p = $fila['usuario']; ?>
                    <h3><?= $usuario_p ?></h3>
                    <p><?= $pio ?></p>
                    <?= $fecha ?>
                    <br/><br/>
                    <?php
                }
            }
            else
            { ?>
                <p>Aún no tienes pios propios!</p> <?php
            } 
            
            // $res = pg_query($con, "select usuario, id 
                                   // from seguidos_v
                                   // where seguidor_id = (select id
                                                        // from usuarios
                                                        // where usuario = '$usuario')");
            // $num_rows = pg_num_rows($res);
            
            
            
            
            pg_close();
        
        ?>    
    </body>
    
</html>