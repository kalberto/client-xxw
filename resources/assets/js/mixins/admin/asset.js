let asset_mixin = {
	data: function () {
		return {
			registro:{
				alias:'medias_conteudo',
				tipo: '',
				legenda: '',
				video_is_link: '',
				video_link: '',
				file: ''
			},
			tipos:[
				{'id': 1, 'nome':'Imagem'},
				{'id': 2, 'nome':'Vídeo'}
			],
		}
	},
	created(){
	},
	methods: {
        addFile : function () {
            this.registro.file = this.$refs.file.files[0];
        }
	}
};
