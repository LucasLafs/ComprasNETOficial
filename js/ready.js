$(document).ready(function() {

    /* Imports  */

    var DataTables = document.createElement('script');
    DataTables.src = '../js/DataTables.js';
    document.head.appendChild(DataTables);

    var sendMail = document.createElement('script');
    sendMail.src = '../js/sendMail.js';
    document.head.appendChild(sendMail);


    $(window).on('load', function () {
        $(".sidebar-mini").addClass('sidebar-collapse');

    });

    $(function () {

        $(".navbar-nav").click(function () {
            if ($("#tblFabricantes").length > 0) {
                getFabris();
            }

          /*  if ($("#table-data-licitacoes").length > 0) {

                getCotacoes();
            }*/

        });

        $('#exclusao').on('show.bs.modal', function (e) {
            let id = $(e.relatedTarget).attr('data-id');
            let item = $(e.relatedTarget).attr('data-item');
            let nome = $(e.relatedTarget).attr('data-nome');

            $(this).find('p').text(`Tem certeza que deseja excluir ${item} ${nome} ?`);
            $(this).find('#idFabri').attr('value', `${id}`);
        });

    });

});

function saveTimeout() {

    let $time = $('#time').val();


    $.ajax({
        type: 'POST',
        url: '../api/request_licitacoes.php',
        data: 'act=saveTimeout&time=' + $time,
        cache: false,
        success: function(data){
            console.log(data);
        }
    })

}