const app = new Vue({
	el: "#app",
	data: {
		msg: "",
		registros: {
			data: []
		},
		warning_message: "",
		warning: false,
		pagination: {
			page: 1,
			limit: 10,
			total: 0,
			q: "",
			sort: "-data",
			fields: ""
		},
		paginator: false,
	},
	methods: {
		formatDate: function (date) {
			return Vue.moment(date).format('DD/MM/YYYY H:m');
		},
		loadLogs: function (e) {
			$.ajax({
				url: base_url + "/admin/api/log_acessos?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&q=" + this.pagination.q + "&sort=" + this.pagination.sort + "&fields=" + this.pagination.fields,
				method: "GET",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
				}
			}).done(function (response) {
				app.$data.paginator = response.pagination;
				app.$data.registros = response.registros;
				if (app.$data.registros.length <= 0) {
					app.$data.warning = true;
					app.$data.warning_message = response.msg;
				}
			}).fail(function (response) {
				app.$data.warning = true;
				if (response.msg)
					app.$data.warning_message = response.msg;
				else
					app.$data.warning_message = "Ocorreu um erro, tente novamente mais tarde.";
				app.$data.paginator = response.responseJSON.pagination;
				app.$data.registros = response.responseJSON.registros;
			});
		},
		search: function (event) {
			event.preventDefault();
			this.pagination.page = 1;
			this.pagination.sort = "";
			this.loadLogs();
		},
		goToPage: function (page) {
			this.pagination.page = page;
			this.loadLogs();
		},
	}
});
app.loadLogs();
