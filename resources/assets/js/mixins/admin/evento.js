let evento_mixin = {
	data: function () {
		return {
			registro: {
				nome:'',
				titulo:'',
				slug:'',
				data_inicio:'',
				data_fim:'',
				autor:'',
				conteudos_relacionados: [],
				categoria_id: '',
				perfil_id: '',
				video: false,
				medias_conteudo: [],
				niveis:[],
				listas:[],
				niveis_id:[],
				perfis_id:[],
				texto:''
			},
			conteudos_relacionados:[],
			medias_relacionadas: [],
			categorias: [],
			perfis:[],
			niveis:[],
		}
	},
	watch: {
        'registro.perfis_id': function(){
			if(this.registro.perfis_id && this.registro.perfis_id.length > 0){
				this.loadNiveis2();
			}else{
				this.niveis_id = [];
				this.registro.niveis_id = [];
			}
        }
	},
	created(){
		this.loadConteudosRelacionados();
		this.loadMediasRelacionadas();
		this.loadCategorias();
	},
	methods: {
		loadConteudosRelacionados(){
			axios.get(base_url + "/admin/api/conteudos/select/" + (typeof id !== "undefined" && id ? id : "")).then(response => {
				this.conteudos_relacionados = response.data.registros;
			}).catch(error => {
				this.conteudos_relacionados = [];
			});
		},
		loadMediasRelacionadas(){
			axios.get(base_url + "/admin/api/medias/all/" + (typeof id !== "undefined" && id ? id : "")).then(response => {
				this.medias_relacionadas = response.data.registros;
			}).catch(error => {
				this.medias_relacionadas = [];
			});
		},
		loadCategorias(){
			axios.get(base_url + "/admin/api/categorias/all").then(response => {
				this.categorias = response.data.registros;
			}).catch(error => {
				this.categorias = [];
			});
		},
		loadPerfis() {
			axios.get(base_url + "/admin/api/usuarios/perfis").then(response => {
				this.perfis = response.data.registros;
			}).catch(error => {
				this.perfis = [];
			});
		},
		loadNiveis2(){
            let perfisString = "";
            for(let i = 0; i < this.registro.perfis_id.length; i++){
                if(i !== 0)
                    perfisString += ",";
                perfisString += this.registro.perfis_id[i];
            }
            axios.get(base_url + "/admin/api/usuarios/niveis-by-perfis/"+perfisString).then(response => {
				this.niveis = response.data.registros;
				this.updateNiveisRegistro();
			}).catch(error => {
				this.niveis = [];
			});
        },
		updateSlug(){
			this.registro.slug = this.registro.titulo;
		},
		checkSlug(){
			let route = base_url + "/admin/api/conteudos/check-url" + (typeof(id) !== "undefined" ? ("/" + id) : '');
			axios.post(route,this.registro).then(response => {
				if(response.data.field){
					this.registro.slug = response.data.field;
				}
				if(response.data.validate)
					this.insertSuccess(response.data.validate);
			}).catch(error => {
				if(error.response.data.error_validate){
					let errors = error.response.data.error_validate;
					if(this.errors)
						this.errors = errors;
					if(this.has_errors)
						this.has_errors = [];
					this.insertErrors(errors);
				}
			});
		},
		updateNiveisRegistro(){
			let toBeRemoved = [];
			if(this.registro.niveis_id){
				for(let i = 0; i< this.registro.niveis_id.length; i++){
					let found = false;
					for(let x = 0; x < this.niveis.length; x++){
						if(this.niveis[x].id == this.registro.niveis_id[i]){
							found = true;
							break;
						}
					}
					if(!found){
						toBeRemoved.push(i);
					}
				}
				for(let i = (toBeRemoved.length - 1); i >= 0  ; i--){
					this.registro.niveis_id.splice(toBeRemoved[i],1);
				}
			}
		}
	}
};
