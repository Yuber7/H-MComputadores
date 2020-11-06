<?php
require("../../partials/routes.php");
require("../../../app/Controllers/ProductosController.php");
require_once ("../../../app/Controllers/CategoriasController.php");

use App\Controllers\CategoriasController;
use App\Controllers\ProductosController;

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar Producto</title>
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
                        <h1>Editar o Actualizar el Producto</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/">Productos</a></li>
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
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información del producto</h3>
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
                                $DataProductos = ProductosController::searchForID($_GET["id"]);
                                if (!empty($DataProductos)) {
                                    ?>

                                    <!-- /.card-header -->
                                    <div class="card-body">
                                    <!-- form start -->
                                    <form class="form-horizontal" method="post" id="frmEditProducto"
                                          name="frmEditProducto"
                                          action="../../../app/Controllers/ProductosController.php?action=edit">

                                        <input id="id" name="id" value="<?php echo $DataProductos->getId(); ?>" hidden
                                               required="required" type="text">

                                        <div class="form-group row">
                                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input required type="text" class="form-control" id="nombre" name="nombre"
                                                       placeholder="Ingrese el nombre" value="<?php echo $DataProductos->getNombre(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="marca" class="col-sm-2 col-form-label">Marca</label>
                                            <div class="col-sm-10">
                                                <input required type="text" class="form-control" id="marca" name="marca"
                                                       placeholder="Ingrese la marca" value="<?php echo $DataProductos->getMarca(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="categoria_id" class="col-sm-2 col-form-label">Categoria</label>
                                            <div class="col-sm-10">
                                                <?= CategoriasController::selectCategorias(false,
                                                    true,
                                                    'categoria_id',
                                                    'categoria_id',
                                                    '',
                                                    'form-control select2bs4 select2-info',
                                                    "estado = 'Activo'")
                                                ?>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="referencia_fabrica" class="col-sm-2 col-form-label">Referencia</label>
                                            <div class="col-sm-10">
                                                <input required type="text"  class="form-control"
                                                       id="referencia_fabrica" name="referencia_fabrica" placeholder="Ingrese la referencia de fabrica"
                                                       value="<?php echo $DataProductos->getReferenciaFabrica(); ?>">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="descripcion" class="col-sm-2 col-form-label">Descripcion</label>
                                            <div class="col-sm-10">
                                                <textarea required class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese una descripción" rows="3"><?php echo $DataProductos->getDescripcion(); ?></textarea>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="stock" class="col-sm-2 col-form-label">Stock</label>
                                            <div class="col-sm-10">
                                                <input required type="number"  class="form-control"
                                                       id="stock" name="stock" placeholder="Ingrese el stock"
                                                        value="<?php echo $DataProductos->getStock(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                                            <div class="col-sm-10">
                                                <input required type="number"  class="form-control"
                                                       id="precio" name="precio" placeholder="Ingrese el precio del producto"
                                                         value="<?php echo $DataProductos->getPrecio(); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                            <div class="col-sm-10">
                                                <select id="estado" name="estado" class="custom-select">
                                                    <option <?= ($DataProductos->getEstado() == "Disponible") ? "selected" : ""; ?> value="Disponible">Disponible</option>
                                                    <option <?= ($DataProductos->getEstado() == "Agotado") ? "selected" : ""; ?> value="Agotado">Agotado</option>
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
