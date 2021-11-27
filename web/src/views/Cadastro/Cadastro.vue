<template lang="pug">
	article#cadastro
		SvgIcon(:data="LogoS", original)
		form(@submit.prevent="send")
			p Preencha os dados abaixo:
			.input
				input(name="input_nome", id="input_nome", placeholder="Nome Completo", type="text", v-model="formulario.contato_responsavel", autocomplete="off", :class="[errors.contato_responsavel ? 'has-error' : '']")
				span(v-if="errors.contato_responsavel").error {{ errors.contato_responsavel[0] }}
			.input
				input(name="input_nascimento", id="input_nascimento", placeholder="Data de nascimento", type="tel", v-mask="['##/##/####']", v-model="formulario.data_nascimento", autocomplete="off", :class="[errors.data_nascimento ? 'has-error' : '']")
				span(v-if="errors.data_nascimento").error {{ errors.data_nascimento[0] }}
			.input
				input(name="input_password", id="input_password", placeholder="Senha", type="password", v-model="formulario.password", autocomplete="off", :class="[errors.password ? 'has-error' : '']")
				span(v-if="errors.password").error {{ errors.password[0] }}
			.input
				input(name="input_re_password", id="input_re_password", placeholder="Confirme sua senha", type="password", v-model="formulario.re_password", autocomplete="off", :class="[errors.re_password ? 'has-error' : '']")
				span(v-if="errors.re_password").error {{ errors.re_password[0] }}
			.input
				input(name="input_email", id="input_email", placeholder="Email", type="email", v-model="formulario.email", autocomplete="off", :class="[errors.email ? 'has-error' : '']")
				span(v-if="errors.email").error {{ errors.email[0] }}
			.input
				input(name="input_re_email", id="input_re_email", placeholder="Confirme seu email", type="email", v-model="formulario.re_email", autocomplete="off", :class="[errors.re_email ? 'has-error' : '']")
				span(v-if="errors.re_email").error {{ errors.re_email[0] }}
			.input
				input(name="input_cnpj", id="input_cnpj", placeholder="CNPJ", type="tel", v-mask="['##.###.###/####-##']", v-model="formulario.documento", autocomplete="off", :class="[errors.documento ? 'has-error' : '']")
				span(v-if="errors.documento").error {{ errors.documento[0] }}
			.input.check
				label(for="input_termos")
					input(name="input_termos", id="input_termos", type="checkbox", v-model="formulario.consentimento_lgpd")
					| Eu aceito os!{' '}
					a(href="#") Termos de Uso e Politica de Privacidade
					br(v-if="errors.consentimento_lgpd")
					span(v-if="errors.consentimento_lgpd").error {{ errors.consentimento_lgpd[0] }}
			.button
				button(type="submit", v-html="button", :disabled="disabled")
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
					data_nascimento: '',
					email: '',
					re_email: '',
					password: '',
					re_password: ''
				}
			}
		},
		methods: {
			send() {
				this.disabled = true;
				this.button = `Enviando...`;
				let form = Object.assign({}, this.formulario, {documento: this.formulario.documento.replace(/\D/gi, '')});
				this.$auth.atualizar(form)
				.then(() => {
					this.button = `Sucesso`;
					this.disabled = true;
					let user = this.$auth.__getUser();
					user.conta_atualizada = 1;
					this.$auth.__setUser(user);
					setTimeout(() => {
						this.$router.push({ name: 'dashboard' });
					},3000);
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
