var app = new Vue({
  el: "#app",
  mixins: [main_mixin, message_mixin, listing_mixin, delete_mixin],
  data: {
    route: "/admin/api/banners",
    deleteRoute: "/admin/banners/deletar/",
    deleteLink: "",
    deletedIndex: 0
  },
  methods: {
    init: function init() {
      this.setSortPagination("-published_at");
      this.load();
    }
  }
});
app.init();
