<template lang="pug">
	#filtro(:class="{'ativo': filtroAtivo}")
		.button
			button(@click="openFiltro()")
				i.first
				i.second
				i.third
		.filtro
			router-link(:to="{ name: 'posts' }", tag="h1")
				| Relacionamento de Canais xxw - Select
				img(src="@images/logos/relacionamento-canais-xxw-v1.png")
			Selo.selo
			form(@submit.prevent="$emit('loadPosts')")
				p
					| Busque por eventos e conteúdos
				.group
					.input._100
						label Categorias
						select(v-model="params.categoria")
							option(value="1" selected) Todos
							option(value="2") Conteúdos
							option(value="3") Eventos
				//-.group
					.input._50
						label Categorias
						select
							option Categoria
				.group(v-if="params.categoria == 3")
					.input._100
						label Confirmados
						select(v-model="params.confirmado")
							option(value="1") Todos
							option(value="2") Confirmados
							option(value="3") Não Confirmados
				.group(v-if="params.categoria == 3")
					.input._100
						label Completos
						select(v-model="params.completo")
							option(value="1") Todos
							option(value="2") Não começou
							option(value="3") Em andamento
							option(value="4") Completos
				button Procurar

			router-link(:to="{ name: 'faq' }").pagina
				| Perguntas Frequentes
			router-link(:to="{ name: 'dashboard' }").pagina
				| Dashboard
			router-link(:to="{ name: 'listaVpc' }", v-if="$auth.user.is_distribuidor").pagina
				| Solicitação VPC
			a(href="#logout", @click.prevent="logout") Sair
</template>

<script>
	import Selo from '@components/Selo/Selo.vue'

	export default {
		name: "component-filtro",
		props: {
			params: {
				required: true,
				type: Object,
			}
		},
		components: {
			Selo
		},
		data() {
			return {
				filtroAtivo: false
			}
		},
		methods: {
			openFiltro() {
				this.filtroAtivo = !this.filtroAtivo
			},
			logout() {
				this.$auth.logout()
				.then(() => this.$router.replace({name: 'home'}))
			}
		},
	}
</script>

<style lang="stylus" scoped src="./Filtro.styl"></style>
