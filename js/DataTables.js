$.extend( true, $.fn.dataTable.defaults, {
    "language": {
        "paginate": {
            "previous": 'Anterior',
            "next": 'Próximo',
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

