const app = new Vue({
	el: "#app",
	mixins: [main_mixin, message_mixin, listing_mixin, delete_mixin],
	data: {
		route: "/admin/api/leads",
		deleteRoute: "/admin/leads/delete/",
		deleteLink: "",
		result_message: "",
		warning_message: "",
		deletedIndex: 0,
		totalLeads: 0,
		porcentagemLeads: 0,
		isSearch: false,
		show_result: false,
	},
	methods: {
		init() {
			this.getLeads();
			this.loadLead();
			this.setSortPagination("-created_at");
		},
		loadLead() {
			app.$data.carregando = false;
			$.ajax({
				url: base_url + "/admin/api/leads?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&q=" + this.pagination.q + "&sort=" + this.pagination.sort + "&fields=" + this.pagination.fields,
				method: "GET",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
				}
			}).done(function (response) {
				app.$data.carregando = false;
				app.$data.paginator = response.pagination;
				app.$data.registros = response.registros;
				if (app.$data.registros.length <= 0) {
					// app.$data.warning = true;
					// app.$data.warning_message = response.msg;
					app.$data.show_result = true;
					app.$data.result_message = response.msg;
					app.$data.isSearch = false;
				}
				if (app.$data.registros.data != undefined && app.$data.registros.data.length > 0) {
					if (app.$data.isSearch == true) {
						app.$data.show_result = true;
						app.$data.result_message = response.msg;
						app.$data.isSearch = false;
					}
				}
			}).fail(function (response) {
				app.$data.carregando = false;
				app.$data.warning = true;
				if (response.msg) {
					app.$data.warning_message = response.msg;
				} else {
					app.$data.warning_message = "Ocorreu um erro, tente novamente mais tarde.";
				}
				app.$data.paginator = response.responseJSON.pagination;
				app.$data.registros = response.responseJSON.registros;
			});
		},
		search: function (event) {
			app.$data.warning = false;
			app.$data.show_result = false;
			event.preventDefault();
			this.pagination.page = 1;
			this.pagination.sort = "-created_at";
			this.isSearch = true;
			this.loadLead();
		},
		getLeads() {
			$.ajax({
				url: base_url + "/admin/api/total/leads"
			}).done(function (response) {
				app.$data.totalLeads = response.total;
				app.$data.porcentagemLeads = 100
			});
		},
		closeResults() {
			this.show_result = false;
			location.reload();
			// this.init();
		},
		donwloadLeads() {
			window.open(base_url + '/admin/leads/export', 'blank');
		}
	}
});
app.$data.base_url = base_url;
app.init();
