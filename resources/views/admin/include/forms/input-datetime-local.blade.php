<div class="form-group col-sm-{{$input_size}}">
    <label class="control-label" :for="{{$input}}">{{$input_label}}</label>
    <input :id="{{$input}}" :name="{{$input}}" :ref="{{$input}}" class="form-control" autocomplete="off" placeholder="Data de Publicação" type="text" v-model="registro.updated_at" {{(isset($disable) && $disable) ?'disabled' :'' }}>
</div>

