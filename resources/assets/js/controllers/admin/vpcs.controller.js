const app = new Vue({
	el: "#app",
	mixins: [main_mixin, message_mixin, listing_mixin],
	data: {
		route: "/admin/api/vpc",
		status:[
            {'status':'PENDENTE'},{'status':'EDIÇÃO'},{'status':'AUTORIZADO'},{'status':'RECUSADO'}
        ],
		carregando_upload:false,
		upload_message:"Mensagem upload",
		upload_error:false,
		show_upload_erros:false,
		upload_errors:[],
		upload_sucesso:false,
	},
    computed:{
	  totalUsuarios: function () {
          if(this.registros && this.registros.total)
              return this.registros.total;
          else
              return 0;
      }
    },
	methods: {
		init() {
			this.pagination.conta = '';
			this.load();
		},
		getParams(){
			return "?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&status=" + this.pagination.state
		},
		toggleErrorsUpload(){
			this.show_upload_erros = !this.show_upload_erros;
		},
		getDocumento(documento) {
			if(!documento)
				return 'Não possui';
			if(documento.length === 14)
				return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
			return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
		},
		getStatusCor(status){
			if(status === 'PENDENTE')
				return "pendente";
			if(status === 'EDIÇÃO')
				return "pendente";
			if(status === 'recusado')
				return "desativado";
			if(status === 'AUTORIZADO')
				return "ativo";
			return "desativado";
		},
	}
});
app.init();
