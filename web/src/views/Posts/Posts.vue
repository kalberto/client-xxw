<template lang="pug">
	article#posts
		Filtro(:params="params", @loadPosts="loadPosts")
		.container
			h1
				| Relacionamento de Canais xxw
				img(src="@images/logos/relacionamento-canais-xxw-v1.png" alt="Relacionamento de Canais xxw", title="Relacionamento de Canais xxw")
			//- h2 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
			ul
				router-link(:to="{ name: 'post', params: { slug: post.slug, params:params } }", v-for="post, index in posts", :class="{ evento: post.evento, post: !post.evento }", tag="li", :key="`${post.id}-${post.slug}`")
					div.img
						img(:src="getThumb(post)", :alt="post.nome")
					div.texto
						div(v-if="post.evento").input
							input(type="checkbox", :id="'confirmado_'+index")
							label(:for="'confirmado_'+index", :class="{ confirmado: post.confirmado}")
								img(:src="require('@images/icons/checkbox.png')")
							span Participação confirmada
						h3 {{ post.titulo }}
						p {{ post.nome }}
			Paginacao(:params="params", @loadPosts="loadPosts")
</template>

<script>
	import qs from 'qs'

	import Paginacao from '@components/Paginacao/Paginacao.vue'
	import Filtro from '@components/Filtro/Filtro.vue'

	export default {
		name: "view-posts",
		components: {
			Paginacao,
			Filtro,
		},
		data() {
			return {
				posts: [],
				params: {
					limit: 12,
					page: 1,
					total_pages: 1,
					categoria: 1,
					confirmado: 1,
					completo: 1
				}
			}
		},
		created() {
			if(this.$route.params.params)
				this.params = this.$route.params.params;
			this.loadPosts()
		},
		methods: {
			loadPosts(page = 1) {
				let params = Object.assign({}, this.params, {page})
				this.$axios
				.get(`conteudo?${qs.stringify(params)}`)
				.then(response => {
					this.posts = response.data.registros.data
					this.params.total_pages = response.data.registros.last_page
					this.params.page = response.data.registros.current_page
				})
			},
			sendParticipacao(post_slug) {
				var teste = post_slug;
				if(post_slug != teste)
					post_slug = teste;
			},
			getThumb(post) {
				let media = post.thumb;
				if (media)
					return media;
				return '#';
			},
			getResumo(post) {
				let texto = post.texto.slice(0, 37)
				if (texto.length == 37)
					texto += '...'
				return texto
			},
		},
	}
</script>

<style lang="stylus" scoped src="./Posts.styl"></style>
