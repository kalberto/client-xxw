let meta_trimestre_mixin = {
	data: function () {
		return {
			registro: {
				perfil_id:'',
				nivel_id:'',
				ano:'',
				trimestre:'',
				meta_mes:'',
				meta_trimestre:'',
				desconto:'',
				vpc:'',
				rebate:'',
			},
			perfis:[],
			niveis:[],
			anos:['2015','2016','2017','2018','2019','2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030','2031','2032','2033','2034','2035'],
			trimestres:[{valor:1,nome:'Primeiro'},{valor:2,nome:'Segundo'},{valor:3,nome:'Terceiro'},{valor:4,nome:'Quarto'}],
			moeda:{
				decimal: ',',
				thousands: '.',
				prefix: 'R$ ',
				precision: 2,
				masked: false
			},
		}
	},
	watch:{
		'registro.perfil_id': function () {
			this.loadNiveis();
		}
	},
	methods: {
		initParent(){
			this.loadPerfis();
		},
		loadPerfis() {
			this.loadItens('perfis', "/admin/api/usuarios/perfis");
		},
		loadNiveis() {
			this.loadItens('niveis', "/admin/api/usuarios/perfis/" + this.registro.perfil_id + "/niveis");
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
