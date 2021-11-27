const app = new Vue({
    el: "#app",
    mixins: [main_mixin, lead_mixin, mensagens_mixin, helper_mixin],
    data: {},
    methods: {
        initLocal: function () {},
    }
});

app.initLocal();
app.hideLoader();