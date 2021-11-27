var app = new Vue({
  el: "#app",
  mixins: [main_mixin, edit_mixin, message_mixin, media_mixin, banner_mixin],
  data: {
    registro: {
      banner_desktop: '',
      banner_mobile: ''
    },
    mediaSelector: {
      identifier: '',
      index: 0
    }
  },
  methods: {
    load: function load() {
      $.ajax({
        url: base_url + "/admin/api/banners/" + id,
        method: "GET"
      }).done(function (response) {
        app.$data.registro = response.registro; // app.$data.registro.published_at = app.$data.registro.published_at ? app.$data.registro.published_at.replace(" ", 'T') : undefined;
      });
    },
    editBanner: function editBanner() {
      this.edit("/admin/banners/atualizar/" + id, false);
    },
    checkSlug: function checkSlug() {
      $.ajax({
        url: base_url + "/admin/api/banners/check-url",
        method: "post",
        data: {
          slug: app.$data.registro.slug !== undefined ? app.$data.registro.slug : ""
        },
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
      }).done(function (response) {
        var mensagens = Object.getOwnPropertyNames(response);
        var form = $("form[name=form-banner]");
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
          if (response.responseJSON['field']) app.$data.registro.slug = response.responseJSON['field'];
          var mensagens = Object.getOwnPropertyNames(response.responseJSON.errors);
          var form = $("form[name=form-banner]");
          mensagens.forEach(function (msg) {
            var element = form.find("[name=" + msg + "]");
            insertErrorOnInput(element, response.responseJSON.errors[msg]);
          });
        }
      });
    }
  }
});
app.load();
