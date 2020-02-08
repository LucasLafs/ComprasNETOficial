<?php
require_once("../header/cabecalho.php");
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Configurações de Email</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Configurações de E-mail</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class='col-md-12'>
          <div class="row">
            <div class='col-md-6'>
              <!-- Default box -->
              <div class="card" style='min-height: 605px;'>
                <div class="card-header">
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="save" data-toggle="tooltip" title="save" onClick="saveSmtp()">
                      <i class="fas fa-save"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class='col-md-4'>
                          <div class='form-group form-conf-email'>
                            <label for='remetente_conf_conta'>Remetente</label>
                            <input id='remetente_conf_conta' name="remetente_conf_conta" class='form-control' type=text />
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class='col-md-4'>
                          <div class='form-group form-conf-email'>
                            <label for='smtp_conf_conta'>Servidor SMTP</label>
                            <input id='smtp_conf_conta' name="smtp_conf_conta" class='form-control' type=text />
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class='col-md-4'>
                          <div class='form-group form-conf-email'>
                            <label for='port_conf_conta'>Porta SMTP</label>
                            <input id='port_conf_conta' name="port_conf_conta" class='form-control' type=text />
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class="col-md-4">
                          <div class='form-group form-conf-email'>
                            <label for='nome_conf_conta'>Usuario</label>
                            <input id='nome_conf_conta' name="nome_conf_conta" class='form-control' type=text></input>
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class='col-md-4'>
                          <div class='form-group form-conf-email'>
                            <label for='senha_conf_conta'>Senha</label>
                            <input id='senha_conf_conta' name="senha_conf_conta" class='form-control' type=password></input>
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class='col-md-4'>
                          <div class='form-group form-conf-email'>
                            <label for='copia_conf_conta'>Cópia E-mail</label>
                            <input id='copia_conf_conta' name="copia_conf_conta" class='form-control' type=text></input>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-md-6'>
              <!-- Default box -->
              <div class="card" style="min-height: 605px">
                <div class="card-header">
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="save" data-toggle="tooltip" title="Save" onClick="saveBody()">
                      <i class="fas fa-save"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">

                      <div class="row">
                        <div class='col-md-12'>
                          <div class='form-group form-body-email'>
                            <label for='assunto_email'>Assunto</label>
                            <input id='assunto_email' name="assunto_email" class='form-control' type=text />
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class="col-md-12">
                          <div class='form-group form-body-email'>
                            <label for='corpo_email'>Corpo</label>
                            <textarea id='corpo_email' name="corpo_email" class='form-control' type=text rows='14'></textarea>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>

<script defer src="../layout/plugins/datatables/jquery.dataTables.js"></script>
<script defer src="../layout/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>


<script type="text/javascript">
  //INIT
  $(".menu-open").find('.menu-geral').find('a[href="./conf-email.php"]').addClass('active');

  $(document).ready(function() {
    getSmtp();
    getBody();
  });

  function getSmtp(){
    $.ajax({
      type: 'GET',
      url: '../ajax/conf-email.php',
      data: 'act=getSmtp',
      cache: false,
      success: function (data){

        if(data != 0){
          data = JSON.parse(data);
          $('#remetente_conf_conta').val(data['remetente']);
          $('#smtp_conf_conta').val(data['server_smtp']);
          $('#port_conf_conta').val(data['port_smtp']);
          $('#nome_conf_conta').val(data['usuario']);
          $('#senha_conf_conta').val(data['senha']);
          $('#copia_conf_conta').val(data['cop_email']);

        } else {
          console.log('error')
        }
      }
    });
  }

  function saveSmtp() {

    data = '';
    $('.form-conf-email').find('input').each(function() {
      if ($(this).attr('type') == 'checkbox') {
        if ($(this).prop('checked'))
          data += '&' + $(this).attr('name') + '=1';
        else
          data += '&' + $(this).attr('name') + '=0';
      } else {
        data += '&' + $(this).attr('name') + '=' + $(this).val();
      }
    });

    $.ajax({
      type: 'POST',
      url: '../ajax/conf-email.php',
      data: "act=saveSmtp" + data,
      cache: false,
      success: function(data) {
        if (data == 1) {
          console.log('Success');
        } else {
          console.log('Error');
        }
      }
    });
  }

  function getBody(){
    $.ajax({
      type: 'GET',
      url: '../ajax/conf-email.php',
      data: 'act=getBody',
      cache: false,
      success: function (data){
        if(data != 0){
          data = JSON.parse(data);
          $('#assunto_email').val(data['smtp_assunto']);
          $('#corpo_email').val(data['smtp_corpo']);
        } else {
          console.log('error');
        }
      }
    });
  }

  function saveBody(){
    data = '';
    $('.form-body-email').find('input').each(function() {
      if ($(this).attr('type') == 'checkbox') {
        if ($(this).prop('checked'))
          data += '&' + $(this).attr('name') + '=1';
        else
          data += '&' + $(this).attr('name') + '=0';
      } else {
        data += '&' + $(this).attr('name') + '=' + $(this).val();
      }
    });

    data += '&' + $('.form-body-email').find('textarea').attr('name') + '=' + $('.form-body-email').find('textarea').val();

    $.ajax({
        type: 'POST',
        url: '../ajax/conf-email.php',
        data: "act=saveBody" + data,
        cache: false,
        success: function(data) {
          if (data == 1) {
            console.log('Success');
          } else {
            console.log('Error');
          }
        }
    });
  }

</script>