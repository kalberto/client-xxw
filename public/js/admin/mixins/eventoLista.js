var evento_lista_mixin={data:function(){return{listas:[],perfis:[],niveis:[],currentLista:{perfil_id:"",perfil_nome:"",niveis_id:[]},lista_being_created:!1,currentListaVisible:!1,currentListaVisibleIndex:!1}},created:function(){this.loadPerfis()},watch:{"currentLista.perfil_id":function(){this.loadNiveis()}},methods:{loadPerfis:function(){var i=this;axios.get(base_url+"/admin/api/usuarios/perfis").then(function(t){i.perfis=t.data.registros}).catch(function(t){i.perfis=[]})},loadNiveis:function(){var i=this;axios.get(base_url+"/admin/api/usuarios/niveis/"+this.currentLista.perfil_id).then(function(t){i.niveis=t.data.registros}).catch(function(t){i.niveis=[]})},showLista:function(){this.currentLista={perfil_id:"",perfil_nome:"",niveis_id:[]},this.lista_being_created=!0,this.currentListaVisible=!0},closeLista:function(){this.currentListaVisible=!1},syncPerfil:function(i){this.currentLista.perfil=this.perfis.find(function(t){return t.id==i.target.value}),this.currentLista.perfil_nome=this.currentLista.perfil.nome,console.log("$event",i)},stopAddLista:function(){this.currentListaVisible=!1,this.currentListaVisibleIndex=!1,this.currentLista={perfil_id:"",perfil_nome:"",niveis_id:[]}},editLista:function(i){this.currentLista.perfil_id=this.registro.listas[i].perfil_id,this.currentLista.perfil_nome=this.registro.listas[i].perfil_nome,this.currentLista.niveis_id=this.registro.listas[i].niveis_id,this.lista_being_created=!1,this.currentListaVisible=!0,this.currentListaVisibleIndex=i},addLista:function(){var i=!1;""==this.currentLista.perfil_id&&(i=!0,alert("Campo obrigatório.")),!0!==i&&(!1===this.currentListaVisibleIndex?this.registro.listas.push(this.currentLista):this.registro.listas[this.currentListaVisibleIndex]=this.currentLista,this.stopAddLista())},removeLista:function(i){this.registro.listas.splice(i,1)}}};