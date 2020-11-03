<?php
require("../../partials/routes.php");
require("../../../app/Controllers/DepartamentosController.php");

use App\Controllers\DepartamentosController;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar Departamento</title>
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
                        <h1>Editar o Actualizar un Departamento</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/">Departamentos</a></li>
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
                        Error al editar el departamento: <?= $_GET['mensaje'] ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información del departamento</h3>
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
                                $DataDepartamentos =DepartamentosController::searchForID($_GET["id"]);
                                if (!empty($DataDepartamentos)) {
                                    ?>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" method="post" id="frmEditDepartamento"
                                      name="frmEditDepartamento"
                                      action="../../../app/Controllers/DepartamentosController.php?action=edit">

                                    <input id="id" name="id" value="<?php echo $DataDepartamentos->getId(); ?>" hidden
                                           required="required" type="text">

                                    <div class="form-group row">
                                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="nombre" name="nombre"
                                                   placeholder="Ingrese el nombre del departamento"
                                                   value="<?php echo $DataDepartamentos->getNombre(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="region" class="col-sm-2 col-form-label">Region</label>
                                        <div class="col-sm-10">
                                            <select id="region" name="region" class="custom-select">
                                                <option <?= ($DataDepartamentos->getRegion() == "Caribe") ? "selected" : ""; ?> value="Caribe">Caribe</option>
                                                <option <?= ($DataDepartamentos->getRegion() == "Centro Oriente") ? "selected" : ""; ?> value="Centro Oriente">Centro Oriente</option>
                                                <option <?= ($DataDepartamentos->getRegion() == "Centro Sur") ? "selected" : ""; ?> value="Centro Sur">Centro Sur</option>
                                                <option <?= ($DataDepartamentos->getRegion() == "Eje Cafetero - Antioquia") ? "selected" : ""; ?> value="Eje Cafetero - Antioquia">Eje Cafetero - Antioquia</option>
                                                <option <?= ($DataDepartamentos->getRegion() == "Llano") ? "selected" : ""; ?> value="Llano">Llano</option>
                                                <option <?= ($DataDepartamentos->getRegion() == "Pacífico") ? "selected" : ""; ?> value="Pacífico">Pacífico</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <select id="estado" name="estado" class="custom-select">
                                                <option <?= ($DataDepartamentos->getEstado() == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                <option <?= ($DataDepartamentos->getEstado() == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
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
