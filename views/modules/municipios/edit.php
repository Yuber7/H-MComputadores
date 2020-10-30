<?php
require("../../partials/routes.php");
require("../../../app/Controllers/MunicipiosController.php");

use App\Controllers\MunicipiosController;

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar Municipios</title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Editar o Actualizar un Municipio</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/">Municipios</a></li>
                            <li class="breadcrumb-item active">Inicio</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if (!empty($_GET['respuesta'])) { ?>
                <?php if ($_GET['respuesta'] != "correcto") { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        Error al editar el Municipio: <?= $_GET['mensaje'] ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información del Municipio</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="create.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                </div>
                            </div>

                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php
                                $DataMunicipios = MunicipiosController::searchForID($_GET["id"]);
                                if (!empty($DataMunicipios)) {
                                    ?>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" method="post" id="frmEditMunicipios"
                                      name="frmEditMunicipios"
                                      action="../../../app/Controllers/MunicipiosController.php?action=edit">

                                    <input id="id" name="id" value="<?php echo $DataMunicipios->getId(); ?>" hidden
                                           required="required" type="text">

                                    <div class="form-group row">
                                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="nombre" name="nombre"
                                                   placeholder="Ingrese el nombre del Municipio"
                                                   value="<?php echo $DataMunicipios->getNombre(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="departamento_id" class="col-sm-2 col-form-label">Region</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="departamento_id" name="departamento_id"
                                                   placeholder="Ingrese el nombre del departamento y el id"
                                                   value="<?php echo $DataMunicipios->getDepartamentoId()->getNombre(); ?> <?php echo $DataMunicipios->getDepartamentoId()->getId(); ?>">
                                        </div>
                                    </div>

                                        <div class="form-group row">
                                            <label for="acortado" class="col-sm-2 col-form-label">Acortado</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="acortado" name="acortado"
                                                       placeholder="Ingrese el acortado del municipio"
                                                       value="<?php echo $DataMunicipios->getAcortado(); ?>">
                                            </div>
                                        </div>

                                    <div class="form-group row">
                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <select id="estado" name="estado" class="custom-select">
                                                <option <?= ($DataMunicipios->getEstado() == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                <option <?= ($DataMunicipios->getEstado() == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <button type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    <!-- /.card-footer -->
                                </form>

                            <!-- /.card-body -->
                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php } ?>
                                </p>
                            <?php } ?>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
</body>
</html>
