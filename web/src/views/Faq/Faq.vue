<template lang="pug">
	article#faq
		Header
		.container
			h2 Dúvidas sobre o Select?
			p
				| Confira as principais perguntas e respostas.
			ul
				li(v-for="item, index in faq" :class="{'ativo' : index == faqAtivo}" @click="toggleFaq(index)")
					h3(v-html="item.pergunta")
					p(v-html="item.resposta")
			form(@submit.prevent="sendForm")
				h2 Não encontrou o que precisava?
				p
					| Envie sua mensagem para nossa central de atendimento.
					br
					| Vamos entrar em contato para esclarecer as suas dúvidas.
				.group
					.input._50
						input(type="text", v-model="faqForm.email", placeholder="Email" disabled)
					.input._50
						select(v-model="faqForm.assunto_id")
							option(:value="null" disabled) Assunto
							option(v-for="item, index in assuntos", :value="item.id") {{item.assunto}}
						span(v-if="errors.assunto_id") O Campo é obrigatório
				.group
					.input._100
						textarea(placeholder="mensagem", v-model="faqForm.mensagem")
						span(v-if="errors.mensagem") {{errors.mensagem[0]}}
				.enviar
					button(type="submit", v-html="button", :disabled="disabled") Enviar
</template>

<script>
	// import qs from 'qs'
	import Header from '@components/Header/Header'

	export default {
		name: "view-faq",
		components: {
			Header
		},
		data() {
			return {
				faq: [],
				faqAtivo: null,
				faqForm: {
					email: '',
					assunto_id: null,
					documento: '',
					mensagem: '',
				},
				assuntos: [],
				errors: {},
				button: 'Entrar',
				disabled: false,
			}
		},
		created() {
			if (this.$route.params.params)
				this.params = this.$route.params.params;
			this.faqForm.email = this.$auth.user ? this.$auth.user.email : ''
		},
		mounted() {
			this.loadQuestions()
			this.loadAssuntos()
		},
		methods: {
			toggleFaq(index) {
				if (this.faqAtivo == index)
					this.faqAtivo = null
				else
					this.faqAtivo = index
			},
			loadQuestions() {
				this.$axios.get(`faq`)
				.then(response => {
					this.faq = response.data.registros
				})
			},
			loadAssuntos() {
				this.$axios.get(`contato/assuntos`)
				.then(response => {
					this.assuntos = response.data
				})
			},
			sendForm() {
				this.button = "Enviando"
				this.disabled = true
				this.faqForm.documento = this.$auth.user ? this.$auth.user.documento : ''

				this.$axios.post(`contato`, this.faqForm)
				.then(() => {
					this.button = "Enviado com sucesso!"
					this.faqForm.assunto_id = ''
					this.faqForm.email = this.$auth.__getUser().email;
					this.faqForm.mensagem = ''
				})
				.catch(error => {
					this.button = "Enviar"
					this.disabled = false
					if (error.response.status == 422)
						this.errors = error.response.data.errors
				})
				.finally(() => {
					setTimeout(() => {
						this.button = "Enviar"
						this.disabled = false
					}, 5000)
				})
			}
		}
	}
</script>

<style lang="stylus" scoped src="./Faq.styl"></style>
