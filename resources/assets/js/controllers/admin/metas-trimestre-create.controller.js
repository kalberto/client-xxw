const app = new Vue({
	el: "#app",
	mixins: [main_mixin, create_mixin, message_mixin, meta_trimestre_mixin],
	mounted() {
		this.init()
	},
	data: {
	},
	methods: {
		init() {
			this.initParent();
		},
		createRegistro() {
			this.create("/admin/api/metas-trimestre", true);
		}
	}
});
