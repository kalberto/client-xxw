$(window).on('load', function(){
    $('#asset-type-select').on('change', function(){
        app.$data.asset.tipo = $(this).val();
        if($(this).val() == 1){
            $("input[name='asset_video']").val(undefined);
        }
        else if($(this).val() == 2){
            $("input[name='asset_image']").val(undefined);
        }
    });
});
const app = new Vue({
    el:"#app",
    data: {
        page_edit: false,
        base_url: '',
        msg:"",
        saved:false,
        success_message:"Editado com sucesso!",
        sending_message:'Enviando..',
        carregando_upload:false,
        carregando_create:false,
        taxa_upload:0,
        tipo:[
            {id: 1, text:'Imagem'},
            {id: 2, text:'Vídeo'}
        ],
        asset:{
            tipo:0,
            nome:'',
            legenda:'',
            alias:'medias'
        }
    },
    methods:{
        createAsset: function () {
            var form = $(".form");
            if(this.asset.file == undefined){
                if(this.asset.tipo == 1){
                    inserErrorOnForm("form-assets","asset_image","A imagem é obrigatória");
                }else if(this.asset.tipo == 2){
                    inserErrorOnForm("form-assets","asset_video","O video é obrigatório");
                }else{
                    inserErrorOnForm("form-assets","tipo","O tipo é obrigatório");
                }
            }else{
                var data = new FormData();
                this.carregando_upload = true;
                this.sending_message = "Enviando..";
                this.carregando_create = true;
                $('#upload_progress_bar').width = '0%';
                data.append('file', this.asset.file[0]);
                data.append('nome', this.asset.nome);
                data.append('tipo', this.asset.tipo);
                data.append('legenda', this.asset.legenda);
                data.append('alias', this.asset.alias);
                this.$http.post(base_url + '/admin/api/assets',data,{
                    headers: {
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")
                    },
                    uploadProgress:function (event) {
                        var current = (event.loaded*100/event.total);
                        $('#upload_progress_bar').width( current+ "%");
                        if(current == 100){
                            app.$data.carregando_upload = false;
                            app.$data.sending_message = "Salvando..";
                        }
                    },
                    emulateJSON:true
                }).then(
                    function (response) {
                        app.$data.carregando_create = false;
                        app.$data.saved = true;
                        setTimeout(function() {
                            app.$data.saved = false;
                            window.location.replace(base_url + response.body.url);
                        }, 10000);
                        app.$data.success_message = response.body.msg;
                        scrollToTopCorrec();
                    },  function (response) {
                        app.$data.carregando_create = false;
                        if (response.body.error_validate) {
                            var erros = response.body.error_validate;
                            insertErrorsOnForm("form-assets",erros);
                        }
                    });
            }
        },
        addFile : function () {
            this.asset.file = this.$refs.file.files;
        }
    }
});