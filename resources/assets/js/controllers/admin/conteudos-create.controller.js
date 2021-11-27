const app = new Vue({
	el: "#app",
	mixins: [main_mixin, create_mixin, errors_mixin, message_mixin, media_mixin, conteudo_mixin],
	data: {},
	methods: {
		createConteudo() {
			this.create("/admin/api/conteudos", true);
		}
	}
});
