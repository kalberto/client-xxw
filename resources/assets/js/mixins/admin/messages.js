let message_mixin = {
    data: function () {
        return {
            success:false,
            successMessage:"Ação executada com sucesso!",
            warning:false,
            warningMessage:"Aconteceu algum imprevisto.",
            error:false,
            errorMessage:"Ocorreu um erro ao executar a ação!",
            saved:false,
            savedMessage:"Salvo com sucesso",
            sending:false,
            sendingMessage:"Enviando..."
        }
    },
    methods:{
    }
};