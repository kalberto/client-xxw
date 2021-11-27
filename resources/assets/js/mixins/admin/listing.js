let listing_mixin = {
    data: function () {
        return {
            route:"",
            carregando:false,
            registros:{
                data:[]
            },
            pagination:{
                page: 1,
                limit: 10,
                total: 0,
                q: "",
                sort: "-created_at",
                fields: "",
                state:''
            },
            paginator:false,
        }
    },
	mounted() {
		let page = new URL(location.href).searchParams.get('page')
		if(page && page !== ''){
			this.pagination.page = page;
		}
	},
	methods:{
        resetMessages(){
            this.warning = false;
            this.error = false;
        },
        setSortPagination(pSort){
            this.pagination.sort = pSort;
        },
        setPagePagination(pPage){
          this.pagination.page = pPage;
		  let url = new URL(document.URL);
	      url.searchParams.set('page',pPage);
	      document.location.search = url.search
        },
        getParams(){
            return "?page=" + this.pagination.page + "&limit=" + this.pagination.limit + "&q=" + this.pagination.q + "&sort=" + this.pagination.sort + "&fields=" + this.pagination.fields + "&state=" + this.pagination.state;
        },//conta
        load(){
            this.resetMessages();
            this.carregando = true;
            axios.get(this.base_url + this.route + this.getParams())
	            .then(response => {
                    this.registros = response.data.registros;
                    this.paginator = response.data.pagination;
	                if(this.registros.length <= 0){
		                this.warning = true;
		                this.warningMessage = response.data.msg;
	                }
                })
                .catch(error => {
                    this.warning = true;
                    if(error.response.data.message){
                        this.warningMessage = error.data.msg;
                    }else{
	                    this.warningMessage = "Ocorreu um erro. Tente novamente mais tarde.";
                    }
                    if(error.response.data.registros !== undefined && error.response.data.pagination !== undefined){
	                    this.registros = error.response.data.registros;
	                    this.paginator = error.response.data.pagination;
                    }else{
	                    this.registros = [];
	                    this.paginator = false;
                    }
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        search(pEvent){
            pEvent.preventDefault();
            this.pagination.page = 1;
            this.load();
        },
        goToPage(pPage){
            this.setPagePagination(pPage);
            this.load();
        }
    }
};
