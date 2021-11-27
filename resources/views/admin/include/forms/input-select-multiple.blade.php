<div class="form-group col-sm-{{$input_size}}">
	<label class="control-label" :for="{{$input}}">{{$input_label}}</label>
	<v-select :id="{{$input}}" :clearable="{{isset($clear) ? $clear : 'true'}}" :name="{{$input}}" v-model="{{$vue_var}}" :options="{{$options}}" multiple label="{{$select_text}}" :reduce="{{$select_value}}"  style="position:relative;" @input="focusVSelect({{$input}})" placeholder="Selecione" ></v-select>
	<input :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" hidden>
</div>
