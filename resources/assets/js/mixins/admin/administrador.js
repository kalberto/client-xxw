let administrador_mixin = {
    data: function () {
		return {
            registro: {
                perfil_id: "",
                ativo: "",
                media:"",
                media_id:""
            },
            perfis: [],
        }
    },
    created(){
		this.loadPerfis();
	},
    methods: {
        loadPerfis(){
			axios.get(base_url + "/admin/api/perfis/all").then(response => {
				this.perfis = response.data.registros;
                console.log('response.data.registros', response.data.registros)
			}).catch(error => {
				this.perfis = [];
			});
		}
    }
};