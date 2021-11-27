const app = new Vue({
	el: "#app",
	mixins: [main_mixin,message_mixin,listing_mixin,delete_mixin,delete_mixin],
	data: {
		route: "/admin/api/categorias",
		deleteRoute: "/admin/categorias/",
		deleteLink: "",
		deletedIndex: 0
	},
	mounted(){
		this.init();
	  },
	methods: {
		init() {
			this.setSortPagination("-updated_at");
			this.load();
		}
	}
});
