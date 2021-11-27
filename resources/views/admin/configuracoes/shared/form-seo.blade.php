<div class="row">
    <div class="form-group col-sm-6">
        <label class="form-control-label text-xs-right" :class="{active: registro.seo_sufix}" for="seo_sufix"> Sufixo SEO: </label>
        <input id="seo_sufix" type="text" class="form-control boxed" placeholder="Sufixo SEO" v-model="registro.seo_sufix" name="seo_sufix">
    </div>
    <div class="form-group col-sm-6">
        <label class="form-control-label text-xs-right" :class="{active: registro.tag_manager_id}" for="tag_manager_id"> Gerenciador de tags: </label>
        <input id="tag_manager_id" type="text" class="form-control boxed" placeholder="Gerenciador de tags" v-model="registro.tag_manager_id" name="tag_manager_id">
    </div>
    <div class="col-2">
        <button class="btn btn-primary" type="button" v-on:click="addMetaTag">Adicionar meta tag</button>
    </div>
</div>
<div class="row" v-for="(complemento, index) in registro.complementos">
    <div class="col-sm-1">
        <a href="#" v-on:click="removeMetaTag(index)"><i class="fa fa-minus-circle"></i></a>
    </div>
    <div class="col-sm-3">
        <div class="row">
            <div class="form-group col-sm-10">
                <label class="form-control-label text-xs-right" :class="{active: registro.complementos[index].nome}" for="'complementos.'+index+'.nome'"> Nome: </label>
                <input type="text" class="form-control boxed" placeholder="Nome" v-model="registro.complementos[index].nome" :id="'complementos.'+index+'.nome'" :name="'complementos.'+index+'.nome'">
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="row">
            <div class="form-group col-sm-10">
                <label class="form-control-label text-xs-right" :class="{active: registro.complementos[index].tipo}" for="'complementos.'+index+'.tipo'"> Tipo: </label>
                <select id="'complementos.'+index+'.tipo'" class="c-select form-control boxed" v-model.lazy="registro.complementos[index].tipo">
                    <option disabled value="">Selecione</option>
                    <option value="1">Texto</option>
                    <option value="2">Imagem</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="row">
            <div class="form-group col-sm-12">
                <label class="form-control-label text-xs-right" :class="{active: registro.complementos[index].value}" for="'complementos.'+index+'.valor'"> Valor: </label>
                <div class="row" v-if="registro.complementos[index].tipo == 1">
                    <div class="form-group col-sm-12">
                        <input type="text" class="form-control boxed" placeholder="Nome" v-model="registro.complementos[index].valor" :id="'complementos.'+index+'.valor'" :name="'complementos.'+index+'.valor'">
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-sm-12">
                        <a href="#" v-on:click="callLoadAssets(index)" class="add-image" data-toggle="modal" data-target="#modal-media">
                            <div class="form-group row">
                                <div class="col-sm-12 item-col-img">
                                    <img class="item-img rounded" :src="complemento.valor" v-if="complemento.valor" width="60" height="60">
                                    <img class="item-img rounded" src="https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg" v-else>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>