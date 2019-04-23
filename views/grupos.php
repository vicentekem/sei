<?php

if(!isset( $_SESSION["usuario"] )){
    header('location:?url=login');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "partials/head.php"; ?>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php
    include "partials/navbar.php";
    include "partials/menu.php";
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">----</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content w-100">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="card card-primary w-100">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Grupos</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <div class="card-body row">

                            <div class="form-group col-12 input-group ">
                                <input type="search" id="txt_buscar_grupo" placeholder="Buscar" class="form-control" oninput="buscarGrupo()" />
                                <span class="input-group-append">
                                    <button class="btn btn-info btn-flat" onclick="buscarGrupo()" >Buscar</button>
                                </span>
                            </div>

                            <div class="form-group col-12">
                                <button class="btn btn-success" onclick="openModal()" ><span class="fa fa-plus"></span>Nuevo</button>
                            </div>

                            <hr class="my-4">

                            <div class="col-12" id="">
                                <div class="table-responsive w-100 text-sm" id="lista_grupos">

                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <!-- /.row -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <!--    <aside class="control-sidebar control-sidebar-dark"></aside>-->
    <!-- /.control-sidebar -->

</div><!-- ./wrapper -->

<div class="modal fade" id="grupoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Mantenimiento Grupo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" id="formGrupo">
                    <input type="hidden" id="action" value="">
                    <input type="hidden" id="txt_id_grupo" value="">
                    <div class="form-group col-12">
                        <label for="txt_desc">Descripción <span class="text-danger">(*)</span> </label>
                        <input type="text" class="form-control" id="txt_desc" placeholder="Descripción de la grupo">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formGrupo" onclick="guardarGrupo(event)" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php include "partials/scripts.php"; ?>
<script src="public/js/grupos.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initJsGrid();
    });
</script>

</body>
</html>
