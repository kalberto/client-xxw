<div class="form-group row">
    <label class="col-sm-2 form-control-label text-xs-right" :class="{active: asset.nome}" for="nome"> Nome: </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Nome" v-model="asset.nome" id="nome" name="nome">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 form-control-label text-xs-right" :class="{active: asset.legenda}" for="legenda"> Legenda: </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Legenda" v-model="asset.legenda" id="legenda" name="legenda">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 form-control-label text-xs-right" for="asset-type-select"> Tipo: </label>
    <div class="col-sm-10">
        <select id="asset-type-select" class="c-select form-control" name="tipo" v-model="asset.tipo" required>
            <option value="0" disabled>Selecione</option>
            <option v-for="item in tipo" :value="item.id">@{{ item.text }}</option>
        </select>
    </div>
</div>

<div class="form-group">
    <div class="form-group row" v-if="asset.tipo == 1">
        <label class="col-sm-2 form-control-label text-xs-right"> Imagem: </label>
        <div class="col-sm-10">
            <input type="file" accept="image/*" ref="file" name="asset_image" v-on:change="addFile">
        </div>
    </div>
    <div class="form-group row" v-if="asset.tipo == 2">
        <label class="col-sm-2 form-control-label text-xs-right"> Vídeo: </label>
        <div class="col-sm-10">
            <input type="file" accept="video/mp4" ref="file" name="asset_video" v-on:change="addFile">
        </div>
    </div>
    <input type="text" name="file" hidden>
</div>

<div class="form-group">
    <div class="form-group row" v-if="asset.tipo == 1">
        <label class="col-sm-2 form-control-label text-xs-right"> Imagem atual </label>
        <div class="col-sm-10">
            <img class="responsive-img" :src="base_url + '/' + media.media_root.path + 'thumb/' + media.file">
        </div>
    </div>
    <div class="form-group row" v-if="asset.tipo == 1">
        <label class="col-sm-2 form-control-label text-xs-right"> Vídeo atual </label>
        <div class="col-sm-10">
            <video width="100%" class="responsive-video" controls="">
                <source :src="base_url + '/' + media.media_root.path + media.file">
            </video>
        </div>
    </div>
</div>
