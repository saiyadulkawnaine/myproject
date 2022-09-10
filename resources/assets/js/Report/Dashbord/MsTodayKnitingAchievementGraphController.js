let MsTodayKnitingAchivementGraphModel = require('./MsTodayKnitingAchivementGraphModel');
require('./../../ext.chart.js');

class MsTodayKnitingAchievementGraphController {
	constructor(MsTodayKnittingAchivementGraphModel)
	{
		this.MsTodayKnittingAchivementGraphModel = MsTodayKnittingAchivementGraphModel;
		this.formId='todayknitingachivmentgraphFrm';
		this.dataTable='#todayknitingachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/todayknitingachivementgraph"
	}

	
	get(){
		let params={};
		let date_to=$('#todayknitingachivmentgraphFrm  [name=date_to]').val();
		params.date_to = date_to
		if(date_to==''){
			alert('Please Select a Date ');
			return;
		}
		let d= axios.get(this.route+"/getgraph",{params})
		.then(function (response) {
			MsTodayKnitingAchievementGraph.createChart(response.data.dayAmtdatas,1,1,'Today Amount');
			MsTodayKnitingAchievementGraph.createChart2(response.data.dayQtydatas,2,0,'Today Quantity');

			MsTodayKnitingAchievementGraph.createChart(response.data.monAmtdatas,3,1,'Current Month Amount');
			MsTodayKnitingAchievementGraph.createChart2(response.data.monQtydatas,4,0,'Current Month Quantity');

			//MsTodayDyeingAchievementGraph.createChart(response.data.dayMktQtydatas,5,0,'Today Quantity');
			//MsTodayDyeingAchievementGraph.createChart(response.data.dayMktAmtdatas,6,1,'Today Amount');

			//MsTodayDyeingAchievementGraph.createChart(response.data.monMktQtydatas,7,0,'Current Month Quantity');
			//MsTodayDyeingAchievementGraph.createChart(response.data.monMktAmtdatas,8,1,'Current Month Amount');
		})
		.catch(function (error) {
		console.log(error);
		});
	}


	

	createChart(data,divid,is_amount,title_tex){
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
			Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 5;
			});
			Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 10;
			});
			},
			})
			var labels = data.graphdata.map(function(e,index) {
			return e.name;
			});
			var caps = data.graphdata.map(function(e,index) {
			return e.cap;
			});
			var tgts = data.graphdata.map(function(e,index) {
			return e.tgt;
			});
			var prods = data.graphdata.map(function(e,index) {
			return e.prod;
			});


		    document.getElementById('divtodayknitingachivmentgraph_'+divid).innerHTML='';
            var canvasid='canvastodayknitingachivmentgraph_'+divid;
            $('#divtodayknitingachivmentgraph_'+divid).append('<canvas id="'+canvasid+'"><canvas>');
            $('#divtodayknitingachivmentgraphtemp_'+divid).html(data.htmldata.substr(1).slice(0, -1));


			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'horizontalBar',
					data: {
						datasets: [
							
							{
								label: 'Earned',
								data: prods,
								backgroundColor:'rgba(163, 0, 23, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'Earning Tgt',
								data: tgts,
								backgroundColor:'rgba(47, 248, 242, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'M/C Cost',
								data: caps,
								backgroundColor:'rgba(2, 20, 68, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
						],
						labels: labels
					},
					options: {
						responsive: true,
                        maintainAspectRatio: false,
						scales: {
							yAxes: [{
								stacked: true,
								id: "bar-y-axis1",
								barThickness: 20,
							}],
							xAxes: [{
								stacked: false,
								ticks: {
								beginAtZero: true
								},
							}]
						},
						title: {
							display: true,
							text: title_tex,
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';
									if(is_amount){
										return label + " Amount: BDT " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
									else{
										return label + " Qty: " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
																
								}
							}
						}
					}
				});
	}


	createChart2(data,divid,is_amount,title_tex){
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
			Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 5;
			});
			Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 10;
			});
			},
			})
			var labels = data.graphdata.map(function(e,index) {
			return e.name;
			});
			var caps = data.graphdata.map(function(e,index) {
			return e.cap;
			});
			var tgts = data.graphdata.map(function(e,index) {
			return e.tgt;
			});
			var prods = data.graphdata.map(function(e,index) {
			return e.prod;
			});


		    document.getElementById('divtodayknitingachivmentgraph_'+divid).innerHTML='';
            var canvasid='canvastodayknitingachivmentgraph_'+divid;
            $('#divtodayknitingachivmentgraph_'+divid).append('<canvas id="'+canvasid+'"><canvas>');
            $('#divtodayknitingachivmentgraphtemp_'+divid).html(data.htmldata.substr(1).slice(0, -1));


			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'horizontalBar',
					data: {
						datasets: [
							
							{
								label: 'Produced',
								data: prods,
								backgroundColor:'rgba(163, 0, 23, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'Tgt Prod',
								data: tgts,
								backgroundColor:'rgba(47, 248, 242, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'Capacity',
								data: caps,
								backgroundColor:'rgba(2, 20, 68, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
						],
						labels: labels
					},
					options: {
						responsive: true,
                        maintainAspectRatio: false,
						scales: {
							yAxes: [{
								stacked: true,
								id: "bar-y-axis1",
								barThickness: 20,
							}],
							xAxes: [{
								stacked: false,
								ticks: {
								beginAtZero: true
								},
							}]
						},
						title: {
							display: true,
							text: title_tex,
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';
									if(is_amount){
										return label + " Amount: BDT " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
									else{
										return label + " Qty: " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
																
								}
							}
						}
					}
				});
	}

	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsTodayKnitingAchievementGraph=new MsTodayKnitingAchievementGraphController(new MsTodayKnitingAchivementGraphModel());