var app=new Vue({el:"#app",mixins:[main_mixin,media_mixin,create_mixin,message_mixin,categoria_mixin],data:{},methods:{createCategoria:function(){this.create("/admin/categorias",!0)}}});
