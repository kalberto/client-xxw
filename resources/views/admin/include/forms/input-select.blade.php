<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <select :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" class="c-select form-control" v-model="{{$vue_var}}" {{(isset($disable) && $disable) ? 'disabled' :'' }} >
        <option disabled value="">Escolha um item</option>
        <option v-for="{{$v_for}}" :value="{{$select_value}}">{{'{{'.$select_text}}}}</option>
    </select>
</div>
