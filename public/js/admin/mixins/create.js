var create_mixin={mixins:[errors_mixin],data:function(){return{}},methods:{create:function(r,e){var s=this,a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null;this.sending=!0,this.error=!1,this.success=!1,this.sendingMessage="Enviando...",axios.post(base_url+r,a||this.registro).then(function(r){s.success=!0,s.successMessage=r.data.msg,setTimeout(function(){s.success=!1,e&&window.location.replace(base_url+r.data.url)},5e3)}).catch(function(r){if(s.error=!0,r.response.data.msg?s.errorMessage=r.response.data.msg:s.errorMessage="Ocorreu um erro ao salvar. Tente novamente mais tarde.",r.response.data.error_validate){var e=r.response.data.error_validate;s.errors&&(s.errors=e),s.has_errors&&(s.has_errors=[]),s.insertErrors(e)}}).finally(function(){s.sending=!1,s.scrollToTop()})}}};
