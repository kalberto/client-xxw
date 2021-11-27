<template lang="pug">
	article#dashboard
		Header
		h2 Acompanhe seus resultados
		.container
			.pageList
				select(v-model="ano", @change="handleChangeAno()")
					option(:value="null" disabled) Selecione o Ano
					option(v-for="item in anos", :value="item") {{item}}
				select(v-model="trimestre", @change="getMeses()")
					option(:value="null" disabled) Selecione o Trimestre
					option(v-for="item in trimestres", :value="item.nome") {{item.nome}}
				ul.listaData(v-if="meses.length > 0" )
					li(v-for="(mes, index) in meses", :class="{'ativo': mesAtivo === index}")
						button(@click="loadMeta(index)") {{mes.nome}}
			.graficos
				.grafico
					div.meta
						i
						p Meta Trimestral:
							span R$ {{metaTrimestre.meta_formatado}}
					div.realizado
						i
						p Realizado:
							span R$ {{metaTrimestre.realizado_formatado}}
					div.svg
						svg(viewBox="-2 -2 40 40")
							path(class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
							path(class="circle" :style="'stroke-dasharray:' + metaTrimestre.porcentagem +', 100'"  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
						p.valor {{metaTrimestre.porcentagem}}%
				.grafico
					div.meta
						i
						p Meta:
							span R$ {{metaMensal.meta_formatado}}
					div.realizado
						i
						p Realizado:
							span R$ {{metaMensal.realizado_formatado}}
					div.svg
						svg(viewBox="-2 -2 40 40")
							path(class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
							path(class="circle" :style="'stroke-dasharray:' + metaMensal.porcentagem +', 100'"  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831")
						p.valor {{metaMensal.porcentagem}}%
			.resultados(v-if="metaMensal")
				div.resultado
					h4 Dados do mês
					.valores(v-if="nivel && nivel.vpc && nivel.perfil == 'Distribuidor'")
						span.head Quanto falta para VPC
						span.valor R$ {{metaMensal.valor_falta_vpc}}
					.valores
						span.head Quanto falta rebate
						span.valor R$ {{metaMensal.valor_falta_rebate}}
					.valores
						span.head Rebate Disponível
						span.valor R$ {{metaMensal.rebate_disponivel}}
		.container
			.bars
				GraficoBarra

</template>

<script>
// import qs from 'qs'
import Header from '@components/Header/Header'
import GraficoBarra from '@components/GraficoBarra/GraficoBarra'


export default {
	name: "view-sashboard",
	components: {
		Header,
		GraficoBarra
	},
	data() {
		return {
			nivel: null,
			anos: [],
			ano: null,
			trimestres: [],
			trimestre: null,
			meses: [],
			mesAtivo: null,
			metaMensal: {
				meta_valor: '',
				meta_formatado: '',
				realizado_valor: '',
				realizado_formatado: '',
				porcentagem: 0 ,
				valor_falta_vpc:'',
				valor_falta_rebate:'',
				rebate_disponivel:'',
			},
			metaTrimestre: {
				meta_valor: '',
				meta_formatado: '',
				realizado_valor: '',
				realizado_formatado: '',
				porcentagem: 0
			},
			detalhamento: {
				classificacao: '',
				desconto: '',
				vpc: '',
				rebate: ''
			},
		}
	},
	created() {
		if (this.$route.params.params)
			this.params = this.$route.params.params;
	},
	mounted() {
		this.loadNivel();
		this.loadAnos()
	},
	methods: {
		loadNivel() {
			this.$axios.get(`nivel`)
			.then(response => {
				this.nivel = response.data
			})
		},
		loadAnos() {
			this.$axios.get(`meta/anos`)
			.then(response => {
				this.anos = response.data;
			});
		},
		handleChangeAno(){
			this.$eventbus.$emit('updateAno', this.ano);
			this.loadTrimestres();
		},
		loadTrimestres() {
			this.$axios.get(`meta/anos/${this.ano}/trimestres`)
			.then(response => {
				this.trimestres = response.data;
			});
		},
		getMeses() {
			this.meses = this.trimestres[this.trimestre].array;
		},
		loadMeta(index) {
			this.mesAtivo = index;
			this.$axios.get(`meta/anos/${this.ano}/meses/${this.meses[index].valor}`)
			.then(response => {
				this.meta = response.data;
				if (response.data.error)
					return;
				this.metaTrimestre = response.data.meta_trimestre;
				this.metaTrimestre.porcentagem = this.calculatePorcentagem(this.metaTrimestre.realizado_valor, this.metaTrimestre.meta_valor);
				this.metaMensal = response.data.meta_mes;
				this.metaMensal.porcentagem = this.calculatePorcentagem(this.metaMensal.realizado_valor, this.metaMensal.meta_valor);
			});
		},
		calculatePorcentagem(realizado, total) {
			let porcentagem = realizado * 100 / total;
			porcentagem = (Math.round(porcentagem * 100) / 100).toFixed(2);
			if (porcentagem > 100)
				porcentagem = 100;
			if (porcentagem < 0)
				porcentagem = 0;
			return porcentagem;
		}
	}
}
</script>

<style lang="stylus" scoped src="./Dashboard.styl"></style>
