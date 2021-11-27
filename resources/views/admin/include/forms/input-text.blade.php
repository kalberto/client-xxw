<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <input :id="{{$input}}" :min="{{isset($min) ? $min : 0}}" :name="{{$input}}" :ref="{{$input}}" class="form-control" {{isset($type) ? 'type='.$type : 'type=text'}} :maxlength="{{isset($max) ? $max : '"false"'}}" placeholder="{{$place_holder}}" v-model="{{$vue_var}}" {!!(isset($mask) ? 'v-mask="'.$mask.'"' : '')!!} {{(isset($disable) && $disable) ?'disabled' :'' }} {!! (isset($blur) ? 'v-on:blur="'.$blur.'"' : '') !!}>
    @if(isset($max))
        <span style="font-size: 0.8rem; position:absolute; right:1rem;" v-text="'Caracteres restantes: ' + ({{$max}} - {{$vue_var}}.length)"></span>
    @endif
</div>
