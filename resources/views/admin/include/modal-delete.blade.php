<a id="click_for_modal" href="#" data-toggle="modal" data-target="#confirm-modal" ref="click_for_modal" hidden></a>
<div class="modal fade" id="confirm-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fa fa-warning"></i>
                    Atenção
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir este registro?</p>
                <p>Esta ação é irreversível.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="confirmDelete()" data-dismiss="modal">Confirmar Exclusão</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Mudei de Ideia</button>
            </div>
        </div>
    </div>
</div>