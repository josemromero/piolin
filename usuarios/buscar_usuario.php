<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf8"/>
    <title>Seguidores</title>
  </head>
  <body><?php
    require '../comunes/comunes.php';
    encabezado();
    $con = conectar();
    
    function busqueda()
    { ?>
        <form action="buscar_usuario.php" method="post">
            <label for="criterio">Introduzca nombre o parte de el: </label>
            <input type="text" name="criterio" value="<?= $criterio ?>" />
            <input type="submit" value="Buscar" />
        </form>
        <br/><br/><?php
    }
    
    if (isset($_POST['criterio']))
    {
        $criterio = trim($_POST['criterio']);
        
        $trans = "'áéíóú', 'aeiou'";
        $res = pg_query($con, "select usuario from usuarios 
                                where translate(lower(usuario), $trans) like
                                translate(lower('%$criterio%'), $trans)");
        busqueda(); ?>
    
        <table border="0"> <?php
            
            if (pg_num_rows($res) > 0)
            {
                for ($i = 0; $i < pg_num_rows($res); $i++)
                {
                    $fila = pg_fetch_array($res, $i);
                    extract($fila); ?>
                    
                    <tr>
                        <td><?= $usuario ?></td>
                        <td><?php
                            
                            $ress = pg_query($con, "select * from usuarios where usuario = '$usuario'");
                            if (pg_num_rows($ress) > 0)
                            {
                                $fila = pg_fetch_array($ress);
                                $id = $fila['id'];
                            }
                            $ress = pg_query($con, "select * from relaciones
                                                 where seguidor_id = {$_SESSION['usuario_id']} and
                                                 seguido_id = $id");
                            if (pg_num_rows($ress) > 0)
                            { ?>
                                <a href="dejar_de_seguir.php?usuario=<?= $id ?>">Dejar de seguir</a><?php
                            }
                            else { ?>
                                <a href="seguir.php?usuario=<?= $id ?>">Seguir</a><?php
                            } ?>
                        </td>
                    </tr> <?php
                }
            }
            else { ?>
                <p>No se encontraron coincidencias</p><?php
            } ?>
        </table><?php
    }
    else {
        $criterio = "";
        busqueda();
    }
    pg_close($con); ?>
  </body>
</html>