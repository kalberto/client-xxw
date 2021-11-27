const app = new Vue({
	el: "#app",
	data: {
		msg: "",
		registros: {
			data: []
		},
		totalUsuarios: 0,
		porcentagemUsuarios: 0,
		totalTalentos: 0,
		porcentagemTalentos: 0,
		warning: false,
		warning_message: "",
		carregando: true,
		total: '',
	},
	methods: {
		init: function () {
			this.getUsuarios();
		},
		getUsuarios: function () {
			$.ajax({
				url: base_url + "/admin/api/total/usuarios"
			}).done(function (response) {
				app.$data.totalUsuarios = response.total;
				app.$data.porcentagemUsuarios = (response.total * 100) / 100
			});
		},
	}
});
app.$data.base_url = base_url;
app.init();
