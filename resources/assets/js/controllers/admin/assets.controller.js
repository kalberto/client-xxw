const app = new Vue({
    el: "#app",
    mixins: [main_mixin, message_mixin, listing_mixin, delete_mixin, asset_mixin],
    data: {
        route: "/admin/api/assets",
        deleteRoute:"/admin/api/assets/",
        deleteLink: "",
        deletedIndex: 0,
        showFilters:false,
    },
    mounted(){
      this.init();
    },
    methods: {
        init(){
            this.setSortPagination("-created_at");
            this.load();
        }
    }
});