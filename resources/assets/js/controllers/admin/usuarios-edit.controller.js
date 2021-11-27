const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, media_mixin, usuario_mixin],
	mounted() {
		this.init()
	},
	data: {
	},
	methods: {
        init() {
        	this.load();
            this.initParent();
        },
		load(){
            axios.get(this.base_url + "/admin/api/usuarios/" + documento)
                .then(response => {
                    this.registro = response.data.registro;
                });
		},
		editUsuario() {
			this.edit("/admin/api/usuarios/" + documento, false);
		}
	}
});
