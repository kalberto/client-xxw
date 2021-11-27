<template lang="pug">
	article#vpc
		Header
		.container
			h3 Verba de Propaganda Cooperada
			h2 Precisando de ajuda para vender mais?
				strong  Solicite a VPC.
			p
				| VERBA DE APOIO PARA AÇÕES   &#9679;   PADRONIZAÇÃO DOS MATERIAIS xxw    &#9679;   CONSULTORIA DE MARKETING

			SaldoVpc
			form(@submit.prevent="sendForm")
				p
					| Basta preencher o formulário abaixo, nossos especialistas
					br
					| vão analisar suas necessidades de acordo com as respostas
					br
					| e logo entraremos em contato pelo e-mail.
				.group
					.input._100
						label(for="valor-vpc") Custo *
						input(id="valor-vpc", type="text", name="valor_vpc", v-money="money", v-model.lazy="valorVPC")
				.group
					.input._100
						label(for="data-inicio-vpc") Data Inicio *
						input(id="data-inicio-vpc",  type="date" name="data_inicio_vpc", v-model="dataInicioVpc")
				.checkSaldo
					button(type="button", @click="checkValorVpc()", v-html="buttonSaldo", :disabled="disabled")
				div(v-if="vpcAtiva")
					.group
						.input._100
							select(v-model="vpcForm.assunto_vpc_id", @change="loadAssuntoCampos()")
								option(:value="null" disabled) Ação
								option(v-for="item, index in assuntos", :value="item.id") {{item.nome}}
							span(v-if="errors.assunto_vpc_id") O Campo é obrigatório
					.group(v-if="showForm", v-for="(item, index) in campos")
						.input._100(v-if="item.tipo != 'files'")
							textarea(v-if="item.tipo === 'textarea'", :name="item.label", v-model="vpcForm.dados[item.campo]")
							input(v-if="item.tipo === 'text'", :name="item.label", v-model="vpcForm.dados[item.campo]")
							input(v-if="item.tipo === 'date' && item.campo !== 'data_inicio'", type="date", :name="item.label", v-model="vpcForm.dados[item.campo]")
							input(v-if="item.tipo === 'date' && item.campo === 'data_inicio'", type="date", :name="item.label", v-model="vpcForm.dados[item.campo]", disabled)
							input(v-if="item.tipo === 'money'", type="text", :name="item.label", v-money="money", v-model.lazy="vpcForm.dados[item.campo]", disabled)
							label(:for="item.label") {{item.label}}
							span(v-if="errors['dados.' + item.campo]") {{errors['dados.' + item.campo][0]}}
						.file._100(v-if="item.tipo === 'files' && item.campo !== 'comprovantes'")
							label(:for="'arquivos-' + item.campo ")
								span {{item.label}}
								p {{files[item.campo] ? files[item.campo].name : 'Nenhum arquivo selecionado'}}
								div Selecione o arquivo
								span(v-if="errors[item.campo]").error-span {{errors[item.campo][0]}}
							input(type="file" :id="'arquivos-' + item.campo", accept="image/*,application/pdf" name="arquivos" multiple="multiple", @change="addFile(item.campo,$event)")
							ul
								li(v-for="(file, index) in vpcForm[item.campo]")
									span {{file.name}}
									.buttons
										button(@click="removeFile(item.campo,index)") X
					.enviar(v-if="showForm")
						button(type="submit", v-html="button", :disabled="disabled") Enviar
</template>

<script>
// import qs from 'qs'
import Header from '@components/Header/Header'
import SaldoVpc from '@components/SaldoVpc/SaldoVpc'
import {mask} from "vue-the-mask";
import {VMoney} from 'v-money'

export default {
	name: "view-nova-vpc",
	components: {
		Header,
		SaldoVpc
	},
	directives: {
		mask,
		money: VMoney
	},
	data() {
		return {
			valorVPC: 0,
			dataInicioVpc: null,
			vpcAtiva: false,
			vpcForm: {
				assunto_vpc_id: null,
				dados: {},
				anexos: [],
			},
			assuntos: [],
			files: {
				anexos: [],
			},
			file: null,
			campos: [],
			showForm: false,
			errors: {},
			button: 'Solicitar',
			buttonSaldo: 'Verificar Disponibilidade',
			disabled: false,
			money: {
				decimal: ',',
				thousands: '.',
				prefix: 'R$ ',
				suffix: '',
				precision: 2,
				masked: true
			},
		}
	},
	created() {
		if (this.$route.params.params)
			this.params = this.$route.params.params;
	},
	mounted() {
		this.loadAssuntos()
	},
	methods: {
		loadAssuntos() {
			this.$axios.get(`vpc/assuntos`)
			.then(response => {
				this.assuntos = response.data.registros;
				this.showForm = false;
			})
		},
		checkValorVpc() {
			this.vpcAtiva = false;
			this.vpcForm = {
				assunto_vpc_id: null,
				dados: {},
				anexos: [],
			};
			this.files = {
				anexos: [],
			};
			this.$axios.post(`vpc/check`, {'valor': this.valorVPC, 'data_inicio': this.dataInicioVpc})
			.then((response) => {
				if (response.data.valor) {
					this.valorVPC = this.vpcForm.dados.custo = response.data.valor;
					this.vpcForm.dados.data_inicio = this.dataInicioVpc;
				}
				if (!response.data.sucesso) {
					alert(response.data.msg);
				} else {
					this.$eventbus.$emit('updateSaldo', response.data);
					this.vpcAtiva = true;
				}
			});
		},
		loadAssuntoCampos() {
			if (this.vpcForm.assunto_vpc_id != null) {
				this.$axios.get(`/vpc/assuntos/${this.vpcForm.assunto_vpc_id}/campos`)
				.then(response => {
					this.campos = response.data.registros;
					this.showForm = true;
				})
			}
		},
		addFile(campo, e) {
			let last = e.target.files.length - 1
			this.files[campo] = e.target.files[last]
			e.target.files.forEach(file => {
				if (!this.vpcForm[campo])
					this.vpcForm[campo] = [];
				this.vpcForm[campo].push(file);
			})
		},
		removeFile(campo, index) {
			this.vpcForm[campo].splice(index, 1)
		},
		sendForm() {
			this.button = "Enviando";
			this.disabled = true;
			let form_data = new FormData();
			form_data.append('assunto_vpc_id', this.vpcForm.assunto_vpc_id);
			for (let key in this.vpcForm.dados) {
				form_data.append('dados[' + key + ']', this.vpcForm.dados[key]);
			}
			for (let key in this.vpcForm.anexos) {
				form_data.append('anexos[]', this.vpcForm.anexos[key]);
			}
			form_data.set('dados[saldo]', this.valorVPC);
			this.$axios.post(`vpc`, form_data)
			.then(() => {
				this.button = "Enviado com sucesso!";
				this.vpcForm = {assunto_vpc_id: null, dados: {}, files: []};
				this.showForm = false;
				alert("Sua solicitação foi feita com sucesso.");
				this.$router.push({name: 'listaVpc'});
			})
			.catch(error => {
				this.button = "Solicitar";
				this.disabled = false;
				if (error.response.status == 422)
					this.errors = error.response.data.errors;
			})
			.finally(() => {
				setTimeout(() => {
					this.button = "Solicitar";
					this.disabled = false;
				}, 5000)
			})
		}
	}
}
</script>

<style lang="stylus" scoped src="./VPC.styl"></style>
