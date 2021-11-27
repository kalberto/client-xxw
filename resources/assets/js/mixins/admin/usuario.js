let usuario_mixin = {
	data: function () {
		return {
			registro: {
				perfil_id:'',
				nivel_id:'',
                estado_id:'',
				cidade_id:'',
			},
			perfis:[],
			niveis:[],
			estados:[],
			cidades:[],
			moeda:{
				decimal: '.',
				thousands: '',
				prefix: 'R$ ',
				precision: 2,
				masked: false
			},
		}
	},
	watch:{
		'registro.perfil_id': function () {
			this.loadNiveis();
		},
        'registro.estado_id': function () {
            this.loadCidades();
        }
	},
	methods: {
		initParent(){
			this.loadPerfis();
			this.loadEstados();
		},
		loadPerfis() {
			this.loadItens('perfis', "/admin/api/usuarios/perfis");
		},
		loadNiveis() {
			this.loadItens('niveis', "/admin/api/usuarios/perfis/" + this.registro.perfil_id + "/niveis");
		},
		loadEstados(){
            this.loadItens('estados', "/admin/api/estados");
		},
		loadCidades(){
            this.loadItens('cidades', "/admin/api/estados/" + this.registro.estado_id + "/cidades");
		},
		loadItens(variable, route,retorno = 'registros',confirm = false) {
            axios.get(this.base_url + route)
                .then(response => {
                	this[variable] = response.data[retorno];
                	if(confirm !== false)
                		this[confirm] = true;
                });
		}
	}
};
