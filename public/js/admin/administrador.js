var app = new Vue({
  el: "#app",
  mixins: [main_mixin, edit_mixin, message_mixin, media_mixin],
  data: {
    msg_dados: "",
    sending_message_dados: 'Enviando..',
    carregando_edit_dados: false,
    saved_dados: false,
    registro: {},
    perfis: []
  },
  methods: {
    loadAdministrador: function loadAdministrador() {
      $.ajax({
        url: base_url + "/admin/api/administradores/" + id,
        method: "GET"
      }).done(function (response) {
        app.$data.registro = response.administrador;
        app.$data.perfis = response.perfis;
      });
    },
    editAdministrador: function editAdministrador() {
      this.edit("/admin/administradores/atualizar/" + id, true);
    },
    atualizarAdministradorDados: function atualizarAdministradorDados(id) {
      this.carregando_edit_dados = true;
      app.$data.saved_dados = false;
      $.ajax({
        url: base_url + "/admin/administradores/atualizar/senha/" + id,
        method: "put",
        data: app.$data.registro,
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
      }).done(function (response) {
        app.$data.carregando_edit_dados = false;
        app.$data.successMessage = response.msg;
        app.$data.success = true;
        scrollToTopCorrec();
        setTimeout(function () {
          app.$data.saved = false;
          window.location.replace(base_url + response.url);
        }, 4000);
      }).fail(function (response) {
        app.$data.carregando_edit_dados = false;

        if (response.responseJSON.error_validate) {
          var erros = response.responseJSON.error_validate;
          insertErrorsOnForm("form-administrador-dados", erros);
        } else {}
      });
    },
    callLoadAssets: function callLoadAssets() {
      this.media_root = "administradores";
      this.loadAssets();
    },
    selectMedia: function selectMedia(id, index) {
      this.registro.media_id = id;
      this.registro.media = this.medias[index];
    },
    removeMedia: function removeMedia() {
      this.registro.media_id = "";
      this.registro.media = "";
    }
  }
});
app.loadAdministrador();
