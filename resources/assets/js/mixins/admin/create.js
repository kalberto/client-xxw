let create_mixin = {
	mixins: [errors_mixin],
    data: function () {
        return {

        }
    },
    methods:{
        create(pRota,pRedirect,form = null){
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
	            },5000);
            }).catch(error => {
            	this.error = true;
            	if(error.response.data.msg)
            	    this.errorMessage = error.response.data.msg;
            	else
            		this.errorMessage = "Ocorreu um erro ao salvar. Tente novamente mais tarde.";
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