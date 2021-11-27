const app = new Vue({
    el: "#app",
    mixins: [main_mixin,edit_mixin,message_mixin],
    data: {
        registro: {},
        permissions:[],
        modulos: [],
    },
    methods: {
        load(){
            $.ajax({
                url: base_url + "/admin/api/perfis/" + id,
                method: "GET"
            }).done(function(response) {
                app.$data.registro = response.registro;
                app.$data.modulos = response.modulos;
            });
        },
        editPerfil(){
            this.edit("/admin/api/perfis/" + id,true);
        }
    }
});
app.$data.base_url = base_url;
app.load();