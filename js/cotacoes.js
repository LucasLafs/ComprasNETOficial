
function getLicGerais(){
    $.ajax({
        type: "GET",
        url: '../api/request_licitacoes.php',
        data: 'act=requestLicitacoes',
        cache: false,
        success: function (data){
            if (Array.isArray(data)){
                console.log('Success');
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
                console.log(data);

            } else {
                console.log(data);
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
            // console.log(data);
            data = JSON.parse(data);
            let offsetInt = data.length;
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
                        width: "7%",
                    },
                    {
                        className: "vertical-align",
                    },
                    {
                        className: "vertical-align",
                    },
                    {
                        className: "vertical-align",
                        width: '25%',
                    },
                    {
                        className: "vertical-align",
                    },
                    {
                        className: "vertical-align",
                    },
                    {
                        className: "vertical-align",
                        "orderable": false,
                        width: "7%",
                    },
                ],
                "dom": "<'row'<'col-sm-2 pull-left'f><'offset-5 col-sm-5 pull-right forceSincronismo'>>" +
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
                                            d.lic_id,
                                           // d.lic_uasg,
                                            d.num_aviso,
                                            d.descricao_item,
                                            d.cod_item_material,
                                            d.quantidade,
                                            d.unidade,
                                            d.valor_estimado,
                                            " <button class='btn btn-sm btn-edit text-info pull-left'\n" +
                                            "      title='Enviar E-mail'> <span class='fas fa-mail-bulk'/>\n" +
                                            "          </button>  " +
                                            "<button class='btn btn-sm btn-edit text-info' title='Imprimir Item' data-toggle='modal' data-target='#exclusao' " +
                                            "data-id='" + d.lic_id + "' data-item='o fabricante'  data-nome='" + d.descricao_item + "'>\n" +
                                            "                 <span class='fa fa-print'/> </button>",
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
                                                width: "150px",
                                            },/*
                                            {
                                                className: "vertical-align",
                                                width: "80px",
                                            },*/
                                            {
                                                className: "vertical-align",
                                                width: "150px",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "580px",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "150px",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "100px",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "100px",
                                            },
                                            {
                                                className: "vertical-align",
                                                width: "150px",
                                            },
                                            {
                                                className: "vertical-align",
                                                "orderable": false,
                                                width: "150px",
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
                        return '<div style="background: #f5f5f5; width: 100%"><table class="table table-responsive table-condesed tblItens" cellpadding="5" cellspacing="0" border="0"> <thead>' +
                            '        <tr> ' +
                            '         <th scope="col">ID Licitacao</th>' +
                         //   '         <th scope="col">UASG Cotação</th>' +
                            '         <th scope="col">Numero Aviso</th>' +
                            '         <th scope="col">Descrição do Item</th>' +
                            '         <th scope="col">Código do Item</th>' +
                            '         <th scope="col">Quantidade</th>' +
                            '         <th scope="col">Unidade</th>' +
                            '         <th scope="col">Valor Estimado</th>' +
                            '         <th scope="col">Acoes</th>' +
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