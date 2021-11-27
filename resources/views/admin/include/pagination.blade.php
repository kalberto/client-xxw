<nav class="text-right">
    <ul class="pagination" v-if="paginator !== false">
        <li class="page-item" v-if="paginator[0] != {{$paginador}}.current_page">
            <a class="page-link" v-on:click="goToPage({{$paginador}}.current_page-1)" title="Anterior"> Anterior </a>
        </li>
        <li class="page-item" v-for="page in paginator" :class="{active: page == {{$paginador}}.current_page}">
            <a class="page-link" v-on:click="goToPage(page)"> @{{page}} </a>
        </li>
        <li class="page-item" v-if="paginator[paginator.length - 1] != {{$paginador}}.current_page">
            <a class="page-link" v-on:click="goToPage({{$paginador}}.current_page+1)" title="Próxima"> Próximo </a>
        </li>
    </ul>
</nav>
