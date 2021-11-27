<div class="subtitle-block"></div>
<div class="form-group row">
    <div class="col-sm-1 col-sm-offset-2">
        <div v-if="sending">@{{sendingMessage}}</div>
        <button v-else type="submit" class="btn btn-primary" @click="{{$save_method}}"> Salvar </button>
    </div>
    <div class="col-sm-1">
        @if(!isset($back_method))
            <button type="button" class="btn btn-secondary-outline"><a href="{{route($route_back)}}" v-if="!sending"> Voltar </a></button>
        @else
            <button type="button" class="btn btn-secondary-outline" style="color:black" @click="{{$back_method}}" v-if="!sending"> Voltar </button>
        @endif
    </div>
</div>