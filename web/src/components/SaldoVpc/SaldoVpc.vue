<template lang="pug">
	#saldoVpc
		ul(v-for="(item, index) in saldos").saldos
			li
				span Mês
				p {{item.mes}}
			li
				span Saldo Total
				p R$ {{item.saldo_total}}
			li
				span Saldo Provisionado
				p R$ {{item.saldo_provisionado}}
			li
				span Saldo Disponível
				p R$ {{item.saldo_disponivel}}
			li
				span Validade
				p {{item.validade}}
</template>

<script>
export default {
	name: "component-saldo-vpc",
	data() {
		return {
			saldos: []
		}
	},
	mounted() {
		this.loadSaldoVPCs();
		this.$eventbus.$on('updateSaldo', this.updateSaldoVpc)
	},
	methods: {
		loadSaldoVPCs() {
			this.$axios.get(`vpc/saldo`)
			.then(response => {
				this.saldos = response.data.registros;
			})
		},
		updateSaldoVpc(data){
			for (let i=0; i < data.provisonado.length; i++){
				let x = this.saldos.length - 1 - i;
				this.saldos[x].saldo_provisionado = data.provisonado[i];
				this.saldos[x].saldo_disponivel = data.disponivel[i];
			}
		}
	}
}
</script>

<style lang="stylus" scoped src="./SaldoVpc.styl"></style>
