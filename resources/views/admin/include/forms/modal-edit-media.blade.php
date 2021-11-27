<a id="click_for_edit" href="#" data-toggle="modal" data-target="#edit-modal" hidden></a>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" v-if="editingMediaIndex !== ''">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fa fa-edit"></i>
                    Editar
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="form-group col-sm-12">
						<label class="form-control-label text-xs-right" :class="{active: registro[identifiers[media_root].media][editingMediaIndex].nome}" for="seo"> Nome: </label>
                        <input type="text" class="form-control boxed" placeholder="Seo" v-model="registro[identifiers[media_root].media][editingMediaIndex].nome" id="seo" name="seo">
                    </div>
                    <div class="form-group col-sm-12">
						<label class="form-control-label text-xs-right" :class="{active: registro[identifiers[media_root].media][editingMediaIndex].legenda}" for="legenda"> Legenda: </label>
                        <input type="text" class="form-control boxed" placeholder="Título" v-model="registro[identifiers[media_root].media][editingMediaIndex].legenda" id="legenda" name="legenda">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" v-on:click="closeEditMedia()">OK</button>
            </div>
        </div>
    </div>
</div>
