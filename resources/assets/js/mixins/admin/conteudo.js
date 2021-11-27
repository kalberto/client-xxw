let conteudo_mixin = {
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
				video: false,
				medias_conteudo: [],
				texto:'',
				perfil_id:''
			},
			identifiers: {
				'medias_conteudo': {
					'id': 'img_topo_id',
					'media': 'medias_conteudo'
				}
			},
			perfis:[],
			conteudos_relacionados: [],
			medias_relacionadas: [],
			categorias: [],
		}
	},
	computed: {
		selectedMediasConteudo() {
			return this.registro.medias_conteudo.map(medCon => medCon.media)
		}
	},
	created(){
		this.loadConteudosRelacionados();
		this.loadMediasRelacionadas();
		this.loadCategorias();
		this.loadPerfis();
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
			this.loadItens('perfis', "/admin/api/usuarios/perfis");
		},
		loadItens(variable, route,retorno = 'registros',confirm = false) {
			axios.get(this.base_url + route)
			.then(response => {
				this[variable] = response.data[retorno];
				if(confirm !== false)
					this[confirm] = true;
			});
		},
		callLoadAssets(pIdentifier) {
			this.media_root = pIdentifier;
			this.loadAssets();
		},
		selectMedia(pId, pIndex) {
			console.log('pId', pId)
			if(!this.registro.medias_conteudo.includes(pId)){
				let media = this.medias.find(media => media.id == pId)
				this.registro.medias_conteudo.push({ id: pId, video: media.tipo == 2, video_is_link: false, video_link: '', media })
			}
			removeErrorMessage($("input[name='" + this.identifiers[this.media_root].id + "']"));
		},
		removeMedia(pIdentifier) {
			this.registro[this.identifiers[pIdentifier].id] = '';
			this.registro[this.identifiers[pIdentifier].media] = false;
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
		}
	}
};
