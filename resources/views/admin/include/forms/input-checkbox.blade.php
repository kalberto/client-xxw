<div class="form-group form-group-select col-sm-{{$input_size}}" style="margin-top: auto; margin-bottom: auto">
    <label class="control-label" :for="{{$input}}">
        <input :id="{{$input}}" class="checkbox" v-model="{{$vue_var}}" :name="{{$input}}" type="checkbox" :ref="{{$input}}" {{(isset($disable) && $disable) ?'disabled' :'' }}>
        <span>{{$input_label}}</span>
    </label>
</div>
