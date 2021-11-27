const app = new Vue({
    el: "#app",
    mixins: [main_mixin,create_mixin,message_mixin,media_mixin,administrador_mixin],
    data: {},
    methods: {
        createAdministrador() {
            this.create("/admin/administradores/salvar",true);
        }
    }
});