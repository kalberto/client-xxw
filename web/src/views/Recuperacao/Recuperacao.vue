<template lang="pug">
	article#recuperacao
		SvgIcon(:data="LogoS", original)
		form(@submit.prevent="send")
			p Insira o CNPJ cadastrado para recuperar o acesso.
			.input
				input(name="input_cnpj", id="input_cnpj", placeholder="CNPJ", type="tel", v-mask="['##.###.###/####-##']", v-model="formulario.documento", autocomplete="off")
				span(v-if="errors.documento").error {{ errors.documento[0] }}

			.button
				button(type="submit", v-html="button", :disabled="disabled")
</template>

<script>
	import LogoS from '@svgs/logo_s.svg'
	import {mask} from 'vue-the-mask'

	export default {
		name: "view-recuperacao",
		directives: {
			mask
		},
		data() {
			return {
				LogoS,
				errors: {},
				button: 'Enviar',
				disabled: false,
				formulario: {
					documento: '',
				}
			}
		},
		methods: {
			send() {
				this.disabled = true
				this.button = `Enviando...`

				this.$auth.recover(this.formulario)
				.then(response => {
					this.button = `Sucesso`;
					if(response.email){
						alert("Enviamos um e-mail para "+response.email + " com instruções para que possa refazer a sua senha.")
					}else{
						alert("Enviamos um e-mail para o e-mail cadastrado nesse CNPJ com instruções para que possa refazer a sua senha.");
					}
					setTimeout(() => {
						this.button = `Enviar`
						this.disabled = false
						this.$router.push({ name: 'login' });
					}, 5000)
				})
				.catch(error => {
					this.button = `Erro`;
					if (error.status == 422){
						this.errors = error.data.errors;
						if(error.data.primeiro_acesso){
							alert('Esse CNPJ ainda não fez o primeiro acesso ao site. Acesse utilizando seu CNPJ como senha.')
						}
					}
					setTimeout(() => {
						this.button = `Enviar`
						this.disabled = false
					}, 5000)
				});
			}
		},
	}
</script>

<style lang="stylus" scoped src="./Recuperacao.styl"></style>
<style lang="stylus" scoped src="@stylus/formulario.styl"></style>
