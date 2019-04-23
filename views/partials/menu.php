<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">DIRIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">  <?php echo $_SESSION["usuario"]["nombre_completo"]; ?> </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php


                if (isset($_SESSION["usuario"])) {

                    require_once "config/Conexion.php";
                    require_once "models/MenuRepository.php";

                    $repository = new MenuRepository();

                    $result = $repository->getMenuUrlsForUser($_SESSION["usuario"]["id_usuario"]);

                    if (count($result["rows"]) > 0) {

                        foreach ($result["rows"] as $row) {

                            ?>

                        <li class="nav-item has-treeview ">
                            <a href="#" class="nav-link">
                                <i class="nav-icon <?php echo $row["bar_img"]; ?>"></i>
                                <p>
                                    <?php echo $row["pad_name"]; ?>
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <?php

                                $result2 = $repository->getSubMenuUrlsByMenu($row["pad_menu"],$_SESSION["usuario"]["id_usuario"]);

                                if (count($result2["rows"]) > 0) {

                                    foreach ($result2["rows"] as $row2) {

                                ?>

                                        <li class="nav-item">
                                            <a href="?url=<?php echo $row2["alias"]; ?>" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?php echo $row2["bar_name"] ?></p>
                                            </a>
                                        </li>

                                <?php
                                    }
                                }
                                ?>

                            </ul>

                        </li>

                <?php
                        }
                    }
                }
                ?>


                <!-- url para salir -->

                <!--                <li class="nav-header">Salir</li>-->
                <li class="nav-item">
                    <a href="?url=logout" class="nav-link">
                        <i class="nav-icon fa fa-sign-out-alt"></i>
                        <p>Salir</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>