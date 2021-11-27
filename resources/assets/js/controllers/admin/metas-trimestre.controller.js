const app = new Vue({
	el: "#app",
	mixins: [main_mixin, message_mixin, listing_mixin],
	data: {
		route: "/admin/api/metas-trimestre",
		deleteRoute: "/admin/metas-trimestre/",
		deleteLink: "",
		deletedIndex: 0,
		perfis:[],
		niveis:[],
		trimestre:[
            {'nome':'Primeiro','value':1},{'nome':'Segundo','value':2},{'nome':'Terceiro','value':3},{'nome':'Quarto','value':4}
        ],
	},
	mounted(){
		this.init();
	},
    computed:{
	    totalRegistros: function () {
          if(this.registros && this.registros.total)
              return this.registros.total;
          else
              return 0;
      }
    },
	methods: {
		init() {
			this.pagination.perfil = '';
			this.pagination.nivel = '';
			this.pagination.trimestre = '';
			this.setSortPagination("-created_at");
			this.loadPerfis()
			this.load();
		},
		loadMetasWithPerfil(){
			this.pagination.nivel = '';
			this.loadNiveis();
			this.load();
		},
		getParams(){
			return "?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&q=" + this.pagination.q + "&sort=" + this.pagination.sort
				+ "&fields=" + this.pagination.fields + "&perfil=" + this.pagination.perfil + "&nivel=" + this.pagination.nivel + "&trimestre=" + this.pagination.trimestre;
		},
		initParent(){
			this.loadPerfis();
		},
		loadPerfis() {
			this.loadItens('perfis', "/admin/api/usuarios/perfis");
		},
		loadNiveis() {
			this.loadItens('niveis', "/admin/api/usuarios/perfis/" + this.pagination.perfil + "/niveis");
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
});
