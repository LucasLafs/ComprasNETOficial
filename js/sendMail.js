$(function () {

    /*  function sendMail (id_item) {
          var idItem = $(this).val();
          console.log(idItem);
            $.get('../ajax/email.php?act=get_infos&id=' + idItem)
                .done(function (data) {
                    console.log(data);
                })
                .fail(function () {
                    console.log('fail');
                });
        }*/

    $(document).on('click', '.sendMail', function () {


        var idItem = $(this).val();
        console.log(idItem);
        console.log($(this).attr('id'));
        console.log($(this).next());
        $.get('../ajax/email.php?act=get_infos&id=' + idItem)
            .done(function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data != 0) {
                    $.each(data, function (i, d) {
                        console.log(i);
                        console.log(d);


                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Enviar e-mail para o fabricante " + d.nome + " sobre esse item?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            showLoaderOnConfirm: true,
                            confirmButtonText: 'Enviar'
                        }).then((result) => {
                            $.get('../ajax/email.php?act=sendEmail&id=' + d.id)
                                .done(function (data) {
                                    console.log(data);
                                    if (data) {
                                        Swal.fire({
                                            title: 'E-mail enviado com sucesso.',
                                            icon: 'success',
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: '<i class="fa fa-thumbs-up"></i>'
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'E-mail não enviado.',
                                            text: 'Tente novamente ou entre em contato com os desenvolvedores.',
                                            icon: 'error',
                                            showCancelButton: false,
                                            confirmButtonColor: '#d33',
                                            confirmButtonText: '<i class="fa fa-thumbs-down"></i>'
                                        });
                                    }
                                })
                                .fail(function () {
                                    console.log('fail');
                                })
                        })

                    });
                } else {
                    Swal.fire({
                        title: 'Esse item não possui fabricante.',
                        text: 'Não é possivel enviar o e-mail.',
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i>'
                    });
                }
            })
            .fail(function () {
                console.log('fail');
            });
    });
});
