const app = new Vue({
    el: "#app",
    mixins: [main_mixin, create_mixin, message_mixin],
    data: {
        registro: {
            mod_adm_permissao: []
        },
        permissions: [],
        modulos: [],
    },
    methods: {
        load() {
            $.ajax({
                url: base_url + "/admin/api/mod_adm_permissao",
                method: "GET",
            }).done(function (response) {
                app.$data.modulos = response.modulos;
                app.$data.modulos.forEach(function (item, index) {
                    if (item.obrigatorio === 1) {
                        item.mod_adm_permissao.forEach(function (mod) {
                            app.$data.registro.mod_adm_permissao[mod.id] = "true";
                        });
                    }
                });
            });
        },
        createPerfil() {
            this.create("/admin/api/perfis", true);
        },
    }
});
app.load();