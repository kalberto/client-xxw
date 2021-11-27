let edit_mixin = {
	mixins: [errors_mixin],
    data: function () {
        return {
        	reload:true,
        }
    },
    methods:{
		editNoReload(pRota,pRedirect,form = null){
			this.reload = false;
			this.edit(pRota,pRedirect,form);
		},
        edit(pRota,pRedirect,form = null){
	        this.sending = true;
	        this.error = false;
	        this.success = false;
	        this.sendingMessage = "Enviando...";
	        axios.put(base_url + pRota,form ? form : this.registro).then(response => {
		        this.success = true;
		        this.successMessage = response.data.msg;
		        setTimeout(() => {
			        this.success = false;
			        if(pRedirect)
				        window.location.replace(base_url + response.data.url);
			        else{
			        	if(this.reload)
				            location.reload();
			        	else
				            this.reload = true;
			        }

		        },3000);
	        }).catch(error => {
		        this.error = true;
		        this.errorMessage = error.response.data.msg;
		        if(error.response.data.error_validate){
			        let errors = error.response.data.error_validate;
			        if(this.errors)
				        this.errors = errors;
			        if(this.has_errors)
				        this.has_errors = [];
			        this.insertErrors(errors);
		        }
	        }).finally(() => {
		        this.sending = false;
		        this.scrollToTop();
	        });
        },
        editPost(pRota,pRedirect,form = null){
	        this.sending = true;
	        this.error = false;
	        this.success = false;
	        this.sendingMessage = "Enviando...";
	        axios.post(base_url + pRota,form ? form : this.registro).then(response => {
		        this.success = true;
		        this.successMessage = response.data.msg;
		        setTimeout(() => {
			        this.success = false;
			        if(pRedirect)
				        window.location.replace(base_url + response.data.url);
			        else
			        	location.reload();
		        },3000);
	        }).catch(error => {
		        this.error = true;
		        this.errorMessage = error.response.data.msg;
		        if(error.response.data.error_validate){
			        let errors = error.response.data.error_validate;
			        if(this.errors)
				        this.errors = errors;
			        if(this.has_errors)
				        this.has_errors = [];
			        this.insertErrors(errors);
		        }
	        }).finally(() => {
		        this.sending = false;
		        this.scrollToTop();
	        });
        }
    }
};
