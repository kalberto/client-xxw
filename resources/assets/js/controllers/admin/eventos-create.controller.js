const app = new Vue({
	el: "#app",
	mixins: [main_mixin, create_mixin, errors_mixin, message_mixin, media_mixin, evento_mixin, evento_lista_mixin],
	data: {},
	methods: {
		createEvento() {
			this.create("/admin/api/eventos", true);
		}
	}
});
