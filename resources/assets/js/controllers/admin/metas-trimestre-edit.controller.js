const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, meta_trimestre_mixin],
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
            axios.get(this.base_url + "/admin/api/metas-trimestre/" + id)
                .then(response => {
                    this.registro = response.data.registro;
                });
		},
		editRegistro() {
			this.edit("/admin/api/metas-trimestre/" + id, false);
		}
	}
});
