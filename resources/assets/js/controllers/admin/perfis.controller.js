const app = new Vue({
    el:"#app",
    mixins: [main_mixin,message_mixin,listing_mixin,delete_mixin],
    data: {
        route: "/admin/api/perfis",
        deleteRoute:"/admin/api/perfis/",
        deleteLink: "",
        deletedIndex: 0
    },
    methods:{
        init(){
            this.load();
        }
    }
});
app.init();