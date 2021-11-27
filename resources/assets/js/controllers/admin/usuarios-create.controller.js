const app = new Vue({
	el: "#app",
	mixins: [main_mixin, create_mixin, message_mixin, media_mixin, usuario_mixin],
	mounted() {
		this.init()
	},
	data: {
	},
	methods: {
		init() {
			this.initParent();
		},
		createUsuario() {
			this.create("/admin/api/usuarios", true);
		}
	}
});
