var administrador_mixin={data:function(){return{registro:{perfil_id:"",ativo:"",media:"",media_id:""},perfis:[]}},created:function(){this.loadPerfis()},methods:{loadPerfis:function(){var i=this;axios.get(base_url+"/admin/api/perfis/all").then(function(a){i.perfis=a.data.registros,console.log("response.data.registros",a.data.registros)}).catch(function(a){i.perfis=[]})}}};