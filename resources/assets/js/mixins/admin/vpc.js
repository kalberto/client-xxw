let vpc_mixin = {
	data: function () {
		return {
			registro: {
				id:null,
				comentarios:'',
			},
			vpc:{
				usuarios:{},
				dados:{},
				campos:{}
			}
		}
	},
	methods: {
		initParent(){
			this.registro.id = id;
		},
	}
};
