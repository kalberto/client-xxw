<div class="form-group">
    <div class="row" v-if="registro.tipo == 1">
		<div class="col-sm-12 form-group">
			<label class="form-control-label text-xs-right"> Imagem: </label>
            <input type="file" accept="image/*" ref="file" name="asset_image" v-on:change="addFile">
			<input type="text" name="file" hidden>
        </div>
    </div>
    <div v-if="registro.tipo == 2">
        <div class="row">
            @include('admin.include.input-checkbox',['input_size' => 2,'input' => 'video_is_link','vue_var' => 'registro.video_is_link','input_label' => 'Vídeo tem link?'])
        </div>
        <div class="row" v-if="registro.video_is_link == true">
            @include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"video_link"','input_label'=>'Link Embed Vídeo:', 'place_holder'=>'Vídeo Link','vue_var'=>'registro.video_link'])
        </div>
		<div class="row" v-else>
			<div class="col-sm-12 form-group">
				<label class="form-control-label text-xs-right"> Vídeo: </label>
				<input type="file" accept="video/mp4" ref="file" name="asset_video" v-on:change="addFile">
				<input type="text" name="file" hidden>
			</div>
        </div>
    </div>
</div>