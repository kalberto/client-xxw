<template lang="pug">
	article#dashboardMini
		Header
		.container
			.grafico
				div.meta
					i
					p Meta Trimestral:
						span(v-if="meta && meta.meta_mes") R$ {{meta.meta_trimestre}}
				div.realizado
					i
					p Realizado:
						span(v-if="meta && meta.valor_mes") R$ {{meta.valor_trimestre}}
				div.svg
					svg(viewBox="-2 -2 40 40")
						path(class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
						path(class="circle" :style="'stroke-dasharray:' + porcentagemTrimestral +', 100'"  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
					p.valor {{porcentagemTrimestral}}%
				// button
				//     |Ver detalhamento
			.grafico
				div.meta
					i
					p Meta:
						span(v-if="meta && meta.meta_mes") R$ {{meta.meta_mes}}
				div.realizado
					i
					p Realizado:
						span(v-if="meta && meta.valor_mes") R$ {{meta.valor_mes}}
				div.svg
					svg(viewBox="-2 -2 40 40")
						path(class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
						path(class="circle" :style="'stroke-dasharray:' + porcentagem +', 100'"  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
					p.valor {{porcentagem}}%
			.resultado
				h2 Acompanhe seus resultados
				p
					img(:src="require('@images/icons/atualizado-em.png')")
					span Atualizado em: {{meta.updated_at}}
				table
					tr(v-if="nivel && nivel.nivel")
						td Classificação Atual
						td {{nivel.nivel}}
					tr(v-if="nivel && nivel.desconto")
						td Desconto da Classificação
						td {{nivel.desconto}}
					tr(v-if="nivel && nivel.vpc && nivel.perfil == 'Distribuidor'")
						td VPC(%)
						td {{nivel.vpc}}
					tr(v-if="meta && meta.valor_falta_vpc && nivel.perfil == 'Distribuidor'")
						td Quanto falta para VPC
						td R$ {{meta.valor_falta_vpc}}
					tr(v-if="nivel && nivel.rebate")
						td Rebate(%)
						td {{nivel.rebate}}
					tr(v-if="meta && meta.valor_falta_rebate")
						td Quanto falta para Rebate
						td R$ {{meta.valor_falta_rebate}}
		router-link(:to="{name: 'dashboardDetalhada'}")
			|Ver detalhamento
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
				nivel: null,
				meta: null,
				porcentagem:0,
				porcentagemTrimestral:0
			}
		},
		created() {
			if (this.$route.params.params)
				this.params = this.$route.params.params;
		},
		mounted() {
			this.loadNivel();
			this.loadMeta()
		},
		methods: {
			loadNivel() {
				this.$axios.get(`nivel`)
				.then(response => {
					this.nivel = response.data
				})
			},
			loadMeta() {
				this.$axios.get(`meta`)
				.then(response => {
					this.meta = response.data;
					if(this.meta.error)
						return;
					this.porcentagem = this.meta.valor_mes_value*100/this.meta.meta_mes_value;
					this.porcentagem = (Math.round(this.porcentagem * 100) / 100).toFixed(2);
					if(this.porcentagem > 100)
						this.porcentagem = 100;
					if(this.porcentagem < 0)
						this.porcentagem = 0;
					this.porcentagemTrimestral = this.meta.valor_trimestre_value*100/this.meta.meta_trimestre_value;
					this.porcentagemTrimestral = (Math.round(this.porcentagemTrimestral * 100) / 100).toFixed(2);
					if(this.porcentagemTrimestral > 100)
						this.porcentagemTrimestral = 100;
					if(this.porcentagemTrimestral < 0)
						this.porcentagemTrimestral = 0;
				})
			},
		}
	}
</script>

<style lang="stylus" scoped src="./DashboardMini.styl"></style>
