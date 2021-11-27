var app = new Vue({
  el: "#app",
  mixins: [main_mixin, lead_mixin, mensagens_mixin, helper_mixin],
  data: {},
  methods: {
    initLocal: function initLocal() {}
  }
});
app.initLocal();
app.hideLoader();
