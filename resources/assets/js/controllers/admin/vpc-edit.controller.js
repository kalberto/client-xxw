const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, media_mixin, vpc_mixin,delete_mixin],
	mounted() {
		this.init()
	},
	data: {
		upload_message:"Mensagem upload",
		upload_error:false,
		show_upload_erros:false,
		upload_errors:[],
		upload_sucesso:false,
		carregando_upload:false,
		deleteRoute: "/admin/api/vpc/anexo-admin/",
		deletedIndex: 0,
	},
	methods: {
        init(){
        	this.load();
            this.initParent();
        },
		load(){
            axios.get(this.base_url + "/admin/api/vpc/" + id)
                .then(response => {
                    this.vpc = response.data.registro;
                });
		},
		getStatusCor(status){
			if(status === "APROVADO")
				return "ativo";
			if(status === "PAGO")
				return "ativo";
			if(status === "RECUSADO")
				return "desativado";
			if(status === "PENDENTE" || status === "EDIÇÃO")
				return "pendente";
			return "outro";
		},
		getDocumento(documento) {
			if(!documento)
				return 'Não possui';
			if(documento.length === 14)
				return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
			return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
		},
		aprovarVPC() {
			this.registro.status = "APROVADO";
			this.edit("/admin/api/vpc/" + id, false);
		},
		reprovarVPC() {
			this.registro.status = "REPROVADA";
			this.edit("/admin/api/vpc/" + id, false);
		},
		revisarVPC(){
			this.registro.status = "REVISÃO";
			this.edit("/admin/api/vpc/" + id, false);
		},
		recusarVPC(){
			this.registro.status = "COMPROVAÇÃO";
			this.edit("/admin/api/vpc/" + id, false);
		},
		pagarVPC(){
			this.registro.status = "PAGO";
			this.edit("/admin/api/vpc/" + id, false);
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
				if(response.body.anexos_admin){
					app.$data.vpc.anexos_admin = response.body.anexos_admin;
				}
			},function(response){
				if(response.body.anexos_admin){
					app.$data.vpc.anexos_admin = response.body.anexos_admin;
				}
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
				this.upload(this.$refs.file.files[0],'/admin/api/vpc/' + id + '/upload');
			}else{
				alert("Selecione um arquivo.");
			}
		},
		openModalAddAnexoAdmin(){
			this.$refs.modalUploadAnexoAdmin.click();
			this.resetUploadVars();
		},
		resetUploadVars(){
			this.upload_sucesso = false;
			this.upload_errors = [];
			this.show_upload_erros = false;
			this.upload_error = false;
		},
		toggleErrorsUpload(){
			this.show_upload_erros = !this.show_upload_erros;
		},
	}
});
