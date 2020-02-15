
function getLicGerais(){
    $.ajax({
        type: "GET",
        url: '../api/request_licitacoes.php',
        data: 'act=requestLicitacoes',
        cache: false,
        success: function (data){
            console.log(data);
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

                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        } else {
                            // Open this row
                            var infos = row.data();
                            var identificador = infos[0];

                            row.child(format(row.data())).show();
                            tr.addClass('shown');

                            $.ajax({
                                type: 'GET',
                                url: '../ajax/cotacoes.php',
                                data: 'act=getItensLicitacao&identificador=' + identificador,
                                cache: false,
                                beforeSend: function () {
                                    $(".loadTable").show();
                                },
                                success: function(data) {
                                    var itens = [];
                                    data = JSON.parse(data);

                                    console.log(data);

                                    $.each(data, function(i, d) {
                                        itens.push([
                                            '<label class="container">\n' +
                                            '  <input type="checkbox" checked="checked">\n' +
                                            '  <span class="checkmark"></span>\n' +
                                            '</label>',
                                            d.lic_id,
                                           // d.lic_uasg,
                                            d.num_aviso,
                                            d.descricao_item,
                                            d.cod_item_material,
                                            d.quantidade,
                                            d.unidade,
                                            d.valor_estimado,
                                            " <button class='btn btn-sm btn-edit text-info pull-left sendMail'\n" +
                                            "      title='Enviar E-mail' id='"+d.id+"' onclick='getInfosEmail("+d.id+")' value='"+d.id+"'> <span class='fas fa-mail-bulk'/>\n" +
                                            "          </button>",
                                          //  " <i class='fa fa-thumbs-up text-info' style='float: right; margin-top: -14px; font-size: 13px;'></i>",
                                        ]);
                                        //itens.push(d);
                                    });

                                    console.log(itens);

                                    $("table.tblItens").DataTable({
                                        retrieve: true,
                                        "responsive": true,
                                        "searching": false,
                                        data: itens,
                                        "language": {
                                            "emptyTable": "Sem itens disponiveis",
                                        },
                                        "columns": [
                                            {
                                              className: "vertical-align",
                                              width: "5%"
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "13%"
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "10%",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "30%",
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

                            $(".loadTable").hide();
                        }
                    });

                    function format(d) {
                        // `d` is the original data object for the row
                        return '<div class="row"><div class="col-12"><label class="container left"><input type="checkbox" checked="checked"> <span class="checkmark"></span>\n' +
                          '</label><button style="float: right; margin-right: -8px; margin-bottom: 10px;\n' +
                          '    display: none;" class="btn btn-sm btn-edit text-info pull-left sendMail"\n '+
                          '  title="Enviar E-mail" id="'+d.id+'" onclick="getInfosEmail('+d.id+')" value="'+d.id+'"> <span class=\'fas fa-mail-bulk\'/>\n' +
                          '              </button></div><div class="table-responsive" style="background: #f5f5f5;">' +
                          '<table style="width: 100% !important;" class="table table-responsive table-condesed tblItens text-center" cellpadding="5" cellspacing="0" border="0"> <thead>' +
                            '        <tr> ' +
                            '         <th scope="col"></th>' +
                            '         <th scope="col">ID Licitacao</th>' +
                            '         <th scope="col">Número Aviso</th>' +
                            '         <th scope="col">Descrição do Item</th>' +
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
