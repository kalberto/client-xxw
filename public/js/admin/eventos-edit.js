var app=new Vue({el:"#app",mixins:[main_mixin,edit_mixin,errors_mixin,message_mixin,media_mixin,evento_mixin,evento_lista_mixin],data:{},created:function(){this.load()},methods:{load:function(){var i=this;axios.get(base_url+"/admin/api/eventos/"+id).then(function(e){i.registro=e.data.registro})},editEvento:function(){this.edit("/admin/api/eventos/"+id,!1)}}});