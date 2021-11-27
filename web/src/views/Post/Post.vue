<template lang="pug">
	article#post
		Filtro(:params="params", @loadPosts="goToPosts")
		.container
			h2
				span {{categoria}}
				br
				|{{post.nome}}
			.card
				//- .controls
					button.back
						img(:src="require('@images/icons/arrow.png')")
					button.next
						img(:src="require('@images/icons/arrow.png')")
				vuescroll(:ops="vueScrollConfig", ref="vuescroll")
					.content
						.medias
							.media(v-for="media in post.medias", :class="{ video: media.video, iframe: media.video_is_link }")
								img(:src="media.url_imagem", v-if="!media.video")
								video(:src="media.url_imagem", controls, preload="metadata", v-else-if="media.video && !media.video_link")
								iframe(:src="media.video_link", frameborder="0", allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture", allowfullscreen, v-else)
						div.content-wrapper
							.texto
								h3 {{ categoria }}
								h4 {{ post.titulo }}
								.info.evento(v-if="post.evento")
									strong Evento dia
									span {{post.data_inicio}}
								.info.conteudo(v-if="!post.evento")
									strong Postado em
									span {{post.data_inicio}}
								.texto
									p(v-html="post.texto")

								ul.documentos
									li(v-for="documento in post.documentos").documento
										a(:href="documento.file" target="_blank").download
											span {{ documento.name }}
											SvgIcon(data="@svgs/icon_download.svg", original)

							button.participar(v-if="post.evento && !post.confirmado", @click="participar()")
								| Quero Participar
							button.participar(v-if="post.evento && post.confirmado", @click="participar()")
								| Não quero Participar

			ul.relacionados
				router-link(:to="{ name: 'post', params: { slug: relacionado.slug } }", tag="li", v-for="relacionado in relacionados" :key="`${relacionado.id}-${relacionado.slug}`")
					div.img
						img(:src="getThumb(relacionado)")
					div.texto
						h3 {{ relacionado.titulo }}
						span {{ relacionado.data_inicio }}
</template>

<script>
	import Filtro from '@components/Filtro/Filtro.vue'
	import vuescroll from 'vuescroll/dist/vuescroll-native';

	export default {
		name: "view-post",
		components: {
			Filtro,
			vuescroll,
		},
		data() {
			return {
				post: {},
				params: {
					limit: 12,
					page: 1,
					total_pages: 1,
					categoria: 1,
					confirmado: 1,
					completo: 1
				},
				vueScrollConfig: {
					rail: {
						size: '7px',
					},
					bar: {
						background: '#d2af7f',
						onlyShowBarOnScroll: false,
						size: '6px',
					}
				}
			}
		},
		computed: {
			categoria() {
				if (this.post.categoria && this.post.categoria.length)
					return this.post.categoria[0].nome
				return ''
			},
			relacionados() {
				return this.post.conteudos_relacionados;
			},
		},
		created() {
			if(this.$route.params.params)
				this.params = this.$route.params.params;
			this.loadPost(this.$route.params.slug)
		},
		beforeRouteUpdate(to, from, next) {
			if (to.params.slug && to.params.slug != this.$route.params.slug)
				return this.loadPost(to.params.slug)
				.then(() => next())
			next()
		},
		methods: {
			goToPosts(){
				this.$router.replace({name: 'posts',params:{'params': this.params}})
			},
			async loadPost(slug = '') {
				return await this.$axios
				.get(`conteudo/${slug}`)
				.then(response => this.post = response.data.registros)
				.catch(() => this.$router.replace({name: 'posts'}))
			},
			participar(){
				this.$axios.post(`conteudo/${this.post.id}/participar`)
				.then(response => {
					this.post.confirmado = response.data.confirmado;
				});
			},
			getThumb(post) {
				let media = post.thumb;
				if (media)
					return media;
				return '#';
			},
		},
	}
</script>

<style lang="stylus" scoped src="./Post.styl"></style>
