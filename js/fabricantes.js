function getFabris() {

    $.ajax({
        type: 'GET',
        url: '../ajax/fabricantes.php',
        data: 'act=allFabris',
        cache: false,
        beforeSend: function () {
            $(".loadTable").show();
        },
        success: function(data) {
            console.log(data);                      

            if (data == 0) {
                $('#btnCadastrarFabricante').show();
                $("#tblFabricantes").hide();
                $("#msgSemFabricante").show();
              
                return false;
            } else {
                $("#tblFabricantes").show();
                $("#msgSemFabricante").hide();
            }

            data = JSON.parse(data);           

            var fabricantes = [];

            $.each(data, function (i, d) {
                var descricao = d.descricao != '' ? d.descricao : '-';

                fabricantes.push([
                    d.nome,
                    d.email,
                    descricao,
                    " <button  data-toggle='modal' data-target='#modalEditaFabri' class='btn btn-sm btn-edit text-info pull-left'\n" +
                    "      title='Editar Fabricante' data-id='" + d.id + "'>\n" +
                    "                                <span class='fa fa-edit'/>\n" +
                    "          </button>  " +
                    "<button class='btn btn-sm btn-trash' title='Excluir Fabricante' data-toggle='modal' data-target='#exclusao' " +
                    "data-id='" + d.id + "' data-item='o fabricante'  data-nome='" + d.nome + "'>\n" +
                    "                 <span class='fa fa-trash'/> </button>",
                ])
            });

            var table = $("#tblFabricantes");         

            if ( $.fn.DataTable.isDataTable( '#tblFabricantes' )) {
                table.dataTable().fnClearTable();
                table.dataTable().fnDestroy();
            }

            $(".loadTable").hide();

            table.DataTable({
                retrieve: true,
                "responsive": true,
                data: fabricantes,
                "columns": [
                    {
                        className: "vertical-align",
                        width: '550px',
                    },
                    {
                        className: "vertical-align",
                        width: '450px',
                    },
                    {
                        className: "vertical-align",
                        width: '550px',
                    },
                    {
                        "orderable": false,
                        width: "80px",
                    },
                ],
                "dom": "<'row'<'col-sm-2 pull-left'f><'col-sm-10 pull-right cadastrarFabricante'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
                fnInitComplete: function () {
                    $('div.cadastrarFabricante').html($('#btnCadastrarFabricante').show());
                }
            });
        }
    });
}

function saveFabri(action) {
    var data = $("#form" + action).serializeArray();

    console.log(data);

    var msg = action == 'CadastroFabri' ? 'Cadastrado' : 'Editado';


    $.ajax({
        type: 'POST',
        url: '../ajax/fabricantes.php',
        data: data,
        beforeSend: function () {
            $("#form" + action).hide();
            $(".loadModal").show();
        },
        success: function (data) {
            console.log(data);
            if (!data) {
                $("#msgStoreFabri").html('Nao foi possivel cadastrar').show();
                return false;
            }

            getFabris();

            $(".alert-success i").append("   " + msg + " com Sucesso");
            $(".alert-success").show();
            $(".loadModal").hide();
            $("#modal" + action).modal('hide');

            if (action == 'CadastroFabri') {
                $('#form' +action+' input').each(function() {
                    if ($(this).attr('name') != 'act' ) {
                        $(this).val('');
                    }
                });
            }


            window.setTimeout(function() {
                $(".alert-success i").html("");
                $(".alert-success").hide();

            }, 2000);


            $("#form" + action).show();
        },
    });
}



function excluirFabricante (idFabri)
{
    var data = {};

    data = {
        act: 'excluir',
        idFabri: idFabri,
    };


    $.ajax({
        type: 'POST',
        url: '../ajax/fabricantes.php',
        data: data,
        beforeSend: function () {
            $(".loadModal").show();
        },
        success: function (data) {
            if (!data) {
                alert('Nao foi possivel excluir');
                return false;
            }

            getFabris();

            $("#exclusao").modal('hide');

            $(".alert-success i").append("   Excluído com Sucesso");
            $(".alert-success").show();
            $(".loadModal").hide();

            window.setTimeout(function() {
                $(".alert-success i").html("");
                $(".alert-success").hide();

            }, 2000);
        },
    });
}


$("#modalEditaFabri").on('show.bs.modal', function (e) {
    let id = $(e.relatedTarget).attr('data-id');

    let form = $(this).find('form');
    form.find('#id').val(id);

    $.ajax({
        type: 'GET',
        url: '../ajax/fabricantes.php?act=getFabri&id=' + id,
        beforeSend: function () {
            $(".loadModal").show();
        },
        success: function (data) {

            data = JSON.parse(data);

            $.each(data, function (campo, value) {
                form.find('input').each(function(){

                    if ($(this).attr('name') == campo) {
                        $(this).val(value);
                    }


                });
            });

            $(".loadModal").hide();
        },
    });

});