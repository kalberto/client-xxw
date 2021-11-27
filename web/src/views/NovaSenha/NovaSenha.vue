<template lang="pug">
	article#novaSenha
		SvgIcon(:data="LogoS", original)
		form(@submit.prevent="send")
			p Preencha os dados abaixo:
			.input
				input(name="input_password", id="input_password", placeholder="Nova Senha", type="password", v-model="formulario.password", autocomplete="off", :class="[errors.password ? 'has-error' : '']")
				span(v-if="errors.password").error {{ errors.password[0] }}
			.input
				input(name="input_re_password", id="input_re_password", placeholder="Confirme sua senha", type="password", v-model="formulario.re_password", autocomplete="off", :class="[errors.re_password ? 'has-error' : '']")
				span(v-if="errors.re_password").error {{ errors.re_password[0] }}
			.input
				input(name="input_token", id="input_token", placeholder="Token", type="token", v-model="formulario.token", style="display:none")
			.button
				button(type="submit", v-html="button", :disabled="disabled")
</template>

<script>
	import LogoS from '@svgs/logo_s.svg'
	import {mask} from 'vue-the-mask'

	export default {
		name: "view-nova-senha",
		directives: {
			mask
		},
		data() {
			return {
				LogoS,
				errors: {},
				button: 'Nova Senha',
				disabled: false,
				formulario: {
					token: '',
					password: '',
					re_password: ''
				}
			}
		},
		created(){
			if(this.$route.query.token)
				this.formulario.token = this.$route.query.token;
		},
		methods: {
			send() {
				this.disabled = true;
				this.button = `Enviando...`;
				let form = Object.assign({}, this.formulario);
				this.$auth.reset(form)
				.then(() => {
					this.button = `Sucesso`;
					this.disabled = true;
					setTimeout(() => {
						this.$router.push({ name: 'login' });
					},3000);
				})
				.catch(error => {
					if (error.status == 422)
						this.errors = error.data.errors;
					setTimeout(() => {
						this.button = `Nova Senha`;
						this.disabled = false;
					}, 3000);
				});
			}
		},
	}
</script>

<style lang="stylus" scoped src="./NovaSenha.styl"></style>
<style lang="stylus" scoped src="@stylus/formulario.styl"></style>
