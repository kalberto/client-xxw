<script>
import {Bar} from 'vue-chartjs'

export default {
	name: "component-grafico-barra",
	extends: Bar,
	data() {
		return {
			ano: null,
			options:  {responsive: true, maintainAspectRatio: false,
				tooltips: {
					mode: 'label',
					label: 'Meta',
					callbacks: {
						label: function(tooltipItem) { return 'R$ ' + tooltipItem.yLabel.toString().replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g, "."); },
					},
				},
				scales: {
					yAxes: [
						{
							ticks: {
								beginAtZero: true,
								callback: (label) => {
									return 'R$ ' + label.toFixed(0).toString().replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								}
							}
						}
					]
				},
			},
			datacollection: {
				labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril','Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				datasets: [
					{
						label: 'Meta',
						backgroundColor: '#bd8e4d',
						data: []
					},
					{
						label: 'Valor',
						backgroundColor: '#252860',
						data: []
					}
				]
			},
		}
	},
	mounted() {
		this.renderChart(this.datacollection,this.options);
		this.$eventbus.$on('updateAno', this.updateAno);
	},
	methods: {
		updateAno(ano) {
			this.ano = ano;
			this.loadDetalhamento();
		},
		loadDetalhamento() {
			this.$axios.get(`meta/anos/${this.ano}/detalhamento`)
			.then(response => {
				this.datacollection.datasets = [
					{
						label: 'Meta',
						backgroundColor: '#bd8e4d',
						data: response.data.metas,
					},
					{
						label: 'Valor',
						backgroundColor: '#252860',
						data: response.data.valores,
					}
				];
				this.renderChart(this.datacollection,this.options);
			});
		}
	}
}
</script>

<style lang="stylus" scoped src="./GraficoBarra.styl"></style>
