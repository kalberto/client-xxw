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
        page_edit: true,
        msg:"",
        saved:false,
        success_message:"Editado com sucesso!",
        sending_message:'Enviando..',
        carregando_upload:false,
        carregando_edit:false,
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
        },
        media:{},
        base_url:''
    },
    methods:{
        editAsset: function (id) {
            let form = $(".form");
            this.carregando_upload = true;
            this.carregando_edit = true;
            if(this.asset.file == undefined){
                this.sending_message = "Salvando..";
                $.ajax({
                    url: base_url + "/admin/api/assets/" + id,
                    method: "POST",
                    data:app.$data.asset,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    }
                })
                    .done(function (response) {
                        app.$data.carregando_edit = false;
                        app.$data.saved = true;
                        setTimeout(function() {
                            app.$data.saved = false;
                        }, 5000);
                        app.$data.success_message = response.msg;
                        scrollToTopCorrec();
                    })
                    .fail(function (response) {
                        app.$data.carregando_edit = false;
                        app.$data.carregando_edit = false;
                        if (response.responseJSON.error_validate) {
                            let erros = response.responseJSON.error_validate;
                            insertErrorsOnForm("form-assets",erros);
                        }
                    });
            }else{
                this.saveWithFile(id);
            }
        },
        addFile : function () {
            this.asset.file = this.$refs.file.files;
        },
        load : function () {
            $.ajax({
                url:base_url + "/admin/api/assets/" + id,
                method: "GET"
            }).done(
                function (response) {
                    app.$data.asset.nome = response.media.nome;
                    app.$data.asset.legenda = response.media.legenda;
                    app.$data.asset.tipo = response.media.tipo;
                    app.$data.media = response.media;
                    $('#asset-type-select').val(app.$data.asset.tipo);
                }
            );
        },
        saveWithFile : function (id) {
            let form = $(".form");
            this.sending_message = "Enviando..";
            let data = new FormData();
            $('#upload_progress_bar').width = '0%';
            data.append('file', this.asset.file[0]);
            data.append('nome', this.asset.nome);
            data.append('tipo', this.asset.tipo);
            data.append('legenda', this.asset.legenda);
            data.append('alias', this.asset.alias);
            this.$http.post(base_url + '/admin/api/assets/'+id,data,{
                headers: {
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")
                },
                uploadProgress:function (event) {
                    let current = (event.loaded*100/event.total);
                    $('#upload_progress_bar').width( current+ "%");
                    if(current == 100){
                        app.$data.carregando_upload = false;
                        app.$data.sending_message = "Salvando..";
                    }
                },
                emulateJSON:true
            }).then(
                function (response) {
                    app.$data.carregando_edit = false;
                    app.$data.saved = true;
                    setTimeout(function() {
                        app.$data.saved = false;
                    }, 5000);
                    app.$data.success_message = response.body.msg;
                    scrollToTopCorrec();
                },  function (response) {
                    if (response.body.error_validate) {
                        let erros = response.body.error_validate;
                        insertErrorsOnForm("form-assets",erros);
                    }
                });
        }
    }
});
app.$data.base_url = base_url;
app.load();