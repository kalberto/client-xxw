var app=new Vue({el:"#app",mixins:[main_mixin,message_mixin,listing_mixin,delete_mixin],data:{route:"/admin/api/administradores",deleteRoute:"/admin/administradores/deletar/",deleteLink:"",deletedIndex:0},methods:{init:function(){this.setSortPagination("nome"),this.load()}}});app.init();
