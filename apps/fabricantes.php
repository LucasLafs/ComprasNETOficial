<?php
require_once("../header/cabecalho.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Fabricantes</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class='col-md-12'>
                    <!-- Default box -->
                    <div class="card">
                      <div class="card-header" style="min-height: 30px; border-bottom: none !important;">
                        <div class="alert alert-success" role="alert">
                          <i class="fa fa-check-circle text-green"></i>
                        </div>

                      </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <button style="display: none; margin-top: 0; float: right;" class="btn btn-tool" id="btnCadastrarFabricante" data-toggle="modal" data-target="#modalCadastroFabri" title="Adicionar Fabricante">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <h4 id='msgSemFabricante' style='display: none;' class='text-info text-center'>Nenhum fabricante cadastrado.</h4>
                                    <table id="tblFabricantes" class="table table-hover table-responsive text-center" style="width: 100%; display: none; ">
                                        <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">E-mail</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                        <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer">
                          </div> -->
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <?php
        include ('modais/cadastroFabricante.php');
        include ('modais/editarFabricante.php');
        include ('modais/exclusao.php');
        ?>
    </section>
</div>
<!-- /.box -->

<script>

    $(document).ready(function() {

        /* Imports  */

        var fabricantes = document.createElement('script');
        fabricantes.src = '../js/fabricantes.js';
        document.head.appendChild(fabricantes);


        $(window).on('load', function () {
            getFabris();
            $('.tab1-loading').hide();
        });

        $(function () {
            $(".menu-open").find('.menu-geral').find('a[href="./fabricantes.php"]').addClass('active');
        });

    });
</script>
