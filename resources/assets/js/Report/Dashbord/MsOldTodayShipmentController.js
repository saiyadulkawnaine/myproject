//require('./jquery.easyui.min.js');
let MsTodayShipmentModel = require('./MsTodayShipmentModel');
require('./../../datagrid-filter.js');

class MsTodayShipmentController {
	constructor(MsTodayShipmentModel)
	{
		this.MsTodayShipmentModel = MsTodayShipmentModel;
		this.formId='todayShipmentFrm';
		this.dataTable='#todayShipmentTbl';
		this.route=msApp.baseUrl()+"/orderwisebudget/getdata"
	}
	
	get(){
		let params={};
		params.company_id = $('#todayShipmentFrm  [name=company_id]').val();
		params.buyer_id = $('#todayShipmentFrm  [name=buyer_id]').val();
		params.style_ref = $('#todayShipmentFrm  [name=style_ref]').val();
		params.job_no = $('#todayShipmentFrm  [name=job_no]').val();
		params.date_from = $('#todayShipmentFrm  [name=date_from]').val();
		params.date_to = $('#todayShipmentFrm  [name=date_to]').val();
		params.order_status = $('#todayShipmentFrm  [name=order_status]').val();
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#todayShipmentTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tYarn=0;
				var tTrim=0;
				var tKniting=0;
				var tYarnDying=0;
				//var tWeaving=0;
				var tDying=0;
				var tAop=0;
				var tBurnOut=0;
				//var tFinishing=0;
				var tWashing=0;
				var tPrinting=0;
				var tEmb=0;
				var tSpEmb=0;
				var tGmtDying=0;
				var tGmtWashing=0;
				var tCourier=0;
				var tFreight=0;
				var tCm=0;
				var tcommi=0;
				var tcommer=0;
				var tTotal=0;
				var tProfit=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tYarn+=data.rows[i]['yarn_amount'].replace(/,/g,'')*1;
				tTrim+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
				tKniting+=data.rows[i]['kniting_amount'].replace(/,/g,'')*1;
				tYarnDying+=data.rows[i]['yarn_dying_amount'].replace(/,/g,'')*1;
				//tWeaving+=data.rows[i]['weaving_amount'].replace(/,/g,'')*1;
				tDying+=data.rows[i]['dying_amount'].replace(/,/g,'')*1;
				tAop+=data.rows[i]['aop_amount'].replace(/,/g,'')*1;
				tBurnOut+=data.rows[i]['burn_out_amount'].replace(/,/g,'')*1;
				//tFinishing+=data.rows[i]['finishing_amount'].replace(/,/g,'')*1;
				tWashing+=data.rows[i]['washing_amount'].replace(/,/g,'')*1;
				tPrinting+=data.rows[i]['printing_amount'].replace(/,/g,'')*1;
				tEmb+=data.rows[i]['emb_amount'].replace(/,/g,'')*1;
				tSpEmb+=data.rows[i]['spemb_amount'].replace(/,/g,'')*1;
				tGmtDying+=data.rows[i]['gmt_dyeing_amount'].replace(/,/g,'')*1;
				tGmtWashing+=data.rows[i]['gmt_washing_amount'].replace(/,/g,'')*1;
				tCourier+=data.rows[i]['courier_amount'].replace(/,/g,'')*1;
				tFreight+=data.rows[i]['freight_amount'].replace(/,/g,'')*1;
				tCm+=data.rows[i]['cm_amount'].replace(/,/g,'')*1;
				tcommi+=data.rows[i]['commi_amount'].replace(/,/g,'')*1;
				tcommer+=data.rows[i]['commer_amount'].replace(/,/g,'')*1;
				tTotal+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
				tProfit+=data.rows[i]['total_profit'].replace(/,/g,'')*1;
				}
				tProfitPer=(tProfit/tAmout)*100;
				tRate=(tAmout/tQty);
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yarn_amount: tYarn.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						trim_amount: tTrim.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						kniting_amount: tKniting.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yarn_dying_amount: tYarnDying.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//weaving_amount: tWeaving.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						dying_amount: tDying.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						aop_amount: tAop.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						burn_out_amount: tBurnOut.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//finishing_amount: tFinishing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						washing_amount: tWashing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						printing_amount: tPrinting.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						emb_amount: tEmb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						spemb_amount: tSpEmb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_dyeing_amount: tGmtDying.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_washing_amount: tGmtWashing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						courier_amount: tCourier.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						freight_amount: tFreight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_amount: tCm.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						commi_amount: tcommi.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						commer_amount: tcommer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_amount: tTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_profit: tProfit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_profit_per: tProfitPer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				/*if(!tEmb){
					$(this).datagrid('hideColumn', 'emb_amount');
				}*/
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	/*showGrid1()
	{
		let data={};
		data.company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		data.buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		data.style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		data.job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			fit:true,
			url:this.route,
			rownumbers:true,
			showFooter:true,
		}).datagrid('enableFilter');
	}*/

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	todayshipmentWindow(flie_src){
		    this.get();
			$('#todayShipmentWindow').window('open');
	}
	imageWindow(flie_src){
		var output = document.getElementById('orderWiseBudgetImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+flie_src;
    	            output.src =  fp;
			$('#orderWiseBudgetImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsTodayShipment.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	formatSaleOrder(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsSaleOrderWindow('+row.id+')">'+row.qty+'</a>';
	}

	formatYarn(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsYarnWindow('+row.id+')">'+row.yarn_amount+'</a>';
	}
	formatTrim(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsTrimWindow('+row.id+')">'+row.trim_amount+'</a>';
	}
	formatKnit(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsKnitWindow('+row.id+')">'+row.kniting_amount+'</a>';
	}
	formatYarnDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsYarnDyeingWindow('+row.id+')">'+row.yarn_dying_amount+'</a>';
	}
	formatWeaving(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.weaving_amount+'</a>';
	}
	formatDyeing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsDyeingWindow('+row.id+')">'+row.dying_amount+'</a>';
	}
	formatAop(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsAopWindow('+row.id+')">'+row.aop_amount+'</a>';
	}

	formatBurnOut(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsBocWindow('+row.id+')">'+row.burn_out_amount+'</a>';
	}
	formatFinishing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.finishing_amount+'</a>';
	}
	formatWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsFwcWindow('+row.id+')">'+row.washing_amount+'</a>';
	}

	formatPrinting(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsGpcWindow('+row.id+')">'+row.printing_amount+'</a>';
	}

	formatEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsGecWindow('+row.id+')">'+row.emb_amount+'</a>';
	}

	formatSpEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsGsecWindow('+row.id+')">'+row.spemb_amount+'</a>';
	}

	formatGmtDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsGdcWindow('+row.id+')">'+row.gmt_dyeing_amount+'</a>';
	}

	formatGmtWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsGwcWindow('+row.id+')">'+row.gmt_washing_amount+'</a>';
	}

	formatCourier(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsOthWindow('+row.id+')">'+row.courier_amount+'</a>';
	}

	formatFreight(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.freight_amount+'</a>';
	}

	formatCm(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.cm_amount+'</a>';
	}

	formatCommi(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.commi_amount+'</a>';
	}

	formatCommer(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsTodayShipment.detailsWindow('+row.id+')">'+row.commer_amount+'</a>';
	}

	detailsYarnWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getyarn?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbyarnWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getyarn?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbyarnWindow2').window('open');
		}
	}

	

	showGridYarn(data)
	{
		var dg = $('#OrdbudgetReportyarnTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			var tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['cons'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['yarn_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					cons: tCons.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_amount: tAmout.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	showGridYarn2(data)
	{
		var dg = $('#OrdbudgetReportyarnTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		rowStyler:function(index,row){
			if (row.yarn_des==='Sub Total'){
			return 'background-color:pink;color:blue;font-weight:bold;';
			}
		},
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			var tRate=0;
			for(var i=0; i<data.rows.length; i++){
				if(data.rows[i]['yarn_des'] !=='Sub Total')
				{
                tCons+=data.rows[i]['yarn_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['yarn_amount'].replace(/,/g,'')*1;
				}
				
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					yarn_qty: tCons.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_amount: tAmout.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsTrimWindow(id){
		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/gettrim?sale_order_id="+id);
			let g=data.then(function (response) {
				$('#OrdbudgetReporttrimTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
			$('#OrdbudgetReporttrimTbl').datagrid('loadData', response.data);
			}
*/			})
			.catch(function (error) {
			console.log(error);
			});
			$('#owbtrimWindow').window('open');
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
			let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
			let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
			let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
			let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
			let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
			let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
			let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/gettrim?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReporttrimTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
			
			//msApp.setHtml(key,response.data.dropDown[key]);
			$('#OrdbudgetReporttrimTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
			console.log(error);
			});
			$('#owbtrimWindow2').window('open');
		}
	}

	showGridTrim(data)
	{
		var dg = $('#OrdbudgetReporttrimTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			//var tCons=0;
			var tAmout=0;
			//tRate=0;
			for(var i=0; i<data.rows.length; i++){
				//tCons+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
			}
			//tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					//bom_trim: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridTrim2(data)
	{
		var dg = $('#OrdbudgetReporttrimTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			//var tCons=0;
			var tAmout=0;
			//tRate=0;
			for(var i=0; i<data.rows.length; i++){
				//tCons+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
			}
			//tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					//bom_trim: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsKnitWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getknit?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportknitTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbknitWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getknit?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportknitTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbknitWindow2').window('open');
		}
	}

	showGridKnit(data)
	{
		var dg = $('#OrdbudgetReportknitTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['kniting_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['kniting_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					kniting_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					kniting_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridKnit2(data)
	{
		var dg = $('#OrdbudgetReportknitTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['kniting_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['kniting_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					kniting_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					kniting_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsYarnDyeingWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getyarndyeing?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportyarndyeingTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbyarndyeingWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getyarndyeing?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportyarndyeingTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbyarndyeingWindow2').window('open');
		}
	}

	showGridYarnDyeing(data)
	{
		var dg = $('#OrdbudgetReportyarndyeingTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['yarn_dyeing_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['yarn_dyeing_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					yarn_dyeing_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_dyeing_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridYarnDyeing2(data)
	{
		var dg = $('#OrdbudgetReportyarndyeingTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['yarn_dyeing_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['yarn_dyeing_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					yarn_dyeing_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_dyeing_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsDyeingWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getdyeing?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportdyeingTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbdyeingWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getdyeing?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportdyeingTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbdyeingWindow2').window('open');
		}
	}

	showGridDyeing(data)
	{
		var dg = $('#OrdbudgetReportdyeingTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridDyeing2(data)
	{
		var dg = $('#OrdbudgetReportdyeingTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsAopWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getaop?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportaopTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbaopWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getaop?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportaopTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbaopWindow2').window('open');
		}
	}

	showGridAop(data)
	{
		var dg = $('#OrdbudgetReportaopTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridAop2(data)
	{
		var dg = $('#OrdbudgetReportaopTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsBocWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getboc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportbocTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbbocWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getboc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportbocTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbbocWindow2').window('open');
		}
	}

	showGridBoc(data)
	{
		var dg = $('#OrdbudgetReportbocTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridBoc2(data)
	{
		var dg = $('#OrdbudgetReportbocTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsFwcWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getfwc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportfwcTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbfwcWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getfwc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportfwcTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbfwcWindow2').window('open');
		}
	}

	showGridFwc(data)
	{
		var dg = $('#OrdbudgetReportfwcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridFwc2(data)
	{
		var dg = $('#OrdbudgetReportfwcTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}



	detailsGpcWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgpc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportgpcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgpcWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgpc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportgpcTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgpcWindow2').window('open');
		}
	}

	showGridGpc(data)
	{
		var dg = $('#OrdbudgetReportgpcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridGpc2(data)
	{
		var dg = $('#OrdbudgetReportgpcTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}



	detailsGecWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgec?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportgecTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgecWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgec?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportgecTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgecWindow2').window('open');
		}
	}

	showGridGec(data)
	{
		var dg = $('#OrdbudgetReportgecTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridGec2(data)
	{
		var dg = $('#OrdbudgetReportgecTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}




	detailsGsecWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgsec?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportgsecTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgsecWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgsec?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportgsecTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgsecWindow2').window('open');
		}
	}

	showGridGsec(data)
	{
		var dg = $('#OrdbudgetReportgsecTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridGsec2(data)
	{
		var dg = $('#OrdbudgetReportgsecTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}



	detailsGdcWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgdc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportgdcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgdcWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgdc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportgdcTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgdcWindow2').window('open');
		}
	}

	showGridGdc(data)
	{
		var dg = $('#OrdbudgetReportgdcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridGdc2(data)
	{
		var dg = $('#OrdbudgetReportgdcTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}



	detailsGwcWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgwc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportgwcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgwcWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getgwc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportgwcTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgwcWindow2').window('open');
		}
	}

	showGridGwc(data)
	{
		var dg = $('#OrdbudgetReportgwcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridGwc2(data)
	{
		var dg = $('#OrdbudgetReportgwcTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	detailsOthWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getoth?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#OrdbudgetReportothTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbothWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getoth?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportothTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbothWindow2').window('open');
		}
	}

	showGridOth(data)
	{
		var dg = $('#OrdbudgetReportothTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tAmout=0;
			for(var i=0; i<data.rows.length; i++){
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			$(this).datagrid('reloadFooter', [
				{ 
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	showGridOth2(data)
	{
		var dg = $('#OrdbudgetReportothTbl2');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			$(this).datagrid('reloadFooter', [
				{ 
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}


	
	detailsSaleOrderWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getsalesorder?sale_order_id="+id);
			let g=data.then(function (response) {
				$('#owbscs').html(response.data);
			//$('#OrdbudgetReportsalesorderTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbsalesorderWindow').window('open');	
		}
		else
		{
			let company_id = $('#orderwisebudgetFrm  [name=company_id]').val();
		    let buyer_id = $('#orderwisebudgetFrm  [name=buyer_id]').val();
		    let style_ref = $('#orderwisebudgetFrm  [name=style_ref]').val();
		    let job_no = $('#orderwisebudgetFrm  [name=job_no]').val();
		    let date_from = $('#orderwisebudgetFrm  [name=date_from]').val();
		    let date_to = $('#orderwisebudgetFrm  [name=date_to]').val();
		    let order_status = $('#orderwisebudgetFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/orderwisebudget/getsalesorder?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#owbscs').html(response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbsalesorderWindow').window('open');
		}
	}
}
window.MsTodayShipment=new MsTodayShipmentController(new MsTodayShipmentModel());
MsTodayShipment.showGrid({rows :{}});
MsTodayShipment.showGridYarn({rows :{}});
MsTodayShipment.showGridYarn2({rows :{}});
MsTodayShipment.showGridTrim({rows :{}});
MsTodayShipment.showGridTrim2({rows :{}});
MsTodayShipment.showGridKnit({rows :{}});
MsTodayShipment.showGridKnit2({rows :{}});
MsTodayShipment.showGridYarnDyeing({rows :{}});
MsTodayShipment.showGridYarnDyeing2({rows :{}});
MsTodayShipment.showGridDyeing({rows :{}});
MsTodayShipment.showGridDyeing2({rows :{}});
MsTodayShipment.showGridAop({rows :{}});
MsTodayShipment.showGridAop2({rows :{}});

MsTodayShipment.showGridBoc({rows :{}});
MsTodayShipment.showGridBoc2({rows :{}});

MsTodayShipment.showGridFwc({rows :{}});
MsTodayShipment.showGridFwc2({rows :{}});
MsTodayShipment.showGridGpc({rows :{}});
MsTodayShipment.showGridGpc2({rows :{}});

MsTodayShipment.showGridGec({rows :{}});
MsTodayShipment.showGridGec2({rows :{}});

MsTodayShipment.showGridGsec({rows :{}});
MsTodayShipment.showGridGsec2({rows :{}});

MsTodayShipment.showGridGdc({rows :{}});
MsTodayShipment.showGridGdc2({rows :{}});

MsTodayShipment.showGridGwc({rows :{}});
MsTodayShipment.showGridGwc2({rows :{}});

MsTodayShipment.showGridOth({rows :{}});
MsTodayShipment.showGridOth2({rows :{}});
