var usuario_mixin={data:function(){return{registro:{perfil_id:"",nivel_id:"",estado_id:"",cidade_id:""},perfis:[],niveis:[],estados:[],cidades:[],moeda:{decimal:".",thousands:"",prefix:"R$ ",precision:2,masked:!1}}},watch:{"registro.perfil_id":function(){this.loadNiveis()},"registro.estado_id":function(){this.loadCidades()}},methods:{initParent:function(){this.loadPerfis(),this.loadEstados()},loadPerfis:function(){this.loadItens("perfis","/admin/api/usuarios/perfis")},loadNiveis:function(){this.loadItens("niveis","/admin/api/usuarios/perfis/"+this.registro.perfil_id+"/niveis")},loadEstados:function(){this.loadItens("estados","/admin/api/estados")},loadCidades:function(){this.loadItens("cidades","/admin/api/estados/"+this.registro.estado_id+"/cidades")},loadItens:function(i,s){var d=this,t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"registros",a=arguments.length>3&&void 0!==arguments[3]&&arguments[3];axios.get(this.base_url+s).then(function(s){d[i]=s.data[t],!1!==a&&(d[a]=!0)})}}};
