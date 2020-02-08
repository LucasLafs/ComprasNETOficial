<!-- Modal -->
<div class="modal fade" id="modalEditaFunc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFuncLabel">Editar Funcionário <i></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger" style="display: none;" id=msgStoreFunc"">
                    <strong></strong>
                </p>
                <form action="" class="form-group text-center" id="formEditaFunc">
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="offset-3 col-md-6">
                            <label for="">Nome</label>
                            <input type="text" class="form-control input" name="nome" required>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-2 col-md-8">
                            <label>Sobrenome</label>
                            <input type="text" class="form-control input" name="sobrenome" required>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-2 col-md-3">
                            <label for="">Idade</label>
                            <input type="number" class="form-control input" name="idade" required maxlength="2">
                            <br>
                        </div>
                        <div class="col-md-6">
                            <label for="sexo">Sexo</label>
                            <br>
                            <label><input type="radio" name="sexo" value="F" class="form-control">Feminino</label>&nbsp;&nbsp;
                            <label><input type="radio" name="sexo" value="M" class="form-control">Masculino</label>
                        </div>
                    </div>
                </form>

                <div class="tab1-loading overlay loadModal" style="display: none"></div>
                <div class="tab1-loading loading-img loadModal" style="display: none"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="saveFunc('EditaFunc')">Editar</button>
            </div>
        </div>
    </div>
</div>