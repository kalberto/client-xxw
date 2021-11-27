var app = new Vue({
  el: "#app",
  mixins: [main_mixin, create_mixin, message_mixin, media_mixin, noticia_mixin],
  data: {},
  methods: {
    init: function init() {
      this.initFather();
    },
    createNoticia: function createNoticia() {
      this.create("/admin/noticias/salvar", true);
    },
    checkSlug: function checkSlug() {
      $.ajax({
        url: base_url + "/admin/api/noticias/check-url",
        method: "post",
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
    }
  }
});
