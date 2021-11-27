var app = new Vue({
  el: "#app",
  mixins: [main_mixin, edit_mixin, message_mixin, media_mixin, noticia_mixin],
  data: {},
  methods: {
    load: function load() {
      $.ajax({
        url: base_url + "/admin/api/noticias/" + id,
        method: "GET"
      }).done(function (response) {
        app.$data.registro = response.registro;

        for (var i = 0; i < response.registro.medias.length; i++) {
          app.$data.registro.medias[i].nome = response.registro.medias[i].pivot.nome;
          app.$data.registro.medias[i].legenda = response.registro.medias[i].pivot.legenda;
          app.$data.registro.medias[i].destaque = response.registro.medias[i].pivot.destaque;
          app.$data.registro.medias[i].order = response.registro.medias[i].pivot.order;
          if (response.registro.medias[i].pivot.destaque) app.$data.mediaDestaque = i;
        }
      });
    },
    editNoticia: function editNoticia() {
      this.edit("/admin/noticias/atualizar/" + id, false);
    },
    checkSlug: function checkSlug(id) {
      $.ajax({
        url: base_url + "/admin/api/blogs/" + id + "/check-url",
        method: "POST",
        data: {
          slug: app.$data.registro.slug !== undefined ? app.$data.registro.slug : ""
        },
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
      }).done(function (response) {
        var mensagens = Object.getOwnPropertyNames(response);
        var form = $("form[name=form-noticia]");
        mensagens.forEach(function (msg) {
          if (msg != "field") {
            var element = form.find("[name=" + msg + "]");
            insertSuccessOnInput(element, response[msg]);
          } else {
            app.$data.registro.slug = response[msg];
          }
        });
      }).fail(function (response) {
        if (response.responseJSON.errors) {
          var mensagens = Object.getOwnPropertyNames(response.responseJSON.errors);
          var form = $("form[name=form-noticia]");
          mensagens.forEach(function (msg) {
            var element = form.find("[name=" + msg + "]");
            insertErrorOnInput(element, response.responseJSON.errors[msg]);
          });
        }
      });
    },
    init: function init() {
      this.initFather();
      this.load();
    }
  }
});
app.init();
