var app=new Vue({el:"#app",mixins:[main_mixin,create_mixin,message_mixin],data:{registro:{mod_adm_permissao:[]},permissions:[],modulos:[]},methods:{load:function(){$.ajax({url:base_url+"/admin/api/mod_adm_permissao",method:"GET"}).done(function(a){app.$data.modulos=a.modulos,app.$data.modulos.forEach(function(a,o){1===a.obrigatorio&&a.mod_adm_permissao.forEach(function(a){app.$data.registro.mod_adm_permissao[a.id]="true"})})})},createPerfil:function(){this.create("/admin/api/perfis",!0)}}});app.load();