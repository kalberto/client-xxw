const app = new Vue({
	el: "#app",
	mixins: [main_mixin, create_mixin, message_mixin, faq_mixin],
	data: {	},
	mounted() {
		this.init()
	},
	methods: {
		init() {
			this.initParent();
		},
		createRegistro() {
			this.create("/admin/api/faqs",true);
		},
	}
});
