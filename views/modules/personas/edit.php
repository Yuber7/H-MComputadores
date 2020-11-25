<?php
require("../../partials/routes.php");
require("../../../app/Controllers/PersonasController.php");
require_once ("../../../app/Controllers/MunicipiosController.php");

use App\Controllers\MunicipiosController;
use  App\Controllers\PersonasController;

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
                        <h1>Editar o Actualizar la Persona</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/">Personas</a></li>
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
                        Error al editar la persona: <?= $_GET['mensaje'] ?>
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
                                $DataPersonas =PersonasController::searchForID($_GET["id"]);
                                if (!empty($DataPersonas)) {
                                    ?>

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                    <!-- form start -->
                                    <form class="form-horizontal" method="post" id="frmEditPersona"
                                          name="frmEditPersona"
                                          action="../../../app/Controllers/PersonasController.php?action=edit">

                                        <input id="id" name="id" value="<?php echo $DataPersonas->getId(); ?>" hidden
                                               required="required" type="text">

                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input required type="text" class="form-control" id="nombre" name="nombre"
                                                       placeholder="Ingrese el nombre" value="<?php echo $DataPersonas->getNombre(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                                            <div class="col-sm-10">
                                                <input required type="text" class="form-control" id="apellido" name="apellido"
                                                       placeholder="Ingrese el apellido" value="<?php echo $DataPersonas->getApellido(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tipo_documento" class="col-sm-2 col-form-label">Tipo
                                                Documento</label>
                                            <div class="col-sm-10">
                                                <select id="tipo_documento" name="tipo_documento" class="custom-select">
                                                    <option <?= ($DataPersonas->getTipo_documento() == "CC") ? "selected" : ""; ?> value="CC">Cedula de Ciudadania</option>
                                                    <option <?= ($DataPersonas->getTipo_documento() == "CE") ? "selected" : ""; ?> value="CE">Cedula de Extranjeria</option>
                                                    <option <?= ($DataPersonas->getTipo_documento() == "TI") ? "selected" : ""; ?> value="TI">Tarjeta de Identidad</option>
                                                    <option <?= ($DataPersonas->getTipo_documento() == "NIT") ? "selected" : ""; ?> value="NIT">Nit Empresa</option>
                                                    <option <?= ($DataPersonas->getTipo_documento() == "REG") ? "selected" : ""; ?> value="REG">Registro Civil</option>
                                                    <option <?= ($DataPersonas->getTipo_documento() == "PAS") ? "selected" : ""; ?> value="PAS">Pasaporte</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                                            <div class="col-sm-10">
                                                <input required type="number" minlength="6" class="form-control"
                                                       id="documento" name="documento" placeholder="Ingrese su documento" value="<?php echo $DataPersonas->getDocumento(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                            <div class="col-sm-10">
                                                <input required type="number" minlength="6" class="form-control"
                                                       id="telefono" name="telefono" placeholder="Ingrese su telefono" value="<?php echo $DataPersonas->getTelefono(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                                            <div class="col-sm-10">
                                                <select id="rol" name="rol" class="custom-select">
                                                    <option <?= ($DataPersonas->getRol() == "Administrador") ? "selected" : ""; ?> value="Administrador">Administrador</option>
                                                    <option <?= ($DataPersonas->getRol() == "Proveedor") ? "selected" : ""; ?> value="Proveedor">Proveedor</option>
                                                    <option <?= ($DataPersonas->getRol() == "Cliente") ? "selected" : ""; ?> value="Cliente">Cliente</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="municipio_id" class="col-sm-2 col-form-label">Municipio</label>
                                            <div class="col-sm-10">
                                                <?= MunicipiosController::selectMunicipios(false,
                                                    true,
                                                    'municipio_id',
                                                    'municipio_id',
                                                    (!empty($DataPersonas)) ? $DataPersonas->getMunicipioId()->getId() : '',
                                                    'form-control select2bs4 select2-info',
                                                    "estado = 'Activo'")
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="direccion" class="col-sm-2 col-form-label">Dirección</label>
                                            <div class="col-sm-10">
                                                <input required type="text" class="form-control"
                                                       id="direccion" name="direccion" placeholder="Ingrese su dirección" value="<?php echo $DataPersonas->getDireccion(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="email" class="col-sm-2 col-form-label">Correo Electronico</label>
                                            <div class="col-sm-10">
                                                <input required type="email" class="form-control"
                                                       id="email" name="email" placeholder="Ingrese su email" value="<?php echo $DataPersonas->getEmail(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="user" class="col-sm-2 col-form-label">Usuario</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control"
                                                       id="user" name="user" placeholder="Ingrese su Usuario" value="<?php echo $DataPersonas->getUser(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control"
                                                       id="password" name="password" placeholder="Ingrese una contraseña" value="<?php echo $DataPersonas->getPassword(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                            <div class="col-sm-10">
                                                <select id="estado" name="estado" class="custom-select">
                                                    <option <?= ($DataPersonas->getEstado() == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                    <option <?= ($DataPersonas->getEstado() == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
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
