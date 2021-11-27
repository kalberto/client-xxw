const app = new Vue({
	el: "#app",
	mixins: [main_mixin, media_mixin, create_mixin, message_mixin, asset_mixin],
	data: {	},
	methods: {
		createAsset() {
			var data = new FormData();
			Object.keys(this.registro).forEach(key => data.append(key, this.registro[key]))
			// data.append('file', this.registro.file);
			// data.append('alias', this.registro.alias);
			this.create("/admin/assets/salvar",true,data);
		},
	}
});
