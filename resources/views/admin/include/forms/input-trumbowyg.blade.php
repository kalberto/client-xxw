<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <trumbowyg v-model="{{$vue_var}}" svg-path="{{url('fonts/vendor/trumbowyg/dist/ui/icons.svg')}}" @tbw-focus="focusTrumbowyg({{$input}})" ></trumbowyg>
    <input :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" hidden>
</div>