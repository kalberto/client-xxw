$(window).on("load",function(){$("#asset-type-select").on("change",function(){app.$data.asset.tipo=$(this).val(),1==$(this).val()?$("input[name='asset_video']").val(void 0):2==$(this).val()&&$("input[name='asset_image']").val(void 0)})});var app=new Vue({el:"#app",data:{page_edit:!1,base_url:"",msg:"",saved:!1,success_message:"Editado com sucesso!",sending_message:"Enviando..",carregando_upload:!1,carregando_create:!1,taxa_upload:0,tipo:[{id:1,text:"Imagem"},{id:2,text:"Vídeo"}],asset:{tipo:0,nome:"",legenda:"",alias:"medias"}},methods:{createAsset:function(){$(".form");if(null==this.asset.file)1==this.asset.tipo?inserErrorOnForm("form-assets","asset_image","A imagem é obrigatória"):2==this.asset.tipo?inserErrorOnForm("form-assets","asset_video","O video é obrigatório"):inserErrorOnForm("form-assets","tipo","O tipo é obrigatório");else{var a=new FormData;this.carregando_upload=!0,this.sending_message="Enviando..",this.carregando_create=!0,$("#upload_progress_bar").width="0%",a.append("file",this.asset.file[0]),a.append("nome",this.asset.nome),a.append("tipo",this.asset.tipo),a.append("legenda",this.asset.legenda),a.append("alias",this.asset.alias),this.$http.post(base_url+"/admin/api/assets",a,{headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},uploadProgress:function(a){var e=100*a.loaded/a.total;$("#upload_progress_bar").width(e+"%"),100==e&&(app.$data.carregando_upload=!1,app.$data.sending_message="Salvando..")},emulateJSON:!0}).then(function(a){app.$data.carregando_create=!1,app.$data.saved=!0,setTimeout(function(){app.$data.saved=!1,window.location.replace(base_url+a.body.url)},1e4),app.$data.success_message=a.body.msg,scrollToTopCorrec()},function(a){if(app.$data.carregando_create=!1,a.body.error_validate){var e=a.body.error_validate;insertErrorsOnForm("form-assets",e)}})}},addFile:function(){this.asset.file=this.$refs.file.files}}});
