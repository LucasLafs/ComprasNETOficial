<?php
require_once("../header/cabecalho.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Cotações Gerais</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Cotações Gerais</li>
                    </ol>
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
                        <!-- <div class="card-header">
                            <h3 class="card-title">Cotações</h3>
                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fas fa-times"></i></button>
                            </div>
                          </div> -->
                        <div class="card-body">
                            <table id="table-data-licitacoes"
                                   class="table table-condesed table-responsive vertical-align" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">UASG</th>
                                    <th scope="col">Data Entrega</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Objeto</th>
                                    <th scope="col">Situação</th>
                                    <th scope="col" class="vertical-align">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                              <!--  <div class="tab1-loading overlay"></div>
                                <div class="tab1-loading loading-img"></div>-->
                                </tbody>
                            </table>
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
    </section>
</div>
<!-- /.box -->
<script>

$(document).ready(function() {

    /* Imports  */

    var DataTables = document.createElement('script');
    DataTables.src = '../js/DataTables.js';
    document.head.appendChild(DataTables);


    $(window).on('load', function () {
        getCotacoes();
        $('.tab1-loading').hide();

    });

    $(function () {
        $(".menu-open").find('.menu-geral').find('a[href="./cotacoes.php"]').addClass('active');
    });

});
</script>