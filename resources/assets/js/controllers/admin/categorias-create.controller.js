const app = new Vue({
	el: "#app",
	mixins: [main_mixin,media_mixin, create_mixin, message_mixin, categoria_mixin],
	data: {	},
	methods: {
		createCategoria() {
			this.create("/admin/categorias",true);
		},
	}
});
