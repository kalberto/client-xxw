let media_mixin = {
	data: function () {
		return {
			media_root: '',
			select_media: '',
			medias: [],
		}
	},
	methods: {
		setSelectedMedia(pId) {
			this.select_media = pId;
		},
		getMediaThumbPath: function getMediaThumbPath(pMedia) {
			if(pMedia !== undefined && pMedia !== null && pMedia.media_root !== undefined && pMedia.media_root !== null) {
				if(pMedia.video_is_link == 1)
					return this.base_url + '/images/admin/656f77.png';
				else 
					return this.base_url + '/' + pMedia.media_root.path + 'thumb/' + (pMedia.tipo === 1 ? pMedia.file : pMedia.thumbnail);
			}else 
				return '';
		},
		loadAssets: function loadAssets() {
			this.select_media = '';
			axios.get(base_url + "/admin/api/media/" + this.media_root).then(response => {
				if(response.data.registros !== undefined){
					this.medias = response.data.registros;
					this.medias.forEach(function (element) {
						if(element.tipo === 2)
							element.file = element.thumbnail;
					});
				}
			});
		}
	}
};
