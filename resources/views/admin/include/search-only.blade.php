<div class="title-search-block" style="min-height:49px;">
    <div class="items-search without">
        <form class="form-inline" v-on:submit="search($event)">
            <div class="input-group">
                <input type="text" id="search_input" v-model="pagination.q" class="form-control boxed rounded-s" placeholder="Busque por">
                <span class="input-group-btn">
                    <button class="btn btn-secondary rounded-s botao-busca" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>
<div class="card card-warning" v-cloak v-if="warning">
    <div class="card-header">
        <div class="header-block">
            <p class="title text-white"> Aviso</p>
        </div>
    </div>
    <div class="card-block">
        <p>@{{ warning_message }}</p>
    </div>
</div>
