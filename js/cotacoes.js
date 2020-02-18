function getLicGerais(){
    $.ajax({
        type: "GET",
        url: '../api/request_licitacoes.php',
        data: 'act=requestLicitacoes',
        cache: false,
        success: function (data){
<<<<<<< Updated upstream
            console.log(data);
=======
            if (Array.isArray(data)){
              $(".alert-sincronismo").fadeIn();


              window.setTimeout(function() {
                $(".alert-sincronismo").fadeOut();
              }, 2000);
            }
>>>>>>> Stashed changes
        }
            // require_once ("../api/request_licitacoes.php");
            // $_REQUEST['act'] = '&requestLicitacoes';
            // requestLicGeraisComprasNet();
    })
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
            // console.log(data);
            data = JSON.parse(data);
            var obj = [];

            var element = $("#table-data-licitacoes");


            if ( $.fn.DataTable.isDataTable( '#table-data-licitacoes' )) {
                element.dataTable().fnClearTable();
                element.dataTable().fnDestroy();
            }

            var table = element.DataTable({
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
                "dom": "<'row'<'col-sm-2 pull-left'f><'col-sm-10 pull-right forceSincronismo'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
                fnInitComplete: function () {
                    $('div.forceSincronismo').html($('#btnForceSincronismo').show());
                },
                "order": [3, 'desc'],
                "fnDrawCallback": function () {

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

                                    var itensComProduto = data.itensComProduto;

                                    var input;
                                    var value;
                                    var disabled;
                                    var title;

                                    $.each(data.itens, function(i, d) {
                                      // value='"+d.id+"'
                                      input = '';
                                      value = '';
                                      disabled = 'disabled';
                                      title = 'Esse item não possui fabricante';
                                      if (itensComProduto.indexOf(d.id) > -1) {
                                          disabled = '';
                                          title = 'Enviar E-mail';
                                          value = "value='"+d.id+"'";
                                          input = '<label class="container" >\n' +
                                                    '  <input type="checkbox"  value="'+d.id+'" class="checkOneItem'+identificador+'">\n' +
                                                    '  <span class="checkmark"></span>\n' +
                                                  '</label>';
                                      }
                                      console.log(itensComProduto.indexOf(d.id));
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
                                            " <button class='btn btn-sm btn-edit text-info pull-left sendMail'\n" +
                                            "      title='"+title+"' id='"+d.id+"' "+disabled+" "+value+" > <span class='fas fa-mail-bulk'/>\n" +
                                            "          </button>",
                                          //  " <i class='fa fa-thumbs-up text-info' style='float: right; margin-top: -14px; font-size: 13px;'></i>",
                                        ]);
                                        //itens.push(d);
                                    });

                                  //  console.log(itens);

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
                                                width: "45%",
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
                                                width: "8%",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "7%",
                                            },
                                            {
                                                className: "vertical-align",
                                                "orderable": false,
                                                width: "8%",
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
                          '<span id="loadingAllEmails"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;&nbsp;</span> <span class=\'fas fa-mail-bulk\'/>\n' +
                          '              </button></div><div class="table-responsive" style="background: #f5f5f5;">' +
                          '<table style="width: 100% !important;" class="table table-responsive table-condesed tblItens text-center" cellpadding="5" cellspacing="0" border="0"> <thead>' +
                            '        <tr> ' +
                            '         <th scope="col"><label class="container"><input type="checkbox" value="'+id+'" class="checkAllItens"> <span class="checkmark"></span></label></th>' +
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
            console.log(data);

        }

    });
}


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
        } else {
          $("#enviarVariosEmails").hide();
        }
      }
    });
  });
});
