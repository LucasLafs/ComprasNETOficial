<?php
require_once("../header/cabecalho.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Fornecedores</h1>
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
                        <div class="card-header" style="min-height: 50px;">
                            <div class="alert alert-success" role="alert">
                                <i class='fa fa-check-circle text-green'></i>
                            </div>
                            <div class="card-tools">
                                <button class="btn btn-tool" data-toggle="modal" data-target="#modalCadastroFabri" title="Adicionar Fornecedor">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id=tblFornecedores"
                                   class="table table-condesed table-responsive vertical-align" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Contato</th>
                                </tr>
                                </thead>
                                <tbody>
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
        <?php
            include ('modais/cadastroFabricante.php');
            include ('modais/editarFabricante.php');
            include ('modais/exclusao.php');
        ?>
    </section>
</div>
<!-- /.box -->
