const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, categoria_mixin],
	data: {},
	created(){
		this.load();
	},
	methods: {
		load() {
			axios.get(base_url + "/admin/api/categorias/" +id).then(response => {
				this.registro = response.data.registro;
				if(this.registro.categoria_id === null || this.registro.categoria_id === undefined)
					this.registro.categoria_id = '';
			});
		},
		editCategoria() {
			this.edit("/admin/categorias/" + id, false);
		}
	}
});
