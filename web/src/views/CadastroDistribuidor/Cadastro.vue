<template lang="pug">
	article#cadastro
		SvgIcon(:data="LogoS", original)
		form(@submit.prevent="send")
			p Preencha os dados abaixo:
			.input
				input(name="input_cnpj", id="input_cnpj", placeholder="CNPJ", type="tel", v-mask="['##.###.###/####-##']", v-model="formulario.documento", autocomplete="off", :class="[errors.documento ? 'has-error' : '']")
				span(v-if="errors.documento").error {{ errors.documento[0] }}
			.input
				input(name="input_razao_social", id="input_razao_social", placeholder="Razão Social", type="text", v-model="formulario.razao_social", autocomplete="off", :class="[errors.razao_social ? 'has-error' : '']")
				span(v-if="errors.razao_social").error {{ errors.razao_social[0] }}
			.input
				input(name="input_nome", id="input_nome", placeholder="Nome Completo", type="text", v-model="formulario.contato_responsavel", autocomplete="off", :class="[errors.contato_responsavel ? 'has-error' : '']")
				span(v-if="errors.contato_responsavel").error {{ errors.contato_responsavel[0] }}

			.input
				input(name="input_celular", id="input_celular", placeholder="Celular", v-mask="['## #####-####']", type="text", v-model="formulario.telefone", autocomplete="off", :class="[errors.telefone ? 'has-error' : '']")
				span(v-if="errors.telefone").error {{ errors.telefone[0] }}
			.input
				input(name="input_email", id="input_email", placeholder="Email", type="email", v-model="formulario.email", autocomplete="off", :class="[errors.email ? 'has-error' : '']")
				span(v-if="errors.email").error {{ errors.email[0] }}
			.group
				.input._30
					select(v-model="formulario.estado_id" @change="loadCidades(formulario.estado_id)")
						option(disabled selected value="") Estados
						option(v-for="estado, index in estados" :value="estado.id") {{estado.uf}}
					span(v-if="errors.estado_id").error {{ errors.estado_id[0] }}
				.input._70
					select(v-model="formulario.cidade_id")
						option(disabled selected value="") Cidades
						option(v-for="cidade, index in cidades" :value="cidade.id") {{cidade.nome}}
					span(v-if="errors.cidade_id").error {{ errors.cidade_id[0] }}

			.input.check
				label(for="input_termos")
					input(name="input_termos", id="input_termos", type="checkbox", v-model="formulario.consentimento_lgpd")
					| Eu aceito os!{' '}
					a(href="#") Termos de Uso e Politica de Privacidade
					br(v-if="errors.consentimento_lgpd")
					span(v-if="errors.consentimento_lgpd").error {{ errors.consentimento_lgpd[0] }}
			.button
				button(type="submit", v-html="button", :disabled="disabled")
			p.texto(v-if="dadosEnviar")
				|Seus dados foram enviados, entraremos em contato.
</template>

<script>
	import LogoS from '@svgs/logo_s.svg'
	import {mask} from 'vue-the-mask'

	export default {
		name: "view-cadastro",
		directives: {
			mask
		},
		data() {
			return {
				LogoS,
				errors: {},
				button: 'Cadastrar',
				disabled: false,
				formulario: {
					documento: '',
					contato_responsavel: '',
					razao_social: '',
					telefone: '',
					email: '',
					cidade_id: '',
					estado_id: '',
				},
				estados: [],
				cidades: [],
				dadosEnviar: false,
			}
		},
		mounted() {
			this.loadEstados()
		},
		methods: {
			loadEstados() {
				this.$axios.get(`estados`)
					.then(response => {
						this.estados = response.data
					})
			},
			loadCidades(estado_id){
				this.$axios.get(`cidades/${estado_id}`)
					.then(response => {
						this.cidades = response.data
					})
			},
			send() {
				this.disabled = true;
				this.button = `Enviando...`;
				let form = Object.assign({}, this.formulario, {documento: this.formulario.documento.replace(/\D/gi, '')});
				this.$auth.preCadastro(form)
				.then(messagem => {
					this.button = `Sucesso`;
					this.disabled = true;
					this.dadosEnviar = messagem
				})
				.catch(error => {
					if (error.status == 422)
						this.errors = error.data.errors;
					setTimeout(() => {
						this.button = `Cadastrar`;
						this.disabled = false;
					}, 3000);
				});
			}
		},
	}
</script>

<style lang="stylus" scoped src="./Cadastro.styl"></style>
<style lang="stylus" scoped src="@stylus/formulario.styl"></style>
