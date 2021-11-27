const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, errors_mixin, message_mixin, media_mixin, evento_mixin, evento_lista_mixin],
	data: {
	},
	created(){
		this.load();
	},
	methods: {
		load() {
			axios.get(base_url + "/admin/api/eventos/" +id).then(response => {
				this.registro = response.data.registro;
			});
		},
		editEvento() {
			this.edit("/admin/api/eventos/" + id, false);
		}
	}
});
