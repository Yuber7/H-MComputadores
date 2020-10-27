<?php
require("../../partials/routes.php");
require("../../../app/Controllers/PersonaController.php");

use  App\Controllers\PersonaController;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar Persona</title>
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
                        <h1>Editar o Actualizar una Persona</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/">Persona</a></li>
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
                        Error al crear la persona: <?= $_GET['mensaje'] ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información de la persona</h3>
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
                                $DataPersona = PersonaController::searchForID($_GET["id"]);
                                if (!empty($DataPersona)) {
                                    ?>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" method="post" id="frmEditPersona"
                                      name="frmEditPersona"
                                      action="../../../app/Controllers/PersonaController.php?action=edit">

                                    <input id="id" name="id" value="<?php echo $DataPersona->getId(); ?>" hidden
                                           required="required" type="text">

                                    <div class="form-group row">
                                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="nombre" name="nombre"
                                                   placeholder="Ingrese su nombre"
                                                   value="<?php echo $DataPersona->getNombre(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="apellido"
                                                   name="apellido" placeholder="Ingrese su apellido"
                                                   value="<?php echo $DataPersona->getApellido(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tipoDocumento" class="col-sm-2 col-form-label">Tipo
                                            Documento</label>
                                        <div class="col-sm-10">
                                            <select id="tipoDocumento" name="tipoDocumento" class="custom-select">
                                                <option <?= ($DataPersona->getTipoDocumento() == "C.C") ? "selected" : ""; ?> value="C.C">Cedula de Ciudadania</option>
                                                <option <?= ($DataPersona->getTipoDocumento() == "T.I") ? "selected" : ""; ?> value="T.I">Tarjeta de Identidad</option>
                                                <option <?= ($DataPersona->getTipoDocumento() == "NIT") ? "selected" : ""; ?> value="NIT">Nit Empresa</option>
                                                <option <?= ($DataPersona->getTipoDocumento() == "C.E") ? "selected" : ""; ?> value="C.E">Cedula de Extranjeria</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                                        <div class="col-sm-10">
                                            <input required type="number" minlength="6" class="form-control"
                                                   id="documento" name="documento" placeholder="Ingrese su documento"
                                                   value="<?php echo $DataPersona->getDocumento(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="correo" class="col-sm-2 col-form-label">Correo Electonico</label>
                                        <div class="col-sm-10">
                                            <input required type="email" minlength="6" class="form-control"
                                                   id="correo" name="correo" placeholder="Ingrese su correo electonico"
                                                   value="<?php echo $DataPersona->getCorreo(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                        <div class="col-sm-10">
                                            <input required type="number" minlength="6" class="form-control"
                                                   id="telefono" name="telefono" placeholder="Ingrese su telefono"
                                                   value="<?php echo $DataPersona->getTelefono(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                                        <div class="col-sm-10">
                                            <select id="rol" name="rol" class="custom-select">
                                                <option <?= ($DataPersona->getRol() == "Proveedor") ? "selected" : ""; ?> value="Proveedor">Proveedor</option>
                                                <option <?= ($DataPersona->getRol() == "Cliente") ? "selected" : ""; ?> value="Cliente">Cliente</option>
                                                <option <?= ($DataPersona->getRol() == "Administrador") ? "selected" : ""; ?> value="Administrador">Administrador</option></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="direccion"
                                                   name="direccion" placeholder="Ingrese su direccion" value="<?php echo $DataPersona->getDireccion(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <select id="estado" name="estado" class="custom-select">
                                                <option <?= ($DataPersona->getEstado() == "activo") ? "selected" : ""; ?> value="activo">Activo</option>
                                                <option <?= ($DataPersona->getEstado() == "inactivo") ? "selected" : ""; ?> value="inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <button type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    <!-- /.card-footer -->
                                </form>
                            </div>
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
