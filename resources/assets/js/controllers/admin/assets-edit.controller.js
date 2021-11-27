const app = new Vue({
	el: "#app",
	mixins: [main_mixin, edit_mixin, message_mixin, media_mixin, asset_mixin],
	data: {},
	created(){
		this.load();
	},
	methods: {
		load() {
			axios.get(base_url + "/admin/api/assets/" + id).then(response => {
				// this.registro = response.data.registro;
				// this.registro.alias = 'medias_conteudo'
				this.registro = Object.assign({}, this.registro, response.data.registro)
			});
		},
		editAsset() {
			var data = new FormData();
			Object.keys(this.registro).forEach(key => data.append(key, this.registro[key]))
			this.editPost("/admin/assets/atualizar/" + id,false, data);
		}
		// editAsset() {
		// 	var data = new FormData();
		// 	Object.keys(this.registro).forEach(key => data.append(key, this.registro[key]))
		// 	this.editPost("/admin/assets/atualizar/" + id,false, data);
		// }
	}
});
