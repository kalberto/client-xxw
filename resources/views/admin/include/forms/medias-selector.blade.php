<div class="form-group col-sm-{{$media_size}}">
	<label class="control-label">{{$media_title}}</label>
	<div class="images-container images-container-sortable">
		<div class="image-container no-marging-bottom" v-for="(secaoMedia,indexMedia) in {{$media_var}}">
			<div class="controls">
				{{-- <a href="" class=" control-btn move control-btn2">
					<i class="fa fa-arrows"></i>
				</a>
				<a href="#" :class="['control-btn2','control-btn','star',{'active':indexMedia == mediaDestaque}]" v-on:click="setDestaque({{$media_remover}})">
					<i class="fa"></i>
				</a>
				<a href="#" class="control-btn2 control-btn edit" v-on:click="editMedia({{$media_remover}})">
					<i class="fa fa-pencil"></i>
				</a>
				<a href="#" class="control-btn remove control-btn2" data-toggle="modal" data-target="#confirm-modal" v-on:click="removeMedia({{$media_remover}})">
					<i class="fa fa-trash-o"></i>
				</a> --}}
			</div>
			<div class="image" :style="{'background-image': 'url('+ getMediaThumbPath(secaoMedia) + ')'}"></div>
		</div>
		<a href="#" v-on:click="callLoadAssets({{$media_selector}})" class="add-image" data-toggle="modal" data-target="#modal-media" v-if="{{ isset($limit) ? $media_var . '.length <' . $limit : 'true' }}">
			<div class="image-container new no-marging-bottom">
				<div class="image">
					<i class="fa fa-plus"></i>
				</div>
			</div>
		</a>
	</div>
</div>
