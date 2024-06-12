<?php 
    $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml'); 
    $ruta = 'http://'.$_SERVER['SERVER_NAME'];//.':'.$_SERVER['SERVER_PORT'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="/public/dist/img/XperienceUML.png" />
    <link rel="stylesheet" href="./locals/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="/dist/css/adminlte.css" />
    <!--script src="./locals/bootstrap.bundle.min.js"></script-->
    <title>Eliminar diagrama</title>
</head>
    
<body>
    <div>
        <section >
            <div class="row"  style="background-color:#f4f6f9">
                <div class="col-md-2" style="color:white;background-color:#4b545c;width:100%">
                    <a href="<?php echo $ruta?>" class="brand-link">
                        <img 
                            src="/public/dist/img/XperienceUML.png" 
                            alt="Logo" 
                            class="brand-image img-circle elevation-3" 
                            style="opacity: .8;"
                        >
                        <span class="brand-text font-weight-light" style="color:white">
                            <i>XPerienceUML</i>
                        </span>
                    </a>
                </div>
                <div class="col-md-10" style="width:100%">
                    <?php
                        if (isset($_GET["id"])){
                            $id = $_GET["id"];
                            $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
                            $result = mysqli_query($conexion, "update model_diagram_success set data='[]' where id='".$id."'");
                            if ($result){   //$conexion->query($ssql)
                                echo '<h1 style="color:green"><strong>Se ha eliminado el diagrama.</strong></h1>';
                            }else{
                                echo '<h1 style="color:red"><strong>Error: No se pudo eliminar el diagrama.</strong></h1>';
                            }
                            $conexion->close();
                        }

                    ?>
                    <a href="<?php echo $ruta;?>/public/index.php/model/diagram/success/" style="margin:15px">
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left-circle"></i>
                            <strong>Regresar</strong>
                        </button>
                    </a>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
