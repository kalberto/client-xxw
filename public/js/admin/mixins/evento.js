var evento_mixin={data:function(){return{registro:{nome:"",titulo:"",slug:"",data_inicio:"",data_fim:"",autor:"",conteudos_relacionados:[],categoria_id:"",perfil_id:"",video:!1,medias_conteudo:[],niveis:[],listas:[],niveis_id:[],perfis_id:[],texto:""},conteudos_relacionados:[],medias_relacionadas:[],categorias:[],perfis:[],niveis:[]}},watch:{"registro.perfis_id":function(){this.registro.perfis_id&&this.registro.perfis_id.length>0?this.loadNiveis2():(this.niveis_id=[],this.registro.niveis_id=[])}},created:function(){this.loadConteudosRelacionados(),this.loadMediasRelacionadas(),this.loadCategorias()},methods:{loadConteudosRelacionados:function(){var i=this;axios.get(base_url+"/admin/api/conteudos/select/"+("undefined"!=typeof id&&id?id:"")).then(function(e){i.conteudos_relacionados=e.data.registros}).catch(function(e){i.conteudos_relacionados=[]})},loadMediasRelacionadas:function(){var i=this;axios.get(base_url+"/admin/api/medias/all/"+("undefined"!=typeof id&&id?id:"")).then(function(e){i.medias_relacionadas=e.data.registros}).catch(function(e){i.medias_relacionadas=[]})},loadCategorias:function(){var i=this;axios.get(base_url+"/admin/api/categorias/all").then(function(e){i.categorias=e.data.registros}).catch(function(e){i.categorias=[]})},loadPerfis:function(){var i=this;axios.get(base_url+"/admin/api/usuarios/perfis").then(function(e){i.perfis=e.data.registros}).catch(function(e){i.perfis=[]})},loadNiveis2:function(){for(var i=this,e="",s=0;s<this.registro.perfis_id.length;s++)0!==s&&(e+=","),e+=this.registro.perfis_id[s];axios.get(base_url+"/admin/api/usuarios/niveis-by-perfis/"+e).then(function(e){i.niveis=e.data.registros,i.updateNiveisRegistro()}).catch(function(e){i.niveis=[]})},updateSlug:function(){this.registro.slug=this.registro.titulo},checkSlug:function(){var i=this,e=base_url+"/admin/api/conteudos/check-url"+("undefined"!=typeof id?"/"+id:"");axios.post(e,this.registro).then(function(e){e.data.field&&(i.registro.slug=e.data.field),e.data.validate&&i.insertSuccess(e.data.validate)}).catch(function(e){if(e.response.data.error_validate){var s=e.response.data.error_validate;i.errors&&(i.errors=s),i.has_errors&&(i.has_errors=[]),i.insertErrors(s)}})},updateNiveisRegistro:function(){var i=[];if(this.registro.niveis_id){for(var e=0;e<this.registro.niveis_id.length;e++){for(var s=!1,a=0;a<this.niveis.length;a++)if(this.niveis[a].id==this.registro.niveis_id[e]){s=!0;break}s||i.push(e)}for(var t=i.length-1;t>=0;t--)this.registro.niveis_id.splice(i[t],1)}}}};