<div class="title-search-block">
    <div class="title-block">
        <div class="row">
            <div class="col-md-6">
                <h3 class="title">
                        <a href="{{route($route)}}" class="btn btn-primary btn-sm rounded-s"> Adicionar Novo </a>
                    @if(isset($dropdown) && $dropdown == true)
                    <div class="action dropdown">
                        <button class="btn  btn-sm rounded-s btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Mais Ações </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item" href="#">
                                <i class="fa fa-pencil-square-o icon"></i>
                                Marcar como Rascunho
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#confirm-modal">
                                <i class="fa fa-close icon"></i>
                                Apagar
                            </a>
                        </div>
                    </div>
                    @endif
                </h3>
            </div>
        </div>
    </div>
    <div class="items-search">
        <form class="form-inline" v-on:submit="search($event)">
            <div class="input-group">
                <input type="text" id="search_input" v-model="pagination.q" class="form-control boxed rounded-s" placeholder="Busque por">
                <span class="input-group-btn">
                    <button class="btn btn-secondary rounded-s botao-busca" type="button" @click="search($event)">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>
