<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf8"/>
    <title>Dejar de seguir</title>
  </head>
  <body><?php
    require '../comunes/comunes.php';
    
    encabezado();
    
    $con = conectar();
    
    if (isset($_GET['usuario']))
    {
      $usuario = trim($_GET['usuario']);
      if ($usuario != "")
      {
        $res = pg_query($con, "select *
                                 from usuarios
                                where id::text = '$usuario'");

        if (pg_num_rows($res) > 0)
        {
          $fila = pg_fetch_array($res, 0);
          $id = $fila['id'];
          $seguido = $fila['usuario'];

          if ($id != $_SESSION['usuario_id'])
          {
            $res = pg_query($con, "select *
                                     from relaciones
                                    where seguidor_id = {$_SESSION['usuario_id']}
                                          and seguido_id = $id");
            if (pg_num_rows($res) == 0): ?>
              <h2>No seguías a <?= $seguido ?></h2><?php
            else:
              $res = pg_query($con, "delete from relaciones
                                    where seguidor_id = {$_SESSION['usuario_id']}
                                      and seguido_id = $id");
                                             
              if (pg_affected_rows($res) > 0): ?>
                <h2>A partir de ahora, ya no sigues a <?= $seguido ?></h2><?php
              endif;
            endif;
          }
        }
      }
    }
    pg_close($con); ?>
  </body>
</html>