const app = new Vue({
    el:"#app",
    data: {
        not_sended : true,
        error:false,
        msg:'',
        form:{
            email:''
        }
    },
    methods:{
        tryAgain:function () {
            this.not_sended = true;
            this.error = false;
            this.msg = '';
            app.$data.form.email='';
        },
        resetPass:function (e) {
            $.ajax({
                url:e.target.action,
                method:e.target.method,
                data:app.$data.form,
                headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")}
            }).done(function (response) {
                app.$data.not_sended = false;
                app.$data.msg = response.msg;
                app.$data.form.email='';
            }).fail(function (response) {
                app.$data.not_sended = false;
                app.$data.error = true;
                app.$data.form.email='';
                app.$data.msg = response.responseJSON.msg;
            });
        }
    }
});