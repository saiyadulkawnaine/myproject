class MsDashBordController 
{
	constructor()
	{
		
	}

	prodfabriccapacityachievementWindow()
	{
		$('#prodfabriccapacityachievementWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement")
		.then(function (response) {
			$('#prodfabriccapacityachievementContainer').html(response.data);
			$.parser.parse('#prodfabriccapacityachievementContainer');
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    prodfabriccapacityachievementWindow()
	{
		$('#prodfabriccapacityachievementWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement")
		.then(function (response) {
			$('#prodfabriccapacityachievementContainer').html(response.data);
			$.parser.parse('#prodfabriccapacityachievementContainer');
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    todayshipmentWindow(){
		    
			$('#todayShipmentWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/todayShipment")
			.then(function (response) {
			$('#todayShipmentContainer').html(response.data);
			$.parser.parse('#todayShipmentContainer');
			MsTodayShipment.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	 pendingshipmentWindow(){
		    
			$('#pendingShipmentWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/pendingshipment")
			.then(function (response) {
			$('#pendingShipmentContainer').html(response.data);
			$.parser.parse('#pendingShipmentContainer');
			MsPendingShipment.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}


	 prodgmtcapacityachievementWindow(){
		    
			$('#prodgmtcapacityachievementWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement")
			.then(function (response) {
			$('#prodgmtcapacityachievementContainer').html(response.data);
			$.parser.parse('#prodgmtcapacityachievementContainer');
			MsProdGmtCapacityAchievement.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	liabilitycoveragereportsWindow(){
			$('#liabilitycoveragereportsWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/liabilitycoveragereport")
			.then(function (response) {
			$('#liabilitycoveragereportsContainer').html(response.data);
			$.parser.parse('#liabilitycoveragereportsContainer');
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	samplereportWindow(flie_src){
			$('#samplerequirementWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/samplerequirement")
			.then(function (response) {
			$('#samplerequirementContainer').html(response.data);
			$.parser.parse('#samplerequirementContainer');
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	  todayaccountWindow(){
		    
			$('#todayAccountWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/todayaccount")
			.then(function (response) {
			$('#todayAccountContainer').html(response.data);
			$.parser.parse('#todayAccountContainer');
			//MsTodayAccount.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}


	receiptspaymentsaccountWindow(){
		    
			$('#receiptspaymentsaccountWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/receiptspaymentsaccount")
			.then(function (response) {
			$('#receiptspaymentsaccountContainer').html(response.data);
			$.parser.parse('#receiptspaymentsaccountContainer');
			//MsTodayAccount.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}


	bepWindow(){
		    
			$('#todaybepWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/todaybep")
			.then(function (response) {
			$('#todaybepContainer').html(response.data);
			$.parser.parse('#todaybepContainer');
			MsDashBordBep.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	todayInventoryReportWindow(){

	$('#todayInventoryReportWindow').window('open');
	let d= axios.get(msApp.baseUrl()+"/todayinventoryreport")
	.then(function (response) {
	$('#todayInventoryReportContainer').html(response.data);
	$.parser.parse('#todayInventoryReportContainer');
	//MsTodayAccount.get();
	})
	.catch(function (error) {
	console.log(error);
	});
	}

	gmtCapGraphWindow(){
		$('#gmtcapgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/capacityachivmentgraph")
		.then(function (response) {
			$('#gmtcapgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#gmtcapgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	orderforcastingWindow(){
		$('#orderforcastingwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/orderforcast")
		.then(function (response) {
			$('#orderforcastingwindowcontainerwraper').html(response.data);
			$.parser.parse('#orderforcastingwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	gmtCapShipGraphWindow(){
		$('#gmtcapshipgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/capacityshipdategraph")
		.then(function (response) {
			$('#gmtcapshipgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#gmtcapshipgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	gmtCapGraphDayWindow(){
		$('#gmtcapgraphdaywindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/capacityachivmentgraphday")
		.then(function (response) {
			$('#gmtcapgraphwindowcontainerwraperday').html(response.data);
			$.parser.parse('#gmtcapgraphwindowcontainerwraperday');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	prodgmtAllGraphWindow(){
		$('#gmtallcapgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/allcapacityachivmentgraph")
		.then(function (response) {
			$('#gmtallcapgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#gmtallcapgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}


	txtCapGraphWindow(){
		$('#txtcapgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/txtcapacityachivmentgraph")
		.then(function (response) {
			$('#txtcapgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#txtcapgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	txtCapGraphDayWindow(){
		$('#txtcapgraphdaywindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/txtcapacityachivmentgraphday")
		.then(function (response) {
			$('#txtcapgraphwindowcontainerwraperday').html(response.data);
			$.parser.parse('#txtcapgraphwindowcontainerwraperday');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	todaySewingAchivementWindow(){

		$('#todaysewingachivementgraphwindow').window({
		onClose: function(){
		document.location.reload()
		}
		})

		$('#todaysewingachivementgraphwindow').window('open');

		

		let d= axios.get(msApp.baseUrl()+"/todaysewingachivementgraph")
		                                  
		.then(function (response) {
			$('#todaysewingachivementgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#todaysewingachivementgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}


	todayDyeingAchivementWindow(){

		$('#todaydyeingachivementgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/todaydyeingachivementgraph")
		.then(function (response) {
			$('#todaydyeingachivementgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#todaydyeingachivementgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	todayAopAchivementWindow(){

		$('#todayaopachivementgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/todayaopachivementgraph")
		.then(function (response) {
			$('#todayaopachivementgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#todayaopachivementgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	todayKnitingAchivementWindow(){

		$('#todayknitingachivementgraphwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/todayknitingachivementgraph")
		.then(function (response) {
			$('#todayknitingachivementgraphwindowcontainerwraper').html(response.data);
			$.parser.parse('#todayknitingachivementgraphwindowcontainerwraper');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	dailyefficiencyWindow(){
		    
			$('#dailyefficiencyWindow').window('open');
			let d= axios.get(msApp.baseUrl()+"/dailyefficiencyreport")
			.then(function (response) {
			$('#dailyefficiencyContainer').html(response.data);
			$.parser.parse('#dailyefficiencyContainer');
			//MsProdGmtCapacityAchievement.get();
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	groupSaleWindow(){
		$('#groupsalewindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/groupsales")
		.then(function (response) {
			$('#groupSaleContainer').html(response.data);
			$.parser.parse('#groupSaleContainer');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	groupReceivableWindow(){
		$('#groupreceivablewindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/groupreceivables")
		.then(function (response) {
			$('#groupReceivableContainer').html(response.data);
			$.parser.parse('#groupReceivableContainer');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	centralBudgetWindow(){
		$('#centralbudgetwindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/centralbudgets")
		.then(function (response) {
			$('#centralbudgetContainer').html(response.data);
			$.parser.parse('#centralbudgetContainer');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	assetIdleTimeWindow(){
		$('#assetidletimewindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/assetbreakdownreport")
		.then(function (response) {
			$('#assetidletimeContainer').html(response.data);
			$.parser.parse('#assetidletimeContainer');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	txtdailyefficiencyWindow(){
		    
		$('#txtdailyefficiencyWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/txtdailyefficiencyreport")
		.then(function (response) {
		$('#txtdailyefficiencyContainer').html(response.data);
		$.parser.parse('#txtdailyefficiencyContainer');
		//MsProdGmtCapacityAchievement.get();
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	targetAchievementWindow(){
		    
		$('#targetachievementWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/targetachievementreport")
		.then(function (response) {
		$('#targetachievementContainer').html(response.data);
		$.parser.parse('#targetachievementContainer');
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	bankLoanWindow(){
		    
		$('#bankloanWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/bankloanreport")
		.then(function (response) {
		$('#bankloanContainer').html(response.data);
		$.parser.parse('#bankloanContainer');
		})
		.catch(function (error) {
		console.log(error);
		});
	}

}	
window.MsDashBord=new MsDashBordController();

