<div class="form-group col-sm-{{$media_size}}">
    <label class="control-label">{{$media_title}}</label>
    <div class="images-container">
        <div class="image-container no-marging-bottom" v-if="{{$media_var}}">
            <div class="controls">
                <a href="#" class="control-btn remove control-btn2" data-toggle="modal" data-target="#confirm-modal" v-on:click="removeMedia({{$media_selector}})">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
            <div class="image" :style="{'background-image': 'url('+ getMediaThumbPath({{$media_var}}) + ')'}"></div>
        </div>
        <a href="#" v-on:click="callLoadAssets({{$media_selector}})" class="add-image" data-toggle="modal" data-target="#modal-media" v-else>
            <div class="image-container new no-marging-bottom">
                <div class="image">
                    <i class="fa fa-plus"></i>
                </div>
            </div>
        </a>
    </div>
    <input :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" hidden>
</div>