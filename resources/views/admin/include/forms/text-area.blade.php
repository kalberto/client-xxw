<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <textarea :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" class="form-control" v-model="{{$vue_var}}" rows="{{$rows}}" placeholder="{{$place_holder}}"></textarea>
</div>
