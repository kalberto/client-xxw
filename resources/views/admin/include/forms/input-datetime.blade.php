<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <input :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" class="form-control" autocomplete="off" placeholder="{{$place_holder}}" type="text" v-model="{{$vue_var}}" {{(isset($disable) && $disable) ?'disabled' :'' }}>
</div>

