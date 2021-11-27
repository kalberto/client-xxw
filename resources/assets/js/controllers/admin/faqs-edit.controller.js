const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, faq_mixin],
	data: {},
	created(){
		this.init();
	},
	methods: {
		init() {
			this.initParent();
			this.load();
		},
		load() {
			axios.get(base_url + "/admin/api/faqs/" +id).then(response => {
				this.registro = response.data.registro;
				if(this.registro.perfil_id === null)
					this.registro.perfil_id = '';
			});
		},
		editRegistro() {
			this.edit("/admin/api/faqs/" + id, false);
		}
	}
});
