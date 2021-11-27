const app = new Vue({
    el:"#app",
    mixins: [main_mixin,edit_mixin,message_mixin,media_mixin],
    data:{
        registro: {
            nome_app:'',
            seo_sufix:'',
            tag_manager_id:'',
            email_remetente:'',
            email_destinatario:'',
            complementos:[]
        },
        mediaSelector:{
            index:0
        },
    },
    methods:{
        selectMedia(id,index){
            this.registro.complementos[this.mediaSelector.index].valor =   base_url + '/' + this.medias[index].media_root.path + (this.medias[index].tipo == 1 ? (this.medias[index].file) : (this.medias[index].thumbnail));
            this.registro.complementos[this.mediaSelector.index].media_id = id;
        },
        callLoadAssets(pParams){
            this.mediaSelector = pParams;
            this.media_root = 'medias';
            this.loadAssets();
        },
        removeMedia(pParams){
            this.mediaSelector = pParams;
            this.registro.complementos[this.mediaSelector.index].valor = '';
            this.registro.complementos[this.mediaSelector.index].media_id = '';
        },
        addMetaTag(){
            this.registro.complementos.push({nome:'',tipo:1,valor:''});
        },
        removeMetaTag(index){
            this.registro.complementos.splice(index,1);
        },
        load(){
            $.ajax({
                url: base_url + "/admin/api/configuracoes",
                method: "GET",
            }).done(function(response) {
                app.$data.registro = response;
            });
        },
        editConfiguracao(){
            this.edit("/admin/api/configuracoes",false);
        }
    }
});
app.load();