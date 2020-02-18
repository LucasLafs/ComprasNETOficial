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
                        <li class="breadcrumb-item"><a href="./dashboard.php">Dashboard</a></li>
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
                         <div class="card-header" style="border-bottom: none !important; margin-bottom: -30px;">
                           <div class="alert alert-success alert-sincronismo" role="alert" style="max-width: 240px; margin-bottom: 15px;">
                             <i class="fa fa-check-circle text-green">&nbsp;Sincronismo realizado com sucesso.</i>
                           </div>
                          </div>
                        <div class="card-body">
                            <div class='row'>
                                <div class='col-md-12'>
                                    <button style="display: none; margin-top: 0; float: right;" class="btn btn-tool" id="btnForceSincronismo" onClick='getLicGerais();' title="Forçar sincronismo">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                    <table id="table-data-licitacoes" class="table table-responsive table-hover vertical-align text-center" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col" class="vertical-align"></th>
                                                <th scope="col">UASG</th>
                                                <th scope="col">Data Entrega</th>
                                                <th scope="col">Descrição</th>
                                                <th scope="col">Objeto</th>
                                                <th scope="col">Situação</th>
                                                <th scope="col">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id='licitacao-itens'>
                                            <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                            <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                            <!--  <div class="tab1-loading overlay"></div>
                                    <div class="tab1-loading loading-img"></div>-->
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
    </section>
</div>
<!-- /.box -->
<script>
    $(document).ready(function() {

        /* Imports  */

        var cotacoes = document.createElement('script');
        cotacoes.src = '../js/cotacoes.js';
        document.head.appendChild(cotacoes);

        $(window).on('load', function() {
            getCotacoes();
            getLicGerais();
            $('.tab1-loading').hide();
            $("#loadingAllEmails").hide();

        });

        $(function() {
            $(".menu-open").find('.menu-geral').find('a[href="./cotacoes.php"]').addClass('active');
        });

    });
</script>
