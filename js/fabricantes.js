function getFabris() {
    $.get('api/allFuncs')
        .done(function (response) {

            var data = [];

            var sexo = '';

            $.each(response, function (i ,d) {
                sexo = 'Não Informado';
                if (d.sexo != null) {
                    sexo = d.sexo == 'M' ? 'Masculino' : 'Feminino';
                }

                data.push([
                    d.nome,
                    d.sobrenome,
                    d.idade,
                    sexo,
                    " <button  data-toggle='modal' data-target='#modalEditaFunc' class='btn btn-sm btn-edit text-info pull-left'\n" +
                    "      title='Editar Funcionario' data-id='" + d.id + "'>\n" +
                    "                                <span class='fa fa-edit'/>\n" +
                    "          </button>  " +
                    "<button class='btn btn-sm btn-trash' title='Excluir Funcionario' data-toggle='modal' data-target='#exclusao' data-id='" + d.id + "'  data-nome='" + d.nome + "'>\n" +
                    "                 <span class='fa fa-trash'/> </button>",
                ])
            });

            var table = $('#tblFuncionarios');

            if ( $.fn.DataTable.isDataTable( '#tblFuncionarios' )) {
                table.dataTable().fnClearTable();
                table.dataTable().fnDestroy();
            }

            table.DataTable({
                data: data,
            });

            $('.tab1-loading').hide();

        })
        .fail(function () {
            console.log('fail');
        });

}

function saveFabri(action) {
    var data = $("#form" + action).serializeArray();

    console.log(data);

    var msg = action == 'CadastroFabri' ? 'Cadastrado' : 'Editado';


    $.ajax({
        type: 'POST',
        url: '../ajax/fabricantes',
        data: data,
        beforeSend: function () {
            $("#form" + action).hide();
            $(".loadModal").show();
        },
        success: function (data) {
            console.log(data);
            if (!data) {
                $("#msgStoreFunc").html('Nao foi possivel cadastrar').show();
                return false;
            }

            getFabris();

            $(".alert-success i").append("   " + msg + " com Sucesso");
            $(".alert-success").show();
            $(".loadModal").hide();
            $("#modal" + action).modal('hide');

            if (action == 'CadastroFabri') {
                $('#form' +action+' input').each(function() {
                    $(this).val('');
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