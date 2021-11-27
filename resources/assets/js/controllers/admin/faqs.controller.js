const app = new Vue({
	el: "#app",
	mixins: [main_mixin,message_mixin,listing_mixin,delete_mixin,delete_mixin],
	data: {
		route: "/admin/api/faqs",
		deleteRoute: "/admin/api/faqs/",
		deleteLink: "",
		deletedIndex: 0,
	},
	created(){
		this.init();
	},
	methods: {
		init() {
			this.setSortPagination("-created_at");
			this.load();
		}
	}
});
