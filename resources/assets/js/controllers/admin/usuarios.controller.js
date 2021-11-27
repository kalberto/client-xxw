const app = new Vue({
	el: "#app",
	mixins: [main_mixin, message_mixin, listing_mixin, delete_mixin],
	data: {
		route: "/admin/api/usuarios",
		deleteRoute: "/admin/usuarios/",
		deleteLink: "",
		deletedIndex: 0,
		status:[
			{'status':1,'texto_status':'Ativo'},{'status':0,'texto_status':'Desativado'}
		],
		contas:[
			{'valor':1,'texto':'Atualizada'},{'valor':0,'texto':'Pendente'}
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
			this.setSortPagination("-updated_at");
			this.load();
		},
		getParams(){
			return "?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&q=" + this.pagination.q + "&sort=" + this.pagination.sort
				+ "&fields=" + this.pagination.fields + "&state=" + this.pagination.state + "&conta=" + this.pagination.conta;
		},
		getUsuarios() {
			if(this.registro)
				return this.registro.total;
			else
				this.totalUsuarios = 0;
		},
		getDocumento(documento) {
			if(!documento)
				return 'Não possui';
			if(documento.length === 14)
				return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
			return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
		},
		openModalUpload(){
			this.$refs.modalUploadPlanilha.click();
			this.resetUploadVars();
		},
		openModalUploadMeta(){
			this.$refs.modalUploadPlanilhaMeta.click();
			this.resetUploadVars();
		},
		openModalUploadClassificacao(){
			this.$refs.modalUploadPlanilhaClassificacao.click();
			this.resetUploadVars();
		},
		openModalUploadSaldoVpc(){
			this.$refs.modalUploadPlanilhaSaldoVpc.click();
			this.resetUploadVars();
		},
		upload(file, route){
			this.resetUploadVars();
			let data = new FormData();
			data.append('file',file);
			this.carregando_upload = true;
			this.$http.post(base_url + route,data,{
				headers: {
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")
				},
				emulateJSON:true
			}).then(function(response){
				app.$data.carregando_upload = false;
				app.$data.upload_message = response.body.msg;
				app.$data.upload_sucesso = true;
			},function(response){
				app.$data.carregando_upload = false;
				if(response.body.errors)
					app.$data.upload_errors = response.body.errors;
				else
					app.$data.upload_errors = [];
				app.$data.upload_message = response.body.msg;
				app.$data.upload_error = true;
			});
		},
		uploadArquivo(){
			if(this.$refs.file.files !== undefined && this.$refs.file.files.length > 0){
				this.upload(this.$refs.file.files[0],'/admin/api/usuarios/upload');
			}else{
				alert("Selecione um arquivo.");
			}
		},
		uploadArquivoMeta(){
			if(this.$refs.file_meta.files != undefined && this.$refs.file_meta.files.length > 0){
				this.upload(this.$refs.file_meta.files[0],'/admin/api/usuarios/upload/meta');
			}else{
				alert("Selecione um arquivo..");
			}
		},
		uploadArquivoClassificacao(){
			if(this.$refs.file_classificacao.files != undefined && this.$refs.file_classificacao.files.length > 0){
				this.upload(this.$refs.file_classificacao.files[0],'/admin/api/usuarios/upload/classificacao');
			}else{
				alert("Selecione um arquivo..");
			}
		},
		uploadArquivoSaldoVpc(){
			if(this.$refs.file_saldo_vpc.files != undefined && this.$refs.file_saldo_vpc.files.length > 0){
				this.upload(this.$refs.file_saldo_vpc.files[0],'/admin/api/usuarios/upload/saldo-vpc');
			}else{
				alert("Selecione um arquivo..");
			}
		},
		toggleErrorsUpload(){
			this.show_upload_erros = !this.show_upload_erros;
		},
		resetUploadVars(){
			this.upload_sucesso = false;
			this.upload_errors = [];
			this.show_upload_erros = false;
			this.upload_error = false;
		},
		getAtivoCor(ativo){
			if(ativo === true)
				return "ativo";
			return "desativado";
		},
	}
});
app.init();
