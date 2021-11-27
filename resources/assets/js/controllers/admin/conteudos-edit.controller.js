const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, errors_mixin, message_mixin, media_mixin, conteudo_mixin],
	data: {
		uploadFiles:[],
		sendingFile:false,
		errorFile:false,
		successFile:false,
	},
	created(){
		this.load();
	},
	methods: {
		load() {
			axios.get(base_url + "/admin/api/conteudos/" +id).then(response => {
				this.registro = response.data.registro;
				if(this.registro.perfil_id === null)
					this.registro.perfil_id = '';
			});
		},
		editConteudo() {
			this.editNoReload("/admin/api/conteudos/" + id, false);
			this.sendDocumentos();
		},
		addDocumentos(){
			this.uploadFiles = this.$refs.documentos.files;
		},
		sendDocumentos(){
			if(this.uploadFiles.length > 0){
				let data = new FormData();
				for (let i = 0; i < this.uploadFiles.length; i++) {
					data.append('documentos[]', this.uploadFiles[i]);
				}
				this.sendingFile = true;
				this.errorFile = false;
				this.successFile = false;
				this.sendingMessage = "Enviando...";
				axios.post(base_url + "/admin/api/conteudos/" + id + "/save-files",data).then(response => {
					this.successMessage = response.data.msg;
					this.successFile = true;
					setTimeout(() => {
						this.successFile = false;
					},3000);
				}).catch(error => {
					this.errorFile = true;
					this.errorMessage = error.response.data.msg;
					if(error.response.data.error_validate){
						let errors = error.response.data.error_validate;
						if(this.errors)
							this.errors = errors;
						if(this.has_errors)
							this.has_errors = [];
						this.insertErrors(errors);
					}
				}).finally(() => {
					this.sendingFile = false;
					this.scrollToTop();
				});
			}else{
				this.successFile = true;
				setTimeout(() => {
					this.successFile = false;
				},5000);
			}

		},
		deleteFile(id,index){
			let deleteLink = this.base_url + "/admin/api/conteudos-documentos/" + id;
			if(confirm("Tem certeza que deseja deletar o arquivo?")){
				axios.delete(deleteLink).then(response => {
					this.registro.documentos.splice(index,1);
					console.log('O arquivo foi deletado');
				}).catch(error => {
					if(error.response.data.msg)
						console.log(error.response.data.msg);
					else
						console.log('Ocorreu um erro ao deletar o arquivo');
				});
			}
		}
	}
});
