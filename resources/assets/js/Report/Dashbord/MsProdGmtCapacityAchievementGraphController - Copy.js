let MsProdGmtCapacityAchievementGraphModel = require('./MsProdGmtCapacityAchievementGraphModel');
require('./../../datagrid-filter.js');

class MsProdGmtCapacityAchievementGraphController {
	constructor(MsProdGmtCapacityAchievementGraphModel)
	{
		this.MsProdGmtCapacityAchievementGraphModel = MsProdGmtCapacityAchievementGraphModel;
		this.formId='capacityachivmentgraphFrm';
		this.dataTable='#capacityachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/capacityachivmentgraph"
	}
	
	get(route){
		
		var is_amount=0;
		if(route=='getgraphqty'){
		var title='Sewing Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphqtycut'){
		var title='Cutting Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphqtysp'){
		var title='Screen Print Central Plan On Qty';
		is_amount=0;
		}

		else if(route=='getgraphqtyemb'){
		var title='Embroidery Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphamount')
		{
		var title='Sewing Central Plan On Amount';
		is_amount=1;
		}
		else if( route=='getgraphamountcut')
		{
		var title='Cutting Central Plan On Amount';
		is_amount=1;
	    }
	    else if(route=='getgraphamountsp')
		{
		var title='Screen Print Central Plan On Amount';
		is_amount=1;
	    }
	    else if(route=='getgraphamountemb')
		{
		var title='Embroidery Central Plan On Amount';
		is_amount=1;
	    }
		
		var p = $('#gmtcapgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', title);
		let d= axios.get(this.route+"/"+route)
		.then(function (response) {
			MsProdGmtCapacityAchievementGraph.createChart(response,is_amount);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response,is_amount){

		var labels = response.data.group.map(function(e) {
		return e.name;
		});
		var caps = response.data.group.map(function(e,index) {
		return e.cap;
		});
		var boks = response.data.group.map(function(e,index) {
		return e.bok;
		});
		var prods = response.data.group.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.group.map(function(e,index) {
		return e.exf;
		});


		var gmtcapgraphwindowcontainer = document.getElementById('gmtcapgraphwindowcontainer');
		gmtcapgraphwindowcontainer.innerHTML = '';
		$('#gmtcapgraphwindowcontainer').append('<h1 style="background:#ccc ">All Company</h1>');
		$('#gmtcapgraphwindowcontainer').append('<canvas id="gmtcapgraphwindowcontainercanvas1"><canvas>');
		var ctx = $("#gmtcapgraphwindowcontainercanvas1").get(0).getContext("2d");

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
					text: 'Central Plan (All Company)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
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
		var prods = response.data.com1.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com1.map(function(e,index) {
		return e.exf;
		});
		

		var gmtcapgraphwindowcontainer1 = document.getElementById('gmtcapgraphwindowcontainer1');
		gmtcapgraphwindowcontainer1.innerHTML = '';
		$('#gmtcapgraphwindowcontainer1').append('<h1 style="background:#ccc ">LAL</h1>');

		$('#gmtcapgraphwindowcontainer1').append('<canvas id="gmtcapgraphwindowcontainercom1"><canvas>');
		var ctx1 = $("#gmtcapgraphwindowcontainercom1").get(0).getContext("2d");

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
					text: 'Central Plan (LAL)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
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
		var prods = response.data.com2.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com2.map(function(e,index) {
		return e.exf;
		});

		var gmtcapgraphwindowcontainer2 = document.getElementById('gmtcapgraphwindowcontainer2');
		gmtcapgraphwindowcontainer2.innerHTML = '';
		$('#gmtcapgraphwindowcontainer2').append('<h1 style="background:#ccc ">A21</h1>');
		$('#gmtcapgraphwindowcontainer2').append('<canvas id="gmtcapgraphwindowcontainercom2"><canvas>');
		var ctx2 = $("#gmtcapgraphwindowcontainercom2").get(0).getContext("2d");

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
					text: 'Central Plan (A21)'
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
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
		var prods = response.data.com4.map(function(e,index) {
		return e.prod;
		});

		var exfs = response.data.com4.map(function(e,index) {
		return e.exf;
		});

		var gmtcapgraphwindowcontainer4 = document.getElementById('gmtcapgraphwindowcontainer4');
		gmtcapgraphwindowcontainer4.innerHTML = '';
		$('#gmtcapgraphwindowcontainer4').append('<h1 style="background:#ccc ">FFL</h1>');

		$('#gmtcapgraphwindowcontainer4').append('<canvas id="gmtcapgraphwindowcontainercom4"><canvas>');
		var ctx4 = $("#gmtcapgraphwindowcontainercom4").get(0).getContext("2d");

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
window.MsProdGmtCapacityAchievementGraph=new MsProdGmtCapacityAchievementGraphController(new MsProdGmtCapacityAchievementGraphModel());