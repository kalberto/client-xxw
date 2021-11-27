let categoria_mixin = {
	data: function () {
		return {
			registro: {
				nome: '',
				slug: ''
			},
		}
	},
	methods: {
		checkSlug(){
			let route = base_url + "/admin/api/categorias/check-url" + (typeof(id) !== "undefined" ? ("/" + id) : '');
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
	},
};
