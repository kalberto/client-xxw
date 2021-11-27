const app = new Vue({
	el: "#app",
	mixins: [main_mixin, message_mixin, listing_mixin, delete_mixin],
	data: {
		route: "/admin/api/assets",
		deleteRoute: "/admin/api/assets/",
		deleteLink: "",
		deletedIndex: 0
	},
	methods: {
		init() {
			this.setSortPagination("created_at");
			this.load();
		}
	}
});
app.init();
