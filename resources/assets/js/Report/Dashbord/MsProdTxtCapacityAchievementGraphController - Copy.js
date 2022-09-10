let MsProdTxtCapacityAchievementGraphModel = require('./MsProdTxtCapacityAchievementGraphModel');
require('./../../datagrid-filter.js');

class MsProdTxtCapacityAchievementGraphController {
	constructor(MsProdTxtCapacityAchievementGraphModel)
	{
		this.MsProdTxtCapacityAchievementGraphModel = MsProdTxtCapacityAchievementGraphModel;
		this.formId='txtcapacityachivmentgraphFrm';
		this.dataTable='#txtcapacityachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/txtcapacityachivmentgraph"
	}
	
	get(route){
		/*let params={};
		let date_from=$('#capacityachivmentgraphFrm  [name=date_from]').val();
		let date_to=$('#capacityachivmentgraphFrm  [name=date_to]').val();
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
		}*/


		//let d= axios.get(this.route,{params})
		if(route=='getgraphqty'){
		var title='Central Plan On Qty';
		}
		else if(route=='getgraphamount')
		{
		var title='Central Plan On Amount';
		}
		var p = $('#txtcapgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', title);
		let d= axios.get(this.route+"/"+route)
		.then(function (response) {
			MsProdTxtCapacityAchievementGraph.createChart(response,route);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response,route){


		var labels = response.data.com4.map(function(e) {
		return e.name;
		});
		var caps = response.data.com4.map(function(e,index) {
		return e.cap;
		});
		var boks = response.data.com4.map(function(e,index) {
		return e.bok;
		});
		var prods = response.data.com4.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com4.map(function(e,index) {
		return e.exf;
		});

		var txtcapgraphwindowcontainer4 = document.getElementById('txtcapgraphwindowcontainer4');
		txtcapgraphwindowcontainer4.innerHTML = '';
		$('#txtcapgraphwindowcontainer4').append('<h1 style="background:#ccc ">FFL</h1>');

		$('#txtcapgraphwindowcontainer4').append('<canvas id="txtcapgraphwindowcontainercom4"><canvas>');
		var ctx4 = $("#txtcapgraphwindowcontainercom4").get(0).getContext("2d");

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
						label: 'Target',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					},
					{
						label: 'Produced',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}, 
					{
						label: 'Exfactory',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: exfs
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
				}
				,
				title: {
					display: true,
					text: 'Central Plan (FFL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							if(route=='getgraphamount'){
								return label + " Amount: $ " + Number(tooltipItem.yLabel).toFixed(2).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
							}
							else
							{
								return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
						    }
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
		var prods = response.data.com5.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com5.map(function(e,index) {
		return e.exf;
		});

		var txtcapgraphwindowcontainer5 = document.getElementById('txtcapgraphwindowcontainer5');
		txtcapgraphwindowcontainer5.innerHTML = '';
		$('#txtcapgraphwindowcontainer5').append('<h1 style="background:#ccc ">FDL</h1>');

		$('#txtcapgraphwindowcontainer5').append('<canvas id="txtcapgraphwindowcontainercom5"><canvas>');
		var ctx5 = $("#txtcapgraphwindowcontainercom5").get(0).getContext("2d");

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
						label: 'Target',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					},
					{
						label: 'Produced',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}, 
					{
						label: 'Exfactory',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: exfs
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
					text: 'Central Plan (FDL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							if(route=='getgraphamount'){
								return label + " Amount: $ " + Number(tooltipItem.yLabel).toFixed(2).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
							}
							else
							{
								return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
						    }
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
		var prods = response.data.com6.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com6.map(function(e,index) {
		return e.exf;
		});

		var txtcapgraphwindowcontainer6 = document.getElementById('txtcapgraphwindowcontainer6');
		txtcapgraphwindowcontainer6.innerHTML = '';
		$('#txtcapgraphwindowcontainer6').append('<h1 style="background:#ccc ">FPL</h1>');
		$('#txtcapgraphwindowcontainer6').append('<canvas id="txtcapgraphwindowcontainercom6"><canvas>');
		var ctx6 = $("#txtcapgraphwindowcontainercom6").get(0).getContext("2d");

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
						label: 'Target',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					},
					{
						label: 'Produced',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods
					}, 
					{
						label: 'Exfactory',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: exfs
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
					text: 'Central Plan (FPL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							if(route=='getgraphamount'){
								return label + " Amount: $ " + Number(tooltipItem.yLabel).toFixed(2).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
							}
							else
							{
								return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
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
window.MsProdTxtCapacityAchievementGraph=new MsProdTxtCapacityAchievementGraphController(new MsProdTxtCapacityAchievementGraphModel());