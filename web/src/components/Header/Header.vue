<template lang="pug">
	#header(:class="{ aviso: $route.name === 'avisoNivel' }")
		router-link(:to="{ name: 'dashboard' }", tag="h1")
			| Relacionamento de Canais xxw - Select
			img(src="@images/logos/relacionamento-canais-xxw-v1.png")
		ul
			li(v-for="item in menu" v-if="item.name != $route.name")
				router-link(:to="{name : item.name}") {{item.label}}
		.perfil
			div.detalhes
				p Olá,
					span {{ $auth.user.nome_fantasia }}
				.botoes
					router-link(:to="{name : 'meuPerfil'}").botao meu perfil
					a(href="#logout", @click.prevent="logout") Sair
			div.selo(v-if="$auth.user.selo")
				img(:src="$auth.user.selo")
				router-link(:to="{ name: 'dashboard' }", tag="h1")
					| Relacionamento de Canais xxw - Select
					img(src="@images/logos/relacionamento-canais-xxw-v1.png")
</template>

<script>
	export default {
		name: "component-header",
		watch:{
			$route (){
				this.changeMenu();
			}
		},
		methods: {
			logout() {
				this.$auth.logout()
				.then(() => this.$router.replace({name: 'home'}))
			},
			changeMenu(){
				if(this.$auth.user.is_distribuidor){
					if(this.menu[this.$route.name])
						this.menu[this.$route.name] = {'name' : 'listaVpc','label':'Solicitação VPC'};
				}
			},
		},
		created(){
			this.changeMenu();
		},
		data() {
			return {
				menu: {
					'faq': {'name' : 'faq','label' : 'Perguntas frequentes'},
					'dashboard': {'name' : 'dashboard','label' : 'Dashboard'},
					'posts': {'name' : 'posts','label' : 'Treinamentos/Conteúdo'},
				},

			}
		},
	}
</script>

<style lang="stylus" scoped src="./Header.styl"></style>
