<style>
div.chart-container {
	text-align: center;
	width: 100%;
	padding-left: 20px;
	padding-right: 20px;
}
div.chart {
	width: 100%;
}
</style>

<template>
	<div class="chart-container">
		<div class="chart"></div>
	</div>
</template>

<script>
	var Highcharts = require('highcharts');

	export default {
		props: ['values', 'noaltbands'],
		mixins: [],
		data() {
			return {
				target: null,
				value: 0
			}
		},
		watch: {
			// whenever question changes, this function will run
			values: 
				function (newValue, oldValue) {
					var series = this.target.series[0];
					series.setData(newValue);
					this.target.reflow();
				},
			loading:
				function (newValue, oldValue) {
					if (newValue) this.target.showLoading();
					else this.target.hideLoading();
				},
			noaltbands:
				function (newValue, oldValue) {
					var target = this.target;
					newValue.forEach(function(element) {
						target.xAxis[0].addPlotLine(element);
					});
				},
			showPlotPoints:
				function (newValue, oldValue) {
					if (newValue) {
						this.target.update({plotOptions: { line: { marker: { enabled: true }}}});
					} else {
						this.target.update({plotOptions: { line: { marker: { enabled: false }}}});
					}
				}
		},
		methods: {
		},
		created() {
		},
	compiled() {
		Highcharts.setOptions({
			time: {
				useUTC: false
			}
		});
		var that = this;

		this.target = Highcharts.chart(this.$el, {
			title: {
				text: null,
				align: 'left',
				margin: 0,
				x: 30
			},
			subtitle: null,
			chart: {
				height: 300,
				zoomType: 'x',
				backgroundColor:'rgba(255, 255, 255, 0.0)'
			},
			yAxis: {
				title: {
					text: "Altitude (feet)"
				}
			},
			xAxis: {
				type: 'datetime',
				crosshair: true,
				title: {
					text:  null
				}
			},
			legend: {
				enabled: false,
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'bottom'
			},
			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					}
				},
				line: {
					marker: {
						enabled: false,
						radius: 3
					}
				}
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'Alt',
				data: 0,
				point: {
					events: {
						mouseOver: function () {
							var chart = this.series.chart;
							//console.log(this.x + ' ' + this.y);
							that.$emit('showpoint', {x: this.x, y: this.y});
						}
					}
				},
				events: {
					mouseOut: function () {
						//console.log('out');
						that.$emit('mouseout');
					}
				}
			}],
			tooltip: {
				positioner: function () {
					return {
						//right aligned
						x: this.chart.chartWidth - this.label.width,
						y: 10 // align to title
					};
				},
				borderWidth: 0,
				backgroundColor: 'none',
				pointFormat: '{point.x:%A, %b %e, %Y} : {point.y}',
				formatter: function () {
					return Highcharts.dateFormat('%e-%b %H:%M:%S ',this.point.x) + ' | ' +  this.point.y + ' feet';
				},
				dateTimeLabelFormats: "%A, %b %e, %Y",
				headerFormat: '',
				shadow: false,
				style: {
					fontSize: '18px'
				}
			},
			responsive: {
				rules: [{
					condition: {
						maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}
		});

	},
	beforeDestroy: function() {
		this.target.destroy();
	}
	}
</script>
