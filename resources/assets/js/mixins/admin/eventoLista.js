let evento_lista_mixin = {
	data: function () {
		return {
            listas: [],
            perfis: [],
            niveis: [],
            currentLista: {
                perfil_id: '',
                perfil_nome: '',
                niveis_id: []
            },
            lista_being_created: false,
            currentListaVisible: false,
            currentListaVisibleIndex: false
		}
	},
	created(){
        this.loadPerfis();
        // this.loadNiveis();
    },
    watch:{
		'currentLista.perfil_id':function(){
			this.loadNiveis();
		}
	},
	methods: {
        loadPerfis() {
			axios.get(base_url + "/admin/api/usuarios/perfis").then(response => {
				this.perfis = response.data.registros;
			}).catch(error => {
				this.perfis = [];
			});
		},
        loadNiveis(){
			axios.get(base_url + "/admin/api/usuarios/niveis/"+this.currentLista.perfil_id).then(response => {
				this.niveis = response.data.registros;
			}).catch(error => {
				this.niveis = [];
			});
		},
        showLista() {
            this.currentLista = {
                perfil_id: '',
                perfil_nome: '',
                niveis_id: []
            };
            this.lista_being_created = true;
            this.currentListaVisible = true;
        },
        closeLista() {
            this.currentListaVisible = false;
        },
        syncPerfil($event) {
            this.currentLista.perfil = this.perfis.find(perfil => perfil.id == $event.target.value)
            this.currentLista.perfil_nome = this.currentLista.perfil.nome
            console.log('$event', $event)
        },
        stopAddLista() {
            this.currentListaVisible = false;
            this.currentListaVisibleIndex = false;
            this.currentLista = {
                perfil_id: '',
                perfil_nome: '',
                niveis_id: []
            };
        },
        editLista(pIndex) {
            // console.log('index',pIndex)
            //     f = this.registro.listas.find(lista => lista.id == pIndex)
            // console.log('find',f)
            // console.log('registro',this.registro.listas[pIndex].perfis[0].id)
            // this.currentLista = this.registro.listas.find(lista => lista.id == pIndex+1).perfis.map(item => Object.assign(
            // this.currentLista = this.registro.listas[pIndex].perfil.map(item => Object.assign(
            //     {},item, { perfil_id: item.id ? item.id : '' }
            // ))
            // this.currentLista.niveis_id = this.registro.listas[pIndex].niveis_id.map(item => item.id)
            // this.currentLista.perfil_id = this.registro.listas[pIndex].perfil.id
            // this.currentLista.perfil_nome = this.registro.listas[pIndex].perfil.nome
            // this.currentLista.niveis_id = this.registro.listas[pIndex].niveis_id
            this.currentLista.perfil_id = this.registro.listas[pIndex].perfil_id
            this.currentLista.perfil_nome = this.registro.listas[pIndex].perfil_nome
            this.currentLista.niveis_id = this.registro.listas[pIndex].niveis_id
            this.lista_being_created = false;
            this.currentListaVisible = true;
            this.currentListaVisibleIndex = pIndex;
        },
        addLista() {
            let error = false;
            if(this.currentLista.perfil_id == '') {
                error = true;
                alert("Campo obrigatório.");
            }
            if(error !== true){
                if(this.currentListaVisibleIndex === false) {
                    this.registro.listas.push(this.currentLista);
                }else {
                    this.registro.listas[this.currentListaVisibleIndex] = this.currentLista;
                }
                this.stopAddLista();
            }
        },
        removeLista(pIndex) {
            this.registro.listas.splice(pIndex, 1);
        }
	}
};