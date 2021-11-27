var lead_mixin = {
    data: {
        lead: {
            formulario: {},
            formulario_errors: {},
        }
    },
    methods: {
        set_localstorage: function () {
            if (!(document.referrer == null || document.referrer == undefined || document.referrer == "")) {
                if (document.referrer.includes('google.com')) {
                    if (typeof document.URL.split('?gclid=')[1] === "undefined")
                        localStorage.setItem("referrer", 'google-organico');
                    else
                        localStorage.setItem("referrer", 'google-adwords');
                } else {
                    localStorage.setItem("referrer", document.referrer);
                }
            } else if (localStorage.getItem("referrer") == null) {
                localStorage.setItem("referrer", "");
            }
        },
        send_lead: function (origem, endpoint, var_formulario) {
            endpoint = endpoint || 'api/lead';
            var_formulario = var_formulario || 'formulario';

            var data = new FormData();
            Object.getOwnPropertyNames(this.lead[var_formulario]).forEach(function (item) {
                if (item == "file")
                    app.$data.lead[var_formulario][item].forEach(function (item2) {
                        data.append('file', item2);
                    });
                else
                    data.append(item, app.$data.lead[var_formulario][item]);
            });
            data.append('form_origem', origem);
            data.append('origem', localStorage.getItem("referrer") ? localStorage.getItem("referrer") : 'site');

            this.lead[var_formulario + '_errors'] = {};
            this.mensagens.error = false;
            this.mensagens.error_422 = false;
            this.mensagens.error_500 = false;
            this.mensagens.success = false;
            this.mensagens.sending = true;

            $.ajax({
                url: app.$data.base_url + endpoint,
                method: "POST",
                async: true,
                processData: false,
                contentType: false,
                data: data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            }).done(function (response) {
                app.$data.mensagens.sending = false;
                app.$data.mensagens.success = true;
                app.$data.lead[var_formulario] = {};
            }).fail(function (response) {
                if (response.status == 422) {
                    app.$data.mensagens.error_422 = true
                    app.$data.lead[var_formulario + '_errors'] = response.responseJSON.error_validate;
                } else if (response.status[0] == 5) {
                    app.$data.mensagens.error_500 = true;
                }
            }).always(function () {
                setTimeout(function () {
                    app.$data.mensagens.error = false;
                    app.$data.mensagens.error_422 = false;
                    app.$data.mensagens.error_500 = false;
                    app.$data.mensagens.sending = false;
                    app.$data.mensagens.success = false;
                }, 5000)
            })
        },
    }
}