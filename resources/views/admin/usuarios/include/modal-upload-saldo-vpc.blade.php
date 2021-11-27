<a href="#" data-toggle="modal" data-target="#upload-modal-saldo-vpc" ref="modalUploadPlanilhaSaldoVpc" hidden></a>
<div class="modal fade" id="upload-modal-saldo-vpc">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Upload
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Selecione o arquivo para upload dos saldos VPC</p>
                <input type="file" accept=".xlsx,xls" ref="file_saldo_vpc" name="file_name" class="form-control">
                <div v-if="upload_sucesso">
                    <span style="font-weight: bold">@{{ upload_message }}</span>
                </div>
                <div v-if="upload_error">
                    <div style="padding-top: 10px">
                        <span style="font-weight: bold">@{{ upload_message }}</span>
                        <button type="button" class="btn btn-danger" @click="toggleErrorsUpload()" v-if="upload_errors.length > 0" >@{{ show_upload_erros ? 'Esconder erros' : 'Mostrar erros' }}</button>
                    </div>
                    <div v-show="show_upload_erros">
                        <div v-for="error_item in upload_errors">
                            <span>@{{ error_item }}</span><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" v-if="!carregando_upload">
                <button type="button" class="btn btn-primary" @click="uploadArquivoSaldoVpc()">Upload</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
            <div class="modal-footer" v-else>
                <span style="font-weight: bold">Enviando ...</span>
            </div>
        </div>
    </div>
</div>
