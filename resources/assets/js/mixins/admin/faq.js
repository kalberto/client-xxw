let faq_mixin = {
	data: function () {
		return {
			registro: {
				perfil_id:'',
				pergunta: '',
				resposta: ''
			},
			perfis:[],
		}
	},
	methods: {
		initParent(){
			this.loadPerfis();
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
		}
	},
};
