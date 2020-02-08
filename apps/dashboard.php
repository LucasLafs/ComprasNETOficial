<?php

require_once("../header/cabecalho.php");

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Cotações Gerais</li>
                    </ol>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Cotacoes Vigentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Cotações dos estados principais</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Cotacoes recomendadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Cotacoes recomendadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</div>

<script type="text/javascript">
    $(".menu-open").find('.menu-geral').find('a[href="./dashboard.php"]').addClass('active');
</script>