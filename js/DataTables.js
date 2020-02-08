$.extend( true, $.fn.dataTable.defaults, {
    "language": {
        "paginate": {
            "previous": 'Próximo',
            "next": 'Anterior',
        },
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "zeroRecords": "Nenhum registro encontrado.",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sSearch": "Pesquisar: ",
        "oAria": {
            "sSortAscending": ": Ordenar colunas de forma ascendente",
            "sSortDescending": ": Ordenar colunas de forma descendente"
        }
    },
    "responsive": true,
    "dom": "<'row'<'sm-4 pull-left'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
});


function getCotacoes() {
    $.ajax({
        type: "GET",
        url: "../ajax/cotacoes.php?act=getLicitacoes",
        // data: "act=getLicitacoes",
        // dataType: 'json',
        cache: false,
        success: function(data) {
            // console.log(data);
            data = JSON.parse(data);
            var obj = [];


            var table = $('#table-data-licitacoes').DataTable({
                "responsive": true,
                data: data,
                "columns": [
                    {
                        "visible": false,
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
                        className: "details-control",
                        "orderable": false,
                        width: "7%",
                    },
                ],
                "order": [3, 'desc'],
                "fnDrawCallback": function () {

                    $('#table-data-licitacoes tbody').off('click').on('click', 'td.details-control', function() {
                        $(".tab1-loading").show();
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
                                success: function(data) {
                                    var itens = [];
                                    data = JSON.parse(data);

                                    console.log(data);

                                    $.each(data, function(i, d) {
                                        itens.push(d);
                                    });

                                    console.log(itens);

                                    $("#tblItens").DataTable({
                                        "responsive": true,
                                       // "retrieve": true,
                                        "searching": false,
                                        "lengthChange": false,
                                        "paging": false,
                                        "pageLength": false,
                                        data: itens,
                                        "columns": [
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                            {
                                                className: "vertical-align",
                                            },
                                        ],

                                    });
                                }
                            });
                        }
                    });

                    function format(d) {
                        // `d` is the original data object for the row
                        return '<div style="background: #f5f5f5"><table cellpadding="5" cellspacing="0" border="0" id="tblItens"> <thead>' +
                            '        <tr> ' +
                            '         <th scope="col">ID Licitacao</th>' +
                            '         <th scope="col">UASG Cotação</th>' +
                            '         <th scope="col">Numero Aviso</th>' +
                            '         <th scope="col">Descrição do Item</th>' +
                            '         <th scope="col">Código do Item</th>' +
                            '         <th scope="col">Quantidade</th>' +
                            '         <th scope="col">Unidade</th>' +
                            '         <th scope="col">Valor Estimado</th>' +
                            '        </tr>' +
                            '      </thead><tbody></tbody></table> </div>'
                            ;
                    }
                }
            });


            console.log(data);

            $(".tab1-loading").hide();

        }

    });
}