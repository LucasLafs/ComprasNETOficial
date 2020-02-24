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
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id='nCotacoesVigentes'>0</h3>
                            <p>Cotacoes Vigentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" id='' onclick='buscaCotacoes("vigentes");' class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3 style="color: white">0</h3>
                            <p style="color: white">Cotacoes não verificadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" style="color: white !important" onclick='buscaCotacoes("nao-enviados");' class="small-box-footer">Mais Informações &nbsp;&nbsp;<i style="color: white" class="fas fa-arrow-circle-right"></i></a>
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
                        <a href="#" onclick='buscaCotacoes("recomendadas");' class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Cotações dos estados principais</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-align-justify"></i>
                        </div>
                        <a href="#" onclick='buscaCotacoes("estados");' class="small-box-footer">Mais Informações &nbsp;&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class='card-body' id='principal'>
                    <div class='row' id='vigentes' style="display: none;">
                        <div class='col-12'>
                            <table id="table-data-licitacoes-vigentes" class="table table-responsive table-hover vertical-align text-center" style="width: 100% !important;">
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
                                <tbody>
                                    <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                    <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                    <!--  <div class="tab1-loading overlay"></div>
                                    <div class="tab1-loading loading-img"></div>-->
                                </tbody>
                            </table>
                        </div>
                        <div class='row' id='estados' style="display: none;">
                            <div class='col-12'>
                                <table id="table-data-licitacoes-estados" class="table table-responsive table-hover vertical-align text-center" style="width: 100% !important;">
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
                                    <tbody>
                                        <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                        <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                        <!--  <div class="tab1-loading overlay"></div>
                                        <div class="tab1-loading loading-img"></div>-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='row' id='recomendadas' style="display: none;">
                            <div class='col-12'>
                                <table id="table-data-licitacoes-recomendadas" class="table table-responsive table-hover vertical-align text-center" style="width: 100% !important;">
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
                                    <tbody>
                                        <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                        <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                        <!--  <div class="tab1-loading overlay"></div>
                                        <div class="tab1-loading loading-img"></div>-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='row' id='nao-enviados' style="display: none;">
                            <div class='col-12'>
                                <table id="table-data-licitacoes-nao-enviados" class="table table-responsive table-hover vertical-align text-center" style="width: 100% !important;">
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
                                    <tbody>
                                        <div class="tab1-loading overlay loadTable" style="display: none"></div>
                                        <div class="tab1-loading loading-img loadTable" style="display: none"></div>
                                        <!--  <div class="tab1-loading overlay"></div>
                                        <div class="tab1-loading loading-img"></div>-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</div>

<script type="text/javascript">
$(".sidebar-light-orange").find('.nav-pills').find('a[href="./dashboard.php"]').addClass('active');

$.ajax({
    type: 'GET',
    url: '../ajax/dashboard.php',
    data: 'act=getCountFiltros',
    cache: false,
    success: function (data) {
    }
})

function buscaCotacoes(filtroX){

    $.ajax({
        type: 'GET',
        url:'../ajax/dashboard.php',
        data: 'act=buscaCotacoes&filtro=' + filtroX,
        cache: false,
        success: function (data){
            data = JSON.parse(data);
            let total = data[1];
            data = data[0];

            // console.log(obj);
            if($(`#${filtroX}`).is(':visible') ){
                $(`#${filtroX}`).fadeOut(1000);
            } else {
                $(`#${filtroX}`).fadeIn(2000);
            }

            $('#nCotacoesVigentes').html(total);

            var obj = [];

            $.each(data, function (i, d) {
                obj.push([
                    d.identificador,
                    '',
                    d.uasg,
                    d.data_entrega_proposta_ord,
                    d.informacoes_gerais,
                    d.objeto,
                    d.situacao_aviso,
                    `<button target title='Gerar PDF' style='float:left; margin-left: -20px;  min-width: 33px;' class='btn btn-sm btn-edit pdfLicitacao' id=${d.identificador}><i class='far fa-file-pdf'></i></button>
                     <button title='Imprimir' style='float:right; margin-right: -4px;' class='btn btn-sm btn-edit printLicitacao' id=${d.identificador}><i class='fa fa-print'></i></button>`,
                    
                ]);
            });



            var element = $(`#table-data-licitacoes-${filtroX}`);
            if ( $.fn.DataTable.isDataTable( `#table-data-licitacoes-${filtroX}`)) {
                element.dataTable().fnClearTable();
                element.dataTable().fnDestroy();
            }

            var table = element.DataTable({
                "responsive": true,
                data: obj,
                "columns": [
                    {
                        "visible": false,
                    },
                    {
                        className: "details-control",
                        "orderable": false,
                        width: "5%",
                    },
                    {
                        witdh: "6%",
                        className: "vertical-align",
                    },
                    {
                        witdh: "6%",
                        className: "vertical-align",
                    },
                    {
                        width: '15%',
                        className: "vertical-align",
                    },
                    {
                        width: '65%',
                        className: "vertical-align",
                    },
                    {
                        width: '10%',
                        className: "vertical-align",
                    },
                    {
                        className: "vertical-align",
                        "orderable": false,
                        width: "5%",
                    },
                ],
                "dom": "<'row'<'col-sm-2 pull-left'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
                // fnInitComplete: function () {
                // // $('div.forceSincronismo').html($('#btnForceSincronismo').show());
                // },
                "order": [3, 'desc'],
                "fnDrawCallback": function () {
                    getItensLicitacao(filtroX);
                }
            });
        }
    });
}

function getItensLicitacao(filtroX){
    $(`#table-data-licitacoes-${filtroX} tbody`).off('click').on('click', 'td.details-control', function() {

        var table = $(`#table-data-licitacoes-${filtroX}`).DataTable();
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        var checkbox = '';

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            var infos = row.data();
            var identificador = infos[0];
            tr.addClass('shown');

            $.ajax({
                type: 'GET',
                url: '../ajax/dashboard.php',
                data: 'act=buscaItensLicitacao&identificador=' + identificador,
                cache: false,
                async: true,
                beforeSend: function () {
                  $(".loadTable").show();
                },
                success: function (data){
                    var itens = [];
                    data = JSON.parse(data);

                    var itensComProduto = data.itensComProduto;
                    var email_enviados = data.email_enviados;
                    var datas = data.datas_envio;

                    var input;
                    var value;
                    var disabled;
                    var title;
                    var flag;
                    var iconColor;

                    $.each(data.itens, function(i, d) {
                        // value='"+d.id+"'
                        flag = '';
                        input = '';
                        value = '';
                        iconColor = '#495057';
                        disabled = 'disabled';

                        title = 'Esse item não possui fabricante';
                        if (itensComProduto.indexOf(d.id) > -1) {
                        disabled = '';
                        iconColor = '#17a2b8';
                        // flag = 'E-mail Enviado.';
                        title = 'Enviar E-mail';
                        value = "value='"+d.id+"'";
                        input = '<label class="container" >\n' +
                            '  <input type="checkbox" style="background: white !important"  value="'+d.id+'" class="checkOneItem'+identificador+'">\n' +
                            '  <span class="checkmark"></span>\n' +
                            '</label>';
                        }

                        if (email_enviados.indexOf(d.id) > -1) {
                        var info = "E-mail enviado: " + datas[d.id];
                        iconColor = '#17a2b8';
                        title = 'E-mail Enviado';
                        value = "value='"+d.id+"'";
                        flag = "<i class='fa fa-check-circle text-success' title='"+info+"' style='float: right; margin-top: -13px; font-size: 12px;'></i>";
                        }
                        // console.log(itensComProduto.indexOf(d.id));
                        itens.push([
                        input,
                        d.lic_id,
                        // d.lic_uasg,
                        d.num_aviso,
                        d.cod_produto != null ? d.desc_produto : d.descricao_item,
                        d.fabricante != null ? d.fabricante : '-',
                        d.cod_item_material,
                        d.quantidade,
                        d.unidade,
                        d.valor_estimado,
                        " <button style='color: "+iconColor+"' onMouseOver=\"this.style.color='#495057'\" class='btn btn-sm btn-edit pull-left sendMail'\n" +
                        "      title='"+title+"' id='"+d.id+"' "+disabled+" "+value+" > <span class='fas fa-mail-bulk'/>\n" +
                        "          </button>" + flag,
                        //  " <i class='fa fa-thumbs-up text-info' style='float: right; margin-top: -14px; font-size: 13px;'></i>",
                        ]);
                    });

                    $("table.tblItens").DataTable({
                        retrieve: true,
                        "responsive": true,
                        "searching": false,
                        "paginate": false,
                        "bInfo" : false,
                        data: itens,
                        "language": {
                        "emptyTable": "Sem itens disponiveis",
                        },
                        "columns": [
                            {
                                className: "vertical-align",
                                "orderable": false,
                                width: "5%",
                            },
                            {
                                visible: false,
                            },
                            {
                                className: "vertical-align",
                                width: "13%"
                            },
                            {
                                className: "vertical-align",
                                width: "30%",
                            },
                            {
                                className: "vertical-align",
                                width: "20%",
                            },
                            {
                                className: "vertical-align",
                                width: "9%",
                            },
                            {
                                className: "vertical-align",
                                width: "8%",
                            },
                            {
                                className: "vertical-align",
                                width: "7%",
                            },
                            {
                                className: "vertical-align",
                                width: "7%",
                            },
                            {
                                className: "vertical-align",
                                "orderable": false,
                                width: "9%",
                            }
                        ],
                    });
                }
            });
            // ...tudo
            row.child(format(row.data(), identificador)).show();

            $(".loadTable").hide();
        }
    });

    
    function format(d, id) {
    // `d` is the original data object for the row

    return '<div class="row"><div class="col-12"><button style="float: right; margin-right: -8px; margin-bottom: 10px;\n' +
        '    display: none;" class="btn btn-sm btn-edit text-info pull-left enviarVariosEmails" id="enviarVariosEmails'+id+'" value="'+id+'" title="Enviar E-mail" >' +
        '<span id="loadingAllEmails" style="display: none"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;&nbsp;</span> <span class=\'fas fa-mail-bulk\'/>\n' +
        '              </button></div><div class="table-responsive" style="background: #f5f5f5;">' +
        '<table style="width: 100% !important;" class="table table-responsive table-condesed tblItens text-center" cellpadding="5" cellspacing="0" border="0"> <thead>' +
        '        <tr> ' +
        '         <th scope="col"><label class="container"><input type="checkbox" style="background: white !important"  value="'+id+'" class="checkAllItens"> <span class="checkmark"></span></label></th>' +
        '         <th scope="col">ID Licitação</th>' +
        '         <th scope="col">Número Aviso</th>' +
        '         <th scope="col">Descrição do Item</th>' +
        '         <th scope="col">Fabricante</th>' +
        '         <th scope="col">Código do Item</th>' +
        '         <th scope="col">Quantidade</th>' +
        '         <th scope="col">Unidade</th>' +
        '         <th scope="col">Valor Estimado</th>' +
        '         <th scope="col">Ações</th>' +
        '        </tr>' +
        '      </thead><tbody></tbody></table> </div>'
        ;
    }
}

$(document).on('click', '.pdfLicitacao', function () {
  var id = $(this).attr('id');

  window.open("../ajax/makePdf.php?act=createPdf&lic_id=" + id);
});

$(document).on('click', '.printLicitacao', function () {
  var id = $(this).attr('id');

  window.open("../ajax/makePdf.php?act=imprimir&lic_id=" + id);
});


$(document).on('click', '.checkAllItens', function () {
  var id = $(this).val();
  var atual = $(this).prop('checked');
  var Ncheked = 0;
  var checked = 0;
  var prop = false;

  $("#enviarVariosEmails" + id).hide();

  if (atual == true) {
    prop = true;
    if ($(".checkOneItem" + id).length > 1) {
      $("#enviarVariosEmails" + id).show();
    }
  }

  $.each($(".checkOneItem" + id), function () {

    $(this).prop('checked', prop);

    $(this).click(function () {
      checked = 0;
      Ncheked = 0;

      if ($(this).prop('checked') == false) {
        $(".checkAllItens").prop('checked', false);
      } else {
        $.each($(".checkOneItem" + id), function () {
          if ($(this).prop('checked') == false) {
            Ncheked++;
          } else {
            checked++;
          }
        });

        if (Ncheked == 0) {
          $(".checkAllItens").prop('checked', true);
        }

        if (checked > 1) {
          $("#enviarVariosEmails").show();
          $("#loadingAllEmails").hide();
        } else {
          $("#enviarVariosEmails").hide();
        }
      }
    });
  });
});

</script>