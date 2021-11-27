<template lang="pug">
	article#avisoNivel
		Header
		.container
			.background
			.aviso(v-if="$auth.user.upgrade_nivel")
				h2 Parabéns!
					br
					| {{message}}
				p
					|Continue assim para crescer ainda mais.
					br
					|A xxw sempre estará com você.
				.continuar
					router-link( :to="{ name: 'dashboard' }") Continuar
			.aviso(v-else)
				h2 Atenção!
					br
					|Você desceu  de categoria.
				p
					|Seus benefícios serão reduzidos,
					|mas você pode contar com o nosso
					|time para reavaliar seu desempenho
					|e voltar ao lugar mais alto.
				.continuar
					router-link(:to="{ name: 'dashboard' }") Continuar
			.modelo
				img(src="@images/geral/modelo.png", alt="")
			.images
				.selo
					img.oldSelo(:src="$auth.user.old_selo")
					img.currentSelo(:src="$auth.user.selo")
				.upgrade
					img(:src="$auth.user.old_selo")
					img(src="@images/icons/arrow_forward_black.png")
					img(:src="$auth.user.selo")
</template>

<script>
// import qs from 'qs'
import Header from '@components/Header/Header'

export default {
	name: "view-avisoNivel",
	components: {
		Header
	},
	data() {
		return {
			message:"",
		};
	},
	mounted() {
		if(this.$auth.user.is_distribuidor)
			this.message = 'Você alcançou  os resultados  do trimestre e  subiu de categoria.';
		else
			this.message = 'Você alcançou  os resultados  e  subiu de categoria.';
	}
}
</script>

<style lang="stylus" scoped src="./AvisoNivel.styl"></style>
