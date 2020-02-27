function getLicGerais(){
  $.ajax({
    type: "GET",
    url: '../api/request_licitacoes.php',
    data: 'act=requestLicitacoes',
    cache: false,
    success: function (data){
      if (Array.isArray(data)){
        $(".alert-sincronismo").fadeIn();


        window.setTimeout(function() {
          $(".alert-sincronismo").fadeOut();
        }, 2000);
      }
    }
    // require_once ("../api/request_licitacoes.php");
    // $_REQUEST['act'] = '&requestLicitacoes';
    // requestLicGeraisComprasNet();
  })
}

function getProdGerais() {
  $.ajax({
    type: 'POST',
    url: '../api/request_produtos.php',
    data: '',
    cache: false,
    success: function (data){
      if(Array.isArray){
        console.log('Success');

      } else {
        console.log('fail');
      }
    }
  });

}

function getCotacoes() {
  $.ajax({
    type: "GET",
    url: "../ajax/cotacoes.php?act=getLicitacoes",
    // data: "act=getLicitacoes",
    // dataType: 'json',
    cache: false,
    beforeSend: function () {
      $(".loadTable").show();
    },
    success: function(data) {
      data = JSON.parse(data);
      let offsetInt = data.length;
      var obj = [];

      makeTblLicitacoes(data);


    }

  });
}

function makeTblLicitacoes(data) {

  var element = $("#table-data-licitacoes");


/*  if ( $.fn.DataTable.isDataTable( '#table-data-licitacoes' )) {
    element.dataTable().fnClearTable();
    element.dataTable().fnDestroy();
  }*/

  var table = element.DataTable({
    retrieve: true,
    "autoWidth": false,
    "responsive": true,
    data: data,
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
    "dom": "<'row'<'col-sm-2 pull-left'f><'col-sm-2 filtroData'><'col-sm-3 filtroNomeProduto'><'col-sm-3 filtroObjDesc'><'col-sm-1 lupa'><'col-sm-1 pull-right forceSincronismo'>>" +
   // "dom": "<'row'<'col-sm-2 pull-left'f><'col-sm-9 formFiltro'><'col-sm-1 pull-right forceSincronismo'>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-1'i><'offset-4 col-sm-7 text-right'p>>",
    fnInitComplete: function () {
      $('div.forceSincronismo').html($('#btnForceSincronismo').show());
      $('div.filtroData').html($('#filtroData').show());
      $('div.filtroNomeProduto').html($('#filtroNomeProduto').show());
      $('div.filtroObjDesc').html($('#filtroObjDesc').show());
      $('div.lupa').html($('#lupaFiltro').show());
    },
    "order": [4, 'desc'],
    "fnDrawCallback": function () {

      //<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="table-data-licitacoes">
      //  $(".dataTables_filter").html("<label>Pequisa Geral</label><br> <input type='search' class='form-control form-control-sm' placeholder='' aria-controls='table-data-licitacoes'>");

      $('#table-data-licitacoes tbody').off('click').on('click', 'td.details-control', function() {

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
          console.log(identificador);
          tr.addClass('shown');

          $.ajax({
            type: 'GET',
            url: '../ajax/cotacoes.php',
            data: 'act=getItensLicitacao&identificador=' + identificador,
            cache: false,
            async: true,
            beforeSend: function () {
              $(".loadTable").show();
            },
            success: function(data) {
              var itens = [];
              data = JSON.parse(data);


              console.log('ITENSSSS ');
              console.log(data);

              var itensComProduto = data.itensComProduto;
              var email_enviados = data.email_enviados;
              var datas = data.datas_envio;

              console.log(datas);

              var flag;
              var input;
              var value;
              var title;
              var disabled;
              var iconColor;
              var idFabricante;


              $.each(data.itens, function(i, d) {
                // value='"+d.id+"'
                flag = '';
                input = '';
                value = '';
                idFabricante = '';
                iconColor = '#495057';
                disabled = 'disabled';

                title = 'Esse item não possui fabricante';
                if (itensComProduto.indexOf(d.id) > -1) {
                  disabled = '';
                  iconColor = '#17a2b8';
                  title = 'Enviar E-mail';
                  value = "value='"+d.id+"'";
                  idFabricante = d.idFabricante;

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
                  flag = "<i class='fa fa-check-square text-success' title='"+info+"' style='float: right; margin-top: -21px;margin-left: 57px; font-size: 12px;'></i>";
                }
                
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
                  " <button style='color: "+iconColor+"' class='btn btn-sm btn-edit pull-left sendMail'\n" +
                  "      title='"+title+"' id='"+d.id+"' data-fabricante='"+idFabricante+"' "+disabled+" "+value+" > <span class='fas fa-mail-bulk'/>\n" +
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
                "order": [4, 'desc'],
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

          /*   console.log(checkbox);

           console.log('lengttth checkbox dentro de suucess'  + $(".checkOneItem"+ identificador).length);
             if ($(document, $(".checkOneItem"+ identificador).length > 0)) {
               console.log('cai no if ');
               checkbox = '<label class="container"><input type="checkbox" value="'+identificador+'" class="checkAllItens"> <span class="checkmark"></span></label>';
             }*/

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
          '         <th scope="col"><label class="container"><input type="checkbox"  value="'+id+'" class="checkAllItens"> <span class="checkmark"></span></label></th>' +
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
  });

  $(".loadTable").hide();
}

$(document).on('click', '#filtrarCotacoes', function () {


  var element = $("#table-data-licitacoes");
  $(".loadTable").show();
  element.hide();

  var obj = [
    {
      name: 'act',
      value: 'getLicitacoes',
    },
    {
      name: 'filtro',
      value: 1,
    },
    {
      name: 'data',
      value: $("#inputfiltroData").val(),
    },
    {
      name: 'nome_produto',
      value: $("#nome_produto").val(),
    },
    {
      name: 'desc_obj',
      value: $("#desc_obj").val(),
    }];

  $("#formFiltros").append($('div.forceSincronismo').html() + $('div.filtroData').html() + $('div.filtroNomeProduto').html() + $('div.filtroObjDesc').html()  +  $('div.lupa').html());


  if ( $.fn.DataTable.isDataTable( '#table-data-licitacoes' )) {
    element.dataTable().fnClearTable();
    element.dataTable().fnDestroy();
  }

  $.ajax({
    type: "POST",
    url: "../ajax/cotacoes.php",
    data: obj,
    // dataType: 'json',
    cache: false,
    beforeSend: function () {
      $(".loadTable").show();
    },
    success: function (data) {

      if (data != '') {
        data = JSON.parse(data);
        makeTblLicitacoes(data);
        element.show();
        return true;
      }

      Swal.fire({
        icon: 'info',
        title: 'Nenhum registro encontrado.',
        text: "Não foram encontrados nenhum registro com os filtros desejado."
      }).then(function () {
        getCotacoes();
      });

      element.show();

    },
  });

});

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

// onclick='window.open(" . $link. ")'
