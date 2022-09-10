let MsProdTxtCapacityAchievementGraphDayModel = require('./MsProdTxtCapacityAchievementGraphDayModel');
require('./../../datagrid-filter.js');

class MsProdTxtCapacityAchievementGraphDayController {
	constructor(MsProdTxtCapacityAchievementGraphDayModel)
	{
		this.MsProdTxtCapacityAchievementGraphDayModel = MsProdTxtCapacityAchievementGraphDayModel;
		this.formId='txtcapacityachivmentgraphdayFrm';
		this.dataTable='#txtcapacityachivmentgraphdayTbl';
		this.route=msApp.baseUrl()+"/txtcapacityachivmentgraphday/getgraph"
	}
	
	get(){
		let params={};
		let date_from=$('#txtcapacityachivmentgraphdayFrm  [name=date_from]').val();
		let date_to=$('#txtcapacityachivmentgraphdayFrm  [name=date_to]').val();
		params.date_from = date_from
		params.date_to = date_to;

		if( date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(date_to==''){
			alert('Please Select a date range');
			return;
		}

		let from=new Date(date_from);
		let to=new Date(date_to);

		var fromDate = new Date(
		from.getFullYear(),
		from.getMonth(),
		from.getDate(),
		from.getHours(),
		from.getMinutes(),
		from.getSeconds()
		);
		var fromyyyy = fromDate.getFullYear().toString();                                    
		var frommm = (fromDate.getMonth()+1).toString();//getMonth() is zero-based

		var toDate = new Date(
		to.getFullYear(),
		to.getMonth(),
		to.getDate(),
		to.getHours(),
		to.getMinutes(),
		to.getSeconds()
		);
		var toyyyy = toDate.getFullYear().toString();                                    
		var tomm = (toDate.getMonth()+1).toString();//getMonth() is zero-based

		if(fromyyyy != toyyyy){
			alert('Cross Year not allowed ');
			return;
		}

		if(frommm != tomm){
			alert('Cross month not allowed ');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			MsProdTxtCapacityAchievementGraphDay.createChart(response)
		})
		.catch(function (error) {
		alert('Problem Found')
		});

	}

	createChart(response){

		var labels = response.data.com4.map(function(e) {
			return e.name;
		});

		var caps = response.data.com4.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com4.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com4.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com4.map(function(e,index) {
			return e.prod;
		});

		var txtcapgraphwindowcontainerday4 = document.getElementById('txtcapgraphwindowcontainerday4');
		txtcapgraphwindowcontainerday4.innerHTML = '';
		$('#txtcapgraphwindowcontainerday4').append('<h1 style="background:#ccc ">FFL</h1>');
		$('#txtcapgraphwindowcontainerday4').append('<canvas id="txtcapgraphwindowcontainerdaycom4"><canvas>');
		var ctx4 = $("#txtcapgraphwindowcontainerdaycom4").get(0).getContext("2d");

		//var ctx4 = txtcapgraphwindowcontainerdaycom4.getContext('2d');
		var mixedChart= new Chart(ctx4, {
			type: 'bar',
			data: {
				datasets: [
					{
						label: 'Capacity',
						data: caps,
						borderColor:'rgba(255, 0, 0, 1)',
						borderWidth:1,
						type: 'line'
					},
					{
						label: 'Tgt. Need',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					}, 
					{
						label: 'Actual Tgt.',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: tgts
					},
					{
						label: 'Tgt. Achieve',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}
				],
				labels: labels
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				},
				title: {
					display: true,
					text: 'Day Plan (FFL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
							return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
						}
					}
				}
			}
		});
		

		var labels = response.data.com5.map(function(e) {
			return e.name;
		});

		var caps = response.data.com5.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com5.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com5.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com5.map(function(e,index) {
			return e.prod;
		});

		var txtcapgraphwindowcontainerday5 = document.getElementById('txtcapgraphwindowcontainerday5');
		txtcapgraphwindowcontainerday5.innerHTML = '';
		$('#txtcapgraphwindowcontainerday5').append('<h1 style="background:#ccc ">FDL</h1>');
		$('#txtcapgraphwindowcontainerday5').append('<canvas id="txtcapgraphwindowcontainerdaycom5"><canvas>');
		var ctx5 = $("#txtcapgraphwindowcontainerdaycom5").get(0).getContext("2d");

		//var ctx1 = txtcapgraphwindowcontainerdaycom1.getContext('2d');
		var mixedChart= new Chart(ctx5, {
			type: 'bar',
			data: {
				datasets: [
					{
						label: 'Capacity',
						data: caps,
						borderColor:'rgba(255, 0, 0, 1)',
						borderWidth:1,
						type: 'line'
					},
					{
						label: 'Tgt. Need',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					}, 
					{
						label: 'Actual Tgt.',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: tgts
					},
					{
						label: 'Tgt. Achieve',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}
				],
				labels: labels
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				},
				title: {
					display: true,
					text: 'Day Plan (FDL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
							return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
						}
					}
				}
			}
		});

		var labels = response.data.com6.map(function(e) {
			return e.name;
		});

		var caps = response.data.com6.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com6.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com6.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com6.map(function(e,index) {
			return e.prod;
		});

		var txtcapgraphwindowcontainerday6 = document.getElementById('txtcapgraphwindowcontainerday6');
		txtcapgraphwindowcontainerday6.innerHTML = '';
		$('#txtcapgraphwindowcontainerday6').append('<h1 style="background:#ccc ">FPL</h1>');
		$('#txtcapgraphwindowcontainerday6').append('<canvas id="txtcapgraphwindowcontainerdaycom6"><canvas>');
		var ctx6 = $("#txtcapgraphwindowcontainerdaycom6").get(0).getContext("2d");

		//var ctx2 = txtcapgraphwindowcontainerdaycom2.getContext('2d');
		var mixedChart= new Chart(ctx6, {
			type: 'bar',
			data: {
				datasets: [
					{
						label: 'Capacity',
						data: caps,
						borderColor:'rgba(255, 0, 0, 1)',
						borderWidth:1,
						type: 'line'
					},
					{
						label: 'Tgt. Need',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					}, 
					{
						label: 'Actual Tgt.',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: tgts
					},
					{
						label: 'Tgt. Achieve',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}
				],
				labels: labels
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				},
				title: {
					display: true,
					text: 'Day Plan (FPL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
							return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
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
window.MsProdTxtCapacityAchievementGraphDay=new MsProdTxtCapacityAchievementGraphDayController(new MsProdTxtCapacityAchievementGraphDayModel());