<template lang="pug">
	article#listaVpc
		Header
		h3 Verba de Propaganda Cooperada
		h2 Precisando de ajuda para vender mais?
			strong  Solicite a VPC.
		p
			span VERBA DE APOIO PARA AÇÕES
			span.ponto &#9679;
			span PADRONIZAÇÃO DOS MATERIAIS xxw
			span.ponto &#9679;
			span CONSULTORIA DE MARKETING
		SaldoVpc
		.container
			router-link(:to="{name: 'nova-vpc'}").new Nova Solicitação
			table
				tr.head
					td Nome da VPC
					td Solicitação
					td Ação
					td Custo
					td Status
				tr(v-for="vpc, index in vpcs")
					td(data-label="Nome da VPC:") {{vpc.dados.nome}}
					td(data-label="Data da Solicitação: ") {{vpc.created_at}}
					td(data-label="Nome da Ação:") {{vpc.acao}}
					td(data-label="Nome da Ação:") {{vpc.dados.custo}}
					td(data-label="Status:", :class='vpc.status.status').status
						| {{vpc.status.nome}}
						router-link(title="EDITAR", :to="{name: 'vpc', params: {id: vpc.id}}" v-if="vpc.status.status == 'revisao'")
							img(:src="require('@images/icons/edit.png')")
						router-link(title="VISUALIZAR", :to="{name: 'vpc', params: {id: vpc.id}}" v-else)
							img(:src="require('@images/icons/view.png')")
						a(href="#", title="CANCELAR", @click="cancelarVPC(vpc.id)", v-if="vpc.status.status === 'aprovado' || vpc.status.status === 'pendente' || vpc.status.status === 'revisao'")
							img(:src="require('@images/icons/remove.png')")


</template>

<script>
import Header from '@components/Header/Header'
import SaldoVpc from '@components/SaldoVpc/SaldoVpc'

export default {
	name: "view-lista-vpc",
	components: {
		Header,
		SaldoVpc
	},
	data() {
		return {
			vpcs: [],
			saldosVpcs: []
		}
	},
	mounted() {
		this.loadVPC();
	},
	methods: {
		loadVPC() {
			this.$axios.get(`vpc/load`)
			.then(response => {
				this.vpcs = response.data.registros;
			})
		},
		cancelarVPC(vpc_id){
			let r = confirm("Você tem certeza que deseja cancelar essa VPC? Esse processo é irreversível.");
			if(r === true){
				this.$axios.get(`vpc/${vpc_id}/cancelar`)
				.then(() => {
					alert("Essa VPC foi cancelada.");
					this.loadVPC();
				})
				.catch(error => {
					if (error.response.status === 422 && error.response.data.msg){
						alert(error.response.data.msg);
						this.loadVPC();
					}
				});
			}
		}
	}
}
</script>

<style lang="stylus" scoped src="./ListaVpc.styl"></style>
