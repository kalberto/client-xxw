<div class="subtitle-block"></div>
<article class="items-list-page">
    <div class="card items" v-if="registro.listas != '' && !currentListaVisible">
        <ul class="item-list striped">
            <li class="item item-list-header">
                <div class="item-row">
                    <div class="item-col item-col-header">
                        <div><span>Perfil</span></div>
                    </div>
                </div>
            </li>
            <li class="item" v-for="(lista,index) in registro.listas">
                <div class="item-row">
                    <div class="item-col pull-left ">
                        <div class="item-heading">Perfil</div>
                        <div>
                            {{-- <h4 class="item-title">@{{lista[index].perfis[0].nome}}</h4> --}}
                            {{-- <h4 class="item-title">@{{lista.perfis[0].nome}}</h4> --}}
                            <h4 class="item-title">@{{lista.perfil_nome}}</h4>
                        </div>
                        {{-- <div v-if="!registro.listas[index].perfil_nome">
                            <h4 class="item-title" v-if="lista.perfil != null">@{{lista.perfil.nome}}</h4>
                            <h5 class="item-title" v-if="lista.perfil == null">Registro Removido</h5>
                        </div>
                        <div v-else>
                            <h4 class="item-title">@{{lista.perfil_nome}}</h4>
                        </div> --}}
                    </div>
                    <div class="item-col fixed item-col-actions-normal">
                        <ul class="item-actions-list">
                            {{-- <li v-if="lista.perfis[0].nome == null">
                                <a class="edit" title="Lista removida" diseblade href="#" ><i class="fa fa-warning"></i></a>
                            </li> --}}
                            {{-- // removeLista(lista.id) --}}
                            <li>
                                <a class="edit" v-bind:title="'Editar ' + lista.perfil_nome" href="#" @click="editLista(index)"><i class="fa fa-pencil"></i></a>
                            </li>
                            <li>
                                <a class="remove" v-bind:title="'Remover ' + lista.perfil_nome" href="#" @click="removeLista(index)"><i class="fa fa-trash-o"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</article>

