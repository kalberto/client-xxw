<template lang="pug">
	article#login
		SvgIcon(:data="LogoS", original)
		form(@submit.prevent="send")
			p Bem-vindo, faça o login.
				span(v-if="firstAccess") Para seu primeiro login, utilize o CNPJ (somente números) como senha.
			.input
				input(name="input_cnpj", id="input_cnpj", placeholder="CNPJ", type="tel", v-mask="['##.###.###/####-##']", v-model="formulario.documento", autocomplete="off")
				span(v-if="errors.documento").error {{ errors.documento[0] }}
			.input
				input(name="input_password", id="input_password", placeholder="Senha", type="password", v-model="formulario.password", autocomplete="off")
				span(v-if="errors.password").error {{ errors.password[0] }}
			p.esqueceu Esqueceu sua senha?
				router-link(:to="{ name: 'recuperacao' }") Clique aqui
			.button
				button(type="submit", v-html="button", :disabled="disabled")
</template>

<script>
	import LogoS from '@svgs/logo_s.svg'
	import {mask} from 'vue-the-mask'

	export default {
		name: "view-login",
		directives: {
			mask
		},
		data() {
			return {
				LogoS,
				errors: {},
				button: 'Entrar',
				disabled: false,
				firstAccess: false,
				formulario: {
					documento: '',
					password: ''
				},
				acessoAdmin:false,

			}
		},
		mounted() {
			this.firstAccess = this.$route.query.firstAccess || false
			if (this.firstAccess) {
				alert("Para seu primeiro login, utilize o CNPJ(somente números) como senha.");
			}
			if(this.$route.query.login_token){
				this.acessoAdmin = true;
				this.sendAdmin();
			}
		},
		methods: {
			send() {
				this.disabled = true
				this.button = `Enviando...`
				let form = Object.assign({}, this.formulario, {documento: this.formulario.documento.replace(/\D/gi, '')})

				this.$auth.login(form)
				.then(() => {
					if(this.$auth.user.show_upgrade){
						this.$router.push({name: 'avisoNivel'})
					}else{
						this.$router.push({name: 'dashboard'})
					}
				})
				.catch(error => {
					if (error.status == 422) {
						this.errors = error.data.errors
						if (error.data.conta_atualizada) {
							alert('Notamos que está tentando acessar ao site com seu CNPJ como senha, porém você já fez o seu primeiro acesso e mudou a sua senha. Caso não lembre da senha cadastrada, clique em esqueceu a sua senha.');
						}
					}
				})
				.finally(() => {
					setTimeout(() => {
						this.button = `Entrar`
						this.disabled = false
					}, 5000)
				})
			},
			sendAdmin(){
				this.disabled = true
				this.button = `Autenticando...`
				let form = {
					'login_token' : this.$route.query.login_token
				};
				this.$auth.loginAdmin(form)
				.then(() => {
					if(this.$auth.user.show_upgrade){
						this.$router.push({name: 'avisoNivel'})
					}else{
						this.$router.push({name: 'dashboard'})
					}
				})
				.catch(error => {
					if (error.status == 422) {
						this.errors = error.data.errors
						if (error.data.token_expirado) {
							alert('Token expirado ou já utilizado.');
						}
						if(error.data.sem_token){
							alert('Token expirado ou já utilizado.');
						}
					}
				})
				.finally(() => {
					setTimeout(() => {
						this.button = `Entrar`
						this.disabled = false
					}, 5000)
				})
			}
		},
	}
</script>

<style lang="stylus" scoped src="./Login.styl"></style>
<style lang="stylus" scoped src="@stylus/formulario.styl"></style>
