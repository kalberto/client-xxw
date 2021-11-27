
<div class="row">
    @include('admin.include.forms.input-text',['input_size' => 12, 'input' => '"nome"','input_label' => 'Nome:','place_holder' => 'Nome', 'vue_var' => 'registro.nome'])
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-block">
            <div class="title-block">
                <h3 class="title">Permissões do perfil</h3>
            </div>        
            <div class="row">
                <div class="col-md-12 row subtitle-block" v-for="mod in modulos">
                    <p class="col-md-12">
                        <label class="control-label">@{{mod.nome}}</label>
                    </p>
                    <div class="col-md-4" v-for="perm in mod.mod_adm_permissao">
                        <label>
                            <div class="form-group">
                                <input :id="mod.id" type='checkbox' v-model="registro.mod_adm_permissao[perm.id]" :disabled="mod.obrigatorio == 1" class="checkbox">
                                <span :for="mod.id">@{{perm.permissao.nome }}</span>
                            </div>
                        </label>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>