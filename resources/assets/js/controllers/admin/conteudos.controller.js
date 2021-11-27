const app = new Vue({
    el: "#app",
    mixins: [main_mixin ,message_mixin, listing_mixin, delete_mixin, conteudo_mixin],
    data: {
        route: "/admin/api/conteudos",
        deleteRoute:"/admin/api/conteudos/",
        deleteLink: "",
        deletedIndex: 0,
        showFilters:false,
    },
    mounted(){
      this.init();
    },
    methods: {
        init(){
            this.setSortPagination("-nome");
            this.load();
        },
	    clickAtivo(pId){
            axios.get(base_url + '/admin/api/conteudos/ativo/' + pId).then(response => {
                this.success = true;
                this.successMessage = response.data.msg;
	            setTimeout(function() {
		            app.$data.success = false;
	            }, 3000);
            }).catch(error => {
                this.error = true;
                this.errorMessage = error.data.msg;
	            setTimeout(function() {
		            app.$data.error = false;
	            }, 3000);
            });
	    },
    }
});