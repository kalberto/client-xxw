<div class="card card-success" v-cloak v-if="success">
    <div class="card-header">
        <div class="header-block">
            <p class="title"> Sucesso</p>
        </div>
    </div>
    <div class="card-block">
        <p>@{{ successMessage }}</p>
    </div>
</div>
<div class="card card-warning" v-cloak v-if="warning">
    <div class="card-header">
        <div class="header-block">
            <p class="title text-white"> Aviso</p>
        </div>
    </div>
    <div class="card-block">
        <p>@{{ warningMessage }}</p>
    </div>
</div>
<div class="card card-danger" v-cloak v-if="error">
    <div class="card-header">
        <div class="header-block">
            <p class="title text-white"> Erro</p>
        </div>
    </div>
    <div class="card-block">
        <p>@{{ errorMessage }}</p>
    </div>
</div>
