const app = new Vue({
    el:"#app",
    data: {
        not_sended : true,
        msg:'',
        error:false,
        form:{
            password:'',
            re_password:'',
            token:''
        }
    },
    methods:{
        tryAgain:function () {
            this.not_sended = true;
            this.error = false;
            this.msg = '';
            app.$data.form.password='';
            app.$data.form.re_password='';
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
                app.$data.form.password='';
                app.$data.form.re_password='';
            }).fail(function (response) {
                app.$data.not_sended = false;
                app.$data.error = true;
                app.$data.form.password='';
                app.$data.form.re_password='';
                app.$data.msg = response.responseJSON.msg;
            });
        }
    }
});
app.$data.form.token = token;