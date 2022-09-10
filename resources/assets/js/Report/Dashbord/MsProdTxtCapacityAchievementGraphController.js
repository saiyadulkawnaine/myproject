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
		let params={};
		let date_from=$('#txtcapacityachivmentgraphFrm  [name=date_from]').val();
		let date_to=$('#txtcapacityachivmentgraphFrm  [name=date_to]').val();
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
		
		let diff=(12-(frommm*1))+(tomm*1);

		if(diff>11 && toyyyy != fromyyyy){
			alert('Maximum 12 months allowed');
			return;
		}
		var is_amount=0;
		if(route=='getgraphqty'){
		var title='Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphamount')
		{
		var title='Central Plan On Amount';
		is_amount=1;
		}
		var p = $('#txtcapgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', title);
		let d= axios.get(this.route+"/"+route,{params})
		.then(function (response) {
			MsProdTxtCapacityAchievementGraph.createChart(response,is_amount);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response,is_amount){

		var txtcapgraphwindowcontainerlayoutcenter = document.getElementById('txtcapgraphwindowcontainerlayoutcenter');
		txtcapgraphwindowcontainerlayoutcenter.innerHTML = '';
		$.each(response.data, function(key, value) {
			var labels = value.map(function(e) {
			return e.name;
			});
			var caps = value.map(function(e,index) {
			return e.cap;
			});
			var boks = value.map(function(e,index) {
			return e.bok;
			});
			var bokus = value.map(function(e,index) {
			return e.boku;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			var exfs = value.map(function(e,index) {
			return e.exf;
			});

			
			var containerid='txtcapgraphwindowcontainer'+key;
			$('#txtcapgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+'</h1>');
            var canvasid='txtcapgraphwindowcontainercom'+key;
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
			var mixedChart= new Chart(ctx1, {
			type: 'bar',
			data: {
				datasets: [
					{
						label: 'Capacity',
						data: caps,
						borderColor:'rgba(255, 0, 0, 1)',
						borderWidth:1,
						type: 'line',
					},
					{
						label: 'Inhouse Target',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks,
						stack:1
					},
					{
						label: 'Subcon. Target',
						backgroundColor: 'rgba(5, 155, 255, 1)',
						data: bokus,
						stack:1
					},
					{
						label: 'Produced',
						backgroundColor: 'rgba(108, 124, 158, 1)',
						data: prods,
						stack:2
					}, 
					{
						label: 'Delivery',
						backgroundColor: 'rgba(178, 113, 49, 1)',
						data: exfs,
						stack:3
					}
				],
				labels: labels
			},
			options: {
				

				scales: {
					xAxes: [{
					stacked: true,
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true
						},
						stacked:true,
					}]
				},
				title: {
					display: true,
					text: 'Central Plan ('+key+')'
				},
				
				tooltips: {
					mode: 'point',
					intersect:false,
					callbacks: {
						afterTitle: function() {
						window.total = 0;
						},
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label;
							var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
							window.total += valor;
							if(is_amount==1){
							return label + " Amount: $" + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						    }
						    else{
							return label + " Qty: " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						    }
						},
						footer: function() {
							if(is_amount==1){
							return "Total Amount: $" + window.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						    }
						    else{
							return "Total Qty: " + window.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						    }
						}
						/*label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							if(is_amount==1){
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
						}*/
					}
				}
			}
		});
		
		});


		/*var labels = response.data.com4.map(function(e) {
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
		});*/


		
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsProdTxtCapacityAchievementGraph=new MsProdTxtCapacityAchievementGraphController(new MsProdTxtCapacityAchievementGraphModel());