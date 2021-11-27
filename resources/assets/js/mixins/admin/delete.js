let delete_mixin = {
    data: function () {
        return {
            deleteRoute:"",
            deleteLink:"",
            deletedIndex:0
        }
    },
    methods:{
        clickDelete(id, index){
            this.deleteLink = this.base_url + this.deleteRoute + id;
	        this.deletedIndex = index;
	        this.$refs.click_for_modal.click();
        },
        confirmDelete(){
            axios.delete(this.deleteLink).then(response => {
            	this.success = true;
	            this.successMessage = response.data.msg;
	            this.load();
	            setTimeout( () => {
		            this.success = false;
	            },2000);
            }).catch(error => {
            	this.error = true;
            	if(error.response.data.msg){
		            this.errorMessage = error.response.data.msg;
	            }else{
		            this.errorMessage = "Ocorreu um erro. Tente novamente mais tarde.";
	            }
	            setTimeout( () => {
		            this.error = false;
	            },5000);
            }).finally(() => {
	            this.scrollToTop();
            });
        }
    }
};