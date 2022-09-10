let MsProdGmtCapacityAchievementGraphDayModel = require('./MsProdGmtCapacityAchievementGraphDayModel');
require('./../../datagrid-filter.js');

class MsProdGmtCapacityAchievementGraphDayController {
	constructor(MsProdGmtCapacityAchievementGraphDayModel)
	{
		this.MsProdGmtCapacityAchievementGraphDayModel = MsProdGmtCapacityAchievementGraphDayModel;
		this.formId='capacityachivmentgraphdayFrm';
		this.dataTable='#capacityachivmentgraphdayTbl';
		this.route=msApp.baseUrl()+"/capacityachivmentgraphday"
	}
	
	get(route){
		if(route=='getgraph'){
		var title='Sewing Day Plan On Qty';
		}
		else if(route=='getgraphcut')
		{
		var title='Cutting Day Plan On Qty';
		}
		else if(route=='getgraphsp')
		{
		var title='Screen Print Day Plan On Qty';
		}
		else if(route=='getgraphemb')
		{
		var title='Embroidery Day Plan On Qty';
		}

		else if(route=='getgraphsewmintprod')
		{
		var title='Production Capacity & Target Chart In Minute';
		}

		var p = $('#gmtcapgraphwindowcontainerdaylayout').layout('panel', 'center').panel('setTitle', title);
		let params={};
		let date_from=$('#capacityachivmentgraphdayFrm  [name=date_from]').val();
		let date_to=$('#capacityachivmentgraphdayFrm  [name=date_to]').val();
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
		let d= axios.get(this.route+'/'+route,{params})
		.then(function (response) {
			if(route=='getgraphsewmintprod'){
				MsProdGmtCapacityAchievementGraphDay.createChartMint(response)

			}
			else{
				MsProdGmtCapacityAchievementGraphDay.createChart(response)
			}
		})
		.catch(function (error) {
		alert('Problem Found')
		});

	}

