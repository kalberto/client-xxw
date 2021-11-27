<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <input :id="{{$input}}" :min="{{isset($min) ? $min : 0}}" :name="{{$input}}" :ref="{{$input}}" class="form-control" placeholder="{{$place_holder}}" v-model.lazy="{{$vue_var}}" v-money="moeda" {{(isset($disable) && $disable) ?'disabled' :'' }}>
</div>
