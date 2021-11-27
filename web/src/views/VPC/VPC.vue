<template lang="pug">
	article#vpc
		Header
		.container
			h3 Verba de Propaganda Cooperada
			h2 Precisando de ajuda para vender mais?
				strong  Solicite a VPC.
			p
				span VERBA DE APOIO PARA AÇÕES
				span.ponto &#9679;
				span PADRONIZAÇÃO DOS MATERIAIS xxw
				span.ponto &#9679;
				span CONSULTORIA DE MARKETING

			SaldoVpc
			ul.status
				li(v-for="(item, index) in vpc.all_status", :class="item.status" )
					p {{item.nome + ' - ' + item.criado}}

			form(@submit.prevent="sendForm")
				div
					p
						| {{mensagemUsuario}}
					.group(v-if="showForm && vpc.status.comentarios")
						.input._100
							textarea(placeholder="Comentários", v-model="vpc.status.comentarios", disabled)
				p
					| Sua Solicitação:
				.group
					.input._100
						select(v-model="vpc.assunto_vpc_id", disabled)
							option(v-for="(item, index) in assuntos", :value="item.id") {{item.nome}}
				.group(v-if="showForm", v-for="(item, index) in campos")
					.input._100(v-if="item.tipo != 'files'")
						textarea(v-if="item.tipo === 'textarea'", :name="item.label", v-model="vpc.dados[item.campo]", :disabled="item.cant_edit")
						input(v-if="item.tipo === 'text'", :name="item.label", v-model="vpc.dados[item.campo]", :disabled="item.cant_edit")
						input(v-if="item.tipo === 'date'", type="date", :name="item.label", v-model="vpc.dados[item.campo]", :disabled="item.cant_edit")
						input(v-if="item.tipo === 'money'", type="text", :name="item.label", v-money="money", v-model.lazy="vpc.dados[item.campo]", :disabled="item.cant_edit")
						label(:for="item.label") {{item.label}}
						span(v-if="errors['dados.' + item.campo]") {{errors['dados.' + item.campo][0]}}
					.file._100(v-if="item.tipo === 'files' && item.campo !== 'comprovantes'")
						label(:for="'arquivos-' + item.campo ")
							span {{item.label}}
							p {{files[item.campo] ? files[item.campo].name : 'Nenhum arquivo selecionado'}}
							div(:class="{'grey-text': item.cant_edit}") Selecione o arquivo
							span(v-if="errors[item.campo]").error-span {{errors[item.campo][0]}}
						input(type="file" :id="'arquivos-' + item.campo", accept="image/*,application/pdf" name="arquivos" multiple="multiple", @change="addFile(item.campo,$event)", :disabled="item.cant_edit")
						ul
							li(v-for="(file, index) in vpc[item.campo]")
								span {{file.name}}
								.buttons
									a(:href="file.file", target="_blank")
										img(:src="require('@images/icons/download.png')")
				.group(v-if="hasComprovantes(vpc.status.nome)")
					.file._100
						label(for="arquivos-comprovantes")
							span Comprovantes *
							p {{files.comprovantes ? files.comprovantes.name : 'Nenhum arquivo selecionado'}}
							div(:class="{'grey-text':!canEditComprovantes(vpc.status.nome)}") Selecione o arquivo
							span(v-if="errors.comprovantes").error-span {{errors.comprovantes[0]}}
						input(type="file" id="arquivos-comprovantes", accept="image/*,application/pdf" name="arquivos" multiple="multiple", @change="addFile('comprovantes',$event)", :disabled="!canEditComprovantes(vpc.status.nome)")
						ul
							li(v-for="(file, index) in vpc.comprovantes")
								span {{file.name}}
								.buttons
									a(:href="file.file", target="_blank")
										img(:src="require('@images/icons/download.png')")
				.group(v-if="vpc.anexos_admin && vpc.anexos_admin.length > 0")
					.file._100
						p Anexos dos nos especialistas
						ul
							li(v-for="(file, index) in vpc.anexos_admin")
								span {{file.name}}
								.buttons
									a(:href="file.file", target="_blank")
										img(:src="require('@images/icons/download.png')")
				.enviar(v-if="candEdit")
					button(type="submit", v-html="button", :disabled="disabled") Enviar
</template>

<script>
// import qs from 'qs'
import Header from '@components/Header/Header'
import SaldoVpc from '@components/SaldoVpc/SaldoVpc'
import {mask} from "vue-the-mask";
import {VMoney} from 'v-money'

export default {
	name: "view-vpc",
	components: {
		Header,
		SaldoVpc,
	},
	directives: {
		mask,
		money: VMoney
	},
	data() {
		return {
			vpc: {
				all_status: [],
				assunto_vpc_id: null,
				dados: {},
				anexos: [],
				comprovantes: [],
				status: {}
			},
			assuntos: [],
			files: {
				anexos: [],
				comprovantes: []
			},
			file: null,
			campos: [],
			showForm: false,
			errors: {},
			button: 'Atualizar',
			disabled: false,
			money: {
				decimal: ',',
				thousands: '.',
				prefix: 'R$ ',
				suffix: '',
				precision: 2,
				masked: true
			},
			mensagemUsuario:'Nosso especialistas estão analisando sua solicitação.',
			candEdit:false,
		}
	},
	created() {
		if (this.$route.params.params)
			this.params = this.$route.params.params;
		this.loadVPC(this.$route.params.id);
		this.loadAssuntos();
	},
	methods: {
		async loadAssuntos() {
			this.$axios.get(`vpc/assuntos`)
			.then(response => {
				this.assuntos = response.data.registros;
			})
		},
		async loadVPC(id = '') {
			return await this.$axios
			.get(`vpc/${id}`)
			.then(response => {
				this.vpc = response.data.registro;
				this.campos = response.data.registro.campos;
				this.showForm = true;
				if(this.vpc.status.comentarios){
					if(this.vpc.status.nome === 'COMPROVAÇÃO'){
						this.mensagemUsuario = "Nossos especialistas aprovaram sua solitação e estão esperando a confirmação para dar ínicio no processo de pagamento."
					}else{
						this.mensagemUsuario = "Nossos especialistas analisaram suas necessidades e responderam:";
					}
				}
				if (this.vpc.status.status !=='')
					this.candEdit = this.vpc.status.status === "revisao";
			})
			.catch(() => {
				alert("VPC não encontrada.");
				this.$router.replace({name: 'vpcs'});
			})
		},
		addFile(campo, e) {
			let last = e.target.files.length - 1
			this.files[campo] = e.target.files[last]
			e.target.files.forEach(file => {
				if (!this.vpc[campo])
					this.vpc[campo] = [];
				this.vpc[campo].push(file);
			})
		},
		removeFile(campo, index) {
			this.vpc[campo].splice(index, 1)
		},
		sendForm() {
			this.button = "Enviando";
			this.disabled = true;
			let form_data = new FormData();
			form_data.append('assunto_vpc_id', this.vpc.assunto_vpc_id);
			for (let key in this.vpc.dados) {
				form_data.append('dados[' + key + ']', this.vpc.dados[key]);
			}
			for (let key in this.vpc.anexos) {
				if(!this.vpc.anexos[key].id)
					form_data.append('anexos[]', this.vpc.anexos[key]);
			}
			for (let key in this.vpc.comprovantes) {
				if(!this.vpc.comprovantes[key].id)
					form_data.append('comprovantes[]', this.vpc.comprovantes[key]);
			}
			this.$axios.post(`vpc/${this.vpc.id}`, form_data)
			.then(() => {
				this.button = "Enviado com sucesso!";
				alert("Sua solicitação foi atualizada.");
				this.$router.push({name: 'listaVpc'});
			})
			.catch(error => {
				this.button = "Atualizar";
				this.disabled = false;
				if (error.response.status == 422)
					this.errors = error.response.data.errors;
			})
			.finally(() => {
				setTimeout(() => {
					this.button = "Atualizar";
					this.disabled = false;
				}, 5000)
			})
		},
		hasComprovantes(status) {
			if(status === 'COMPROVAÇÃO' || status === 'COMPROVADO' || status === 'PAGO')
				return true
			return false;
		},
		canEditComprovantes(status){
			if(status === 'COMPROVAÇÃO')
				return true
			return false;
		},
	}
}
</script>

<style lang="stylus" scoped src="./VPC.styl"></style>