	createChart(response){
		var gmtcapgraphwindowcontainerdaylayoutcenter = document.getElementById('gmtcapgraphwindowcontainerdaylayoutcenter');
		gmtcapgraphwindowcontainerdaylayoutcenter.innerHTML = '';
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
			var tgts = value.map(function(e,index) {
			return e.tgt;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			

			var containerid='gmtcapgraphwindowcontainerday'+key;
			$('#gmtcapgraphwindowcontainerdaylayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+'</h1>');
            var canvasid='gmtcapgraphwindowcontainerdaycom'+key;
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
					text: 'Day Plan ('+key+')'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							return label+" Qty : " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
							return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
						}
					}
				}
			}
			});
		});


		/*var labels = response.data.group.map(function(e) {
			return e.name;
		});

		var caps = response.data.group.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.group.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.group.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.group.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday = document.getElementById('gmtcapgraphwindowcontainerday');
		gmtcapgraphwindowcontainerday.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday').append('<h1 style="background:#ccc ">All Company</h1>');
		$('#gmtcapgraphwindowcontainerday').append('<canvas id="gmtcapgraphwindowcontainerdaycanvas1"><canvas>');
		var ctx = $("#gmtcapgraphwindowcontainerdaycanvas1").get(0).getContext("2d");

		//var ctx = gmtcapgraphwindowcontainerdaycanvas1.getContext('2d');
		var mixedChart= new Chart(ctx, {
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
					text: 'Day Plan (All Company)'
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

		var labels = response.data.com1.map(function(e) {
			return e.name;
		});

		var caps = response.data.com1.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com1.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com1.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com1.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday1 = document.getElementById('gmtcapgraphwindowcontainerday1');
		gmtcapgraphwindowcontainerday1.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday1').append('<h1 style="background:#ccc ">LAL</h1>');
		$('#gmtcapgraphwindowcontainerday1').append('<canvas id="gmtcapgraphwindowcontainerdaycom1"><canvas>');
		var ctx1 = $("#gmtcapgraphwindowcontainerdaycom1").get(0).getContext("2d");

		//var ctx1 = gmtcapgraphwindowcontainerdaycom1.getContext('2d');
		var mixedChart= new Chart(ctx1, {
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
					text: 'Day Plan (LAL)'
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

		var labels = response.data.com2.map(function(e) {
			return e.name;
		});

		var caps = response.data.com2.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com2.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com2.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com2.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday2 = document.getElementById('gmtcapgraphwindowcontainerday2');
		gmtcapgraphwindowcontainerday2.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday2').append('<h1 style="background:#ccc ">A21</h1>');
		$('#gmtcapgraphwindowcontainerday2').append('<canvas id="gmtcapgraphwindowcontainerdaycom2"><canvas>');
		var ctx2 = $("#gmtcapgraphwindowcontainerdaycom2").get(0).getContext("2d");

		//var ctx2 = gmtcapgraphwindowcontainerdaycom2.getContext('2d');
		var mixedChart= new Chart(ctx2, {
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
					text: 'Day Plan (A21)'
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

		var gmtcapgraphwindowcontainerday4 = document.getElementById('gmtcapgraphwindowcontainerday4');
		gmtcapgraphwindowcontainerday4.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday4').append('<h1 style="background:#ccc ">FFL</h1>');
		$('#gmtcapgraphwindowcontainerday4').append('<canvas id="gmtcapgraphwindowcontainerdaycom4"><canvas>');
		var ctx4 = $("#gmtcapgraphwindowcontainerdaycom4").get(0).getContext("2d");

		//var ctx4 = gmtcapgraphwindowcontainerdaycom4.getContext('2d');
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
		});*/
	}
	createChartMint(response){
		var gmtcapgraphwindowcontainerdaylayoutcenter = document.getElementById('gmtcapgraphwindowcontainerdaylayoutcenter');
		gmtcapgraphwindowcontainerdaylayoutcenter.innerHTML = '';
		$.each(response.data, function(key, value) {
			var labels = value.map(function(e) {
			return e.name;
			});
			var caps = value.map(function(e,index) {
			return e.cap;
			});
			/*var boks = value.map(function(e,index) {
			return e.bok;
			});*/
			var tgts = value.map(function(e,index) {
			return e.tgt;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			

			var containerid='gmtcapgraphwindowcontainerday'+key;
			$('#gmtcapgraphwindowcontainerdaylayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+'</h1>');
            var canvasid='gmtcapgraphwindowcontainerdaycom'+key;
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
						type: 'line'
					},
					/*{
						label: 'Tgt. Need',
						backgroundColor: 'rgba(124, 31, 24, 1)',
						data: boks
					},*/ 
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
					text: 'Day Plan ('+key+')'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label || '';							
							return label+" Minute : " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
							return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
						}
					}
				}
			}
			});
		});


		/*var labels = response.data.group.map(function(e) {
			return e.name;
		});

		var caps = response.data.group.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.group.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.group.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.group.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday = document.getElementById('gmtcapgraphwindowcontainerday');
		gmtcapgraphwindowcontainerday.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday').append('<h1 style="background:#ccc ">All Company</h1>');
		$('#gmtcapgraphwindowcontainerday').append('<canvas id="gmtcapgraphwindowcontainerdaycanvas1"><canvas>');
		var ctx = $("#gmtcapgraphwindowcontainerdaycanvas1").get(0).getContext("2d");

		//var ctx = gmtcapgraphwindowcontainerdaycanvas1.getContext('2d');
		var mixedChart= new Chart(ctx, {
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
					text: 'Day Plan (All Company)'
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

		var labels = response.data.com1.map(function(e) {
			return e.name;
		});

		var caps = response.data.com1.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com1.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com1.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com1.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday1 = document.getElementById('gmtcapgraphwindowcontainerday1');
		gmtcapgraphwindowcontainerday1.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday1').append('<h1 style="background:#ccc ">LAL</h1>');
		$('#gmtcapgraphwindowcontainerday1').append('<canvas id="gmtcapgraphwindowcontainerdaycom1"><canvas>');
		var ctx1 = $("#gmtcapgraphwindowcontainerdaycom1").get(0).getContext("2d");

		//var ctx1 = gmtcapgraphwindowcontainerdaycom1.getContext('2d');
		var mixedChart= new Chart(ctx1, {
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
					text: 'Day Plan (LAL)'
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

		var labels = response.data.com2.map(function(e) {
			return e.name;
		});

		var caps = response.data.com2.map(function(e,index) {
			return e.cap;
		});

		var boks = response.data.com2.map(function(e,index) {
			return e.bok;
		});

		var tgts = response.data.com2.map(function(e,index) {
			return e.tgt;
		});

		var prods = response.data.com2.map(function(e,index) {
			return e.prod;
		});

		var gmtcapgraphwindowcontainerday2 = document.getElementById('gmtcapgraphwindowcontainerday2');
		gmtcapgraphwindowcontainerday2.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday2').append('<h1 style="background:#ccc ">A21</h1>');
		$('#gmtcapgraphwindowcontainerday2').append('<canvas id="gmtcapgraphwindowcontainerdaycom2"><canvas>');
		var ctx2 = $("#gmtcapgraphwindowcontainerdaycom2").get(0).getContext("2d");

		//var ctx2 = gmtcapgraphwindowcontainerdaycom2.getContext('2d');
		var mixedChart= new Chart(ctx2, {
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
					text: 'Day Plan (A21)'
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

		var gmtcapgraphwindowcontainerday4 = document.getElementById('gmtcapgraphwindowcontainerday4');
		gmtcapgraphwindowcontainerday4.innerHTML = '';
		$('#gmtcapgraphwindowcontainerday4').append('<h1 style="background:#ccc ">FFL</h1>');
		$('#gmtcapgraphwindowcontainerday4').append('<canvas id="gmtcapgraphwindowcontainerdaycom4"><canvas>');
		var ctx4 = $("#gmtcapgraphwindowcontainerdaycom4").get(0).getContext("2d");

		//var ctx4 = gmtcapgraphwindowcontainerdaycom4.getContext('2d');
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
		});*/
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsProdGmtCapacityAchievementGraphDay=new MsProdGmtCapacityAchievementGraphDayController(new MsProdGmtCapacityAchievementGraphDayModel());