let MsOrderInHandModel = require('./MsOrderInHandModel');
require('./datagrid-filter.js');
class MsOrderInHandController {
	constructor(MsOrderInHandModel)
	{
		this.MsOrderInHandModel = MsOrderInHandModel;
		this.formId='orderinhandFrm';
		this.dataTable='#orderinhandTbl';
		this.route=msApp.baseUrl()+"/orderinhand/getdata"
	}
	getParams()
	{
		let params={};
		params.company_id = $('#orderinhandFrm  [name=company_id]').val();
		params.buyer_id = $('#orderinhandFrm  [name=buyer_id]').val();
		params.style_ref = $('#orderinhandFrm  [name=style_ref]').val();
		params.job_no = $('#orderinhandFrm  [name=job_no]').val();
		params.month_from = $('#orderinhandFrm  [name=month_from]').val();
		params.month_to = $('#orderinhandFrm  [name=month_to]').val();
		params.order_status = $('#orderinhandFrm  [name=order_status]').val();
		params.year = $('#orderinhandFrm  [name=year]').val();
		return params;
	}

	 validate(params)
	 {
		if(params.year==''){
			alert("Select Year");
			return false;
		}
		if(params.month_from==''){
			alert("Select Month Range");
			return false;
		}
		if(params.month_to==''){
			alert("Select Month Range");
			return false;
		}
		return true;
	 }

	get()
	{
		let params=this.getParams();
		if(this.validate(params))
		{
            let d= axios.get(this.route,{params});
            return d; 
		}
	}

	show()
	{
		let data=this.get();
		data.then(function (response) {
			$('#orderinhandTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showExcel(table_id,file_name){
		let data=this.get();
		data.then(function (response) 
		{
			$('#orderinhandTbl').datagrid('loadData', response.data);
			msApp.toExcel(table_id,file_name);
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
			rowStyler:function(index,row)
			{
				if (row.company_code==='Sub Total'){
					return 'background-color:pink;color:black;font-weight:bold;';
				}
		    },
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tShipQty=0;
				var tShipBalance=0;
				var tYarn=0;
				var tTrim=0;
				var tFabPur=0;
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
				var tcompany=[];
				for(var i=0; i<data.rows.length; i++){
				if(data.rows[i]['company_code'] !=='Sub Total')
				{
				tcompany.push(data.rows[i]['company_id']);
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tShipQty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tShipBalance+=data.rows[i]['ship_balance'].replace(/,/g,'')*1;
				tYarn+=data.rows[i]['yarn_amount'].replace(/,/g,'')*1;
				tTrim+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
				tFabPur+=data.rows[i]['fab_pur_amount'].replace(/,/g,'')*1;
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
				}
				tProfitPer=(tProfit/tAmout)*100;
				tRate=(tAmout/tQty);
				let params=MsOrderInHand.getParams();
				let uniqueCompany=msApp.uniqueArray( tcompany );
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_qty: tShipQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_balance: tShipBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yarn_amount: tYarn.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						trim_amount: tTrim.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						fab_pur_amount: tFabPur.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
						month_from:params.month_from,
						month_to:params.month_to,
						id:0,
						company_id:uniqueCompany.join(',')
					}
				]);
				/*if(!tEmb){
					$(this).datagrid('hideColumn', 'emb_amount');
				}*/
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	
	
	formatimage(value,row)
	{
		if(row.flie_src)
		{
			return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOrderInHand.imageWindow('+'\''+row.flie_src+'\''+')"/>';
		}
		else
		{
			return row.flie_src;

		}
	}

	formatSaleOrder(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsSaleOrderWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')"> '+row.qty+'</a>';
	}

	formatYarn(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsYarnWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')">'+row.yarn_amount+'</a>';
	}
	formatTrim(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsTrimWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')">'+row.trim_amount+'</a>';
	}
	formatFabPur(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsFabPurWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')">'+row.fab_pur_amount+'</a>';
	}
	formatKnit(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsKnitWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.kniting_amount+'</a>';
	}
	formatYarnDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsYarnDyeingWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.yarn_dying_amount+'</a>';
	}
	formatWeaving(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.weaving_amount+'</a>';
	}
	formatDyeing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsDyeingWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.dying_amount+'</a>';
	}
	formatAop(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsAopWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.aop_amount+'</a>';
	}

	formatBurnOut(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsBocWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.burn_out_amount+'</a>';
	}
	formatFinishing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.finishing_amount+'</a>';
	}
	formatWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsFwcWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.washing_amount+'</a>';
	}

	formatPrinting(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsGpcWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.printing_amount+'</a>';
	}

	formatEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsGecWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.emb_amount+'</a>';
	}

	formatSpEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsGsecWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.spemb_amount+'</a>';
	}

	formatGmtDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsGdcWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.gmt_dyeing_amount+'</a>';
	}

	formatGmtWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsGwcWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.gmt_washing_amount+'</a>';
	}

	formatCourier(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsOthWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')">'+row.courier_amount+'</a>';
	}

	formatFreight(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.freight_amount+'</a>';
	}

	formatCm(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.cm_amount+'</a>';
	}

	formatCommi(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.commi_amount+'</a>';
	}

	formatCommer(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsWindow('+row.id+','+row.month_from+','+row.month_to+')">'+row.commer_amount+'</a>';
	}
	formatconsdzn(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.detailsConsDznWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')">'+row.fin_fab_cons+'</a>';
	}

	formatOverheadRate(value,row)
	{
		// return '<a href="javascript:void(0)" onClick="MsOrderInHand.bepWindow('+$row->company_id'+)">'+row.overhead_rate+'</a>';
        return '<a href="javascript:void(0)" onClick="MsOrderInHand.bepWindow('+row.company_id+','+row.month_from+','+row.month_to+')">'+row.overhead_rate+'</a>';
	}

	bepWindow (company_id)
	{
		//let params={};
		// let params={};
		// params.company_id=company_id;
		// params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		// params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getbep",{params});
		data.then(function (response) {
			$('#orderinhandbepmatrix').html(response.data);
			$('#orderinhandbepWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});	
	}

	imageWindow(flie_src){
		var output = document.getElementById('orderWiseBudgetImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#orderWiseBudgetImageWindow').window('open');
	}

	detailsYarnWindow(id,month_from,month_to,company_id)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getyarn",{params});
		data.then(function (response) {
			if(id)
			{
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
				$('#owbyarnWindow').window('open');	
			}
			else
			{
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
				$('#owbyarnWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
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
			var tPoQty=0;
			var tPoAmount=0;
			var tBalPoQty=0;
			var tBalPoAmount=0;
			for(var i=0; i<data.rows.length; i++){
				if(data.rows[i]['yarn_des'] !=='Sub Total')
				{
	                tCons+=data.rows[i]['yarn_qty'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['yarn_amount'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tBalPoQty+=data.rows[i]['bal_po_qty'].replace(/,/g,'')*1;
					tBalPoAmount+=data.rows[i]['bal_po_amount'].replace(/,/g,'')*1;
				}
				
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					yarn_qty: tCons.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_amount: tAmout.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_qty: tPoQty.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: tPoAmount.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bal_po_qty: tBalPoQty.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bal_po_amount: tBalPoAmount.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
	}

	trimamountvar(value,row,index)
	{
		if (row.amount_var*1 < 0 ){
		    return 'color:red;';
		}
	}
	trimratevar(value,row,index)
	{
		if (row.rate_var*1 < 0 ){
		    return 'color:red;';
		}
	}


	detailsTrimWindow(id,month_from,month_to,company_id){
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/gettrim",{params});
		let g=data.then(function (response) {
			if(id)
			{
				$('#OrdbudgetReporttrimTbl').datagrid('loadData', response.data);
				$('#owbtrimWindow').window('open');	
			}
			else
			{
				$('#OrdbudgetReporttrimTbl2').datagrid('loadData', response.data);
				$('#owbtrimWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});

		
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
			var tQty=0;
			var tAmout=0;
			var tRate=0;
			var po_qty=0;
			var po_rate=0;
			var po_amount=0;
			var amount_var=0;
			var lc_amount=0;
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
				po_qty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
				po_amount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
				amount_var+=data.rows[i]['amount_var'].replace(/,/g,'')*1;
				lc_amount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
			tRate=(tAmout/tQty);
			}
			if(po_qty){
			po_rate=(po_amount/po_qty);
			}
			$(this).datagrid('reloadFooter', [
				{ 
					bom_trim: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_qty: po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: po_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: po_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount_var: amount_var.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_amount: lc_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
			var tQty=0;
			var tAmout=0;
			var tRate=0;
			var po_qty=0;
			var po_rate=0;
			var po_amount=0;
			var amount_var=0;
			var lc_amount=0;
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
				po_qty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
				po_amount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
				amount_var+=data.rows[i]['amount_var'].replace(/,/g,'')*1;
				lc_amount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
				tRate=(tAmout/tQty);
			}
			if(po_qty){
				po_rate=(po_amount/po_qty);
			}
			$(this).datagrid('reloadFooter', [
				{ 
					bom_trim: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_qty: po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: po_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: po_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount_var: amount_var.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_amount: lc_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	detailsFabPurWindow(id,month_from,month_to,company_id){
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getfabpur",{params});
		let g=data.then(function (response) {
			if(id)
			{
				$('#OrdbudgetReportfabpurTbl').datagrid('loadData', response.data);
				$('#owbfabpurWindow').window('open');	
			}
			else
			{
				$('#OrdbudgetReportfabpurTbl2').datagrid('loadData', response.data);
				$('#owbfabpurWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});

		
	}

	showGridFabPur(data)
	{
		var dg = $('#OrdbudgetReportfabpurTbl');
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
			var tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['grey_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['fab_pur_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
			tRate=(tAmout/tQty);
			}
			
			$(this).datagrid('reloadFooter', [
				{ 
					grey_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fab_pur_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridFabPur2(data)
	{
		var dg = $('#OrdbudgetReportfabpurTbl2');
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
			var tRate=0;
			
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['grey_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['fab_pur_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
				tRate=(tAmout/tQty);
			}
			
			$(this).datagrid('reloadFooter', [
				{ 
					grey_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fab_pur_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	detailsKnitWindow(id,month_from,month_to)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getknit",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportknitTbl').datagrid('loadData', response.data);
				$('#owbknitWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportknitTbl2').datagrid('loadData', response.data);	
			    $('#owbknitWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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


	detailsYarnDyeingWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getyarndyeing",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportyarndyeingTbl').datagrid('loadData', response.data);
				$('#owbyarndyeingWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportyarndyeingTbl2').datagrid('loadData', response.data);	
			    $('#owbyarndyeingWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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


	detailsDyeingWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getdyeing",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportdyeingTbl').datagrid('loadData', response.data);
				$('#owbdyeingWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportdyeingTbl2').datagrid('loadData', response.data);	
			    $('#owbdyeingWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		nowrap:false,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			var tOhAmout=0;
			var ttAmout=0;
			tDyeCharge=0;
			tRate=0;
			
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
				//tDyeCharge+=data.rows[i]['dye_charge_per_kg'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			tDyeCharge=(ttAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dye_charge_per_kg: tDyeCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

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
			var tOhAmout=0;
			var ttAmout=0;
			tDyeCharge=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
			}
			tDyeCharge=(ttAmout/tCons);
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dye_charge_per_kg: tDyeCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	detailsAopWindow(id,month_from,month_to)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getaop",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportaopTbl').datagrid('loadData', response.data);
				$('#owbaopWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportaopTbl2').datagrid('loadData', response.data);	
			    $('#owbaopWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		nowrap:false,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			var tOhAmout=0;
			var ttAmout=0;
			tAopCharge=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
				//tAopCharge+=data.rows[i]['aop_charge_per_kg'].replace(/,/g,'')*1;
			}
			tAopCharge=(ttAmout/tCons);
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_charge_per_kg: tAopCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
			var tOhAmout=0;
			var ttAmout=0;
			tRate=0;
			tAopCharge=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			tAopCharge=(ttAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_charge_per_kg: tAopCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	detailsBocWindow(id,month_from,month_to)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getboc",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportbocTbl').datagrid('loadData', response.data);
				$('#owbbocWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportbocTbl2').datagrid('loadData', response.data);	
			    $('#owbbocWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	detailsFwcWindow(id,month_from,month_to)
	{

        let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getfwc",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportfwcTbl').datagrid('loadData', response.data);
				$('#owbfwcWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportfwcTbl2').datagrid('loadData', response.data);	
			    $('#owbfwcWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	detailsGpcWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getgpc",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportgpcTbl').datagrid('loadData', response.data);
				$('#owbgpcWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportgpcTbl2').datagrid('loadData', response.data);	
			    $('#owbgpcWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		nowrap:false,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tCons=0;
			var tAmout=0;
			var tOhAmout=0;
			var ttAmout=0;
			tScreenPrintCharge=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
				//tScreenPrintCharge+=data.rows[i]['screen_print_chargey'].replace(/,/g,'')*1;
			}
			tScreenPrintCharge=(ttAmout/tCons)*12;
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					screen_print_charge: tScreenPrintCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
			var tOhAmout=0;
			var ttAmout=0;
			tRate=0;
			tScreenPrintCharge=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			tScreenPrintCharge=(ttAmout/tCons)*12;
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overhead_amount: tOhAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_amount: ttAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					screen_print_charge: tScreenPrintCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	detailsGecWindow(id,month_from,month_to)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getgec",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportgecTbl').datagrid('loadData', response.data);
				$('#owbgecWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportgecTbl2').datagrid('loadData', response.data);	
			    $('#owbgecWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}




	detailsGsecWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getgsec",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportgsecTbl').datagrid('loadData', response.data);
				$('#owbgsecWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportgsecTbl2').datagrid('loadData', response.data);	
			    $('#owbgsecWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	detailsGdcWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getgdc",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportgdcTbl').datagrid('loadData', response.data);
				$('#owbgdcWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportgdcTbl2').datagrid('loadData', response.data);	
			    $('#owbgdcWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	detailsGwcWindow(id,month_from,month_to)
	{

		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getgwc",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportgwcTbl').datagrid('loadData', response.data);
				$('#owbgwcWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportgwcTbl2').datagrid('loadData', response.data);	
			    $('#owbgwcWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	detailsOthWindow(id,month_from,month_to,company_id)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getoth",{params});
		data.then(function (response) {
			if(id)
		    {
				$('#OrdbudgetReportothTbl').datagrid('loadData', response.data);
				$('#owbothWindow').window('open');	
		    }
		    else
		    {
			    $('#OrdbudgetReportothTbl2').datagrid('loadData', response.data);	
			    $('#owbothWindow2').window('open');
		    }
		})
		.catch(function (error) {
			console.log(error);
		});
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	detailsSaleOrderWindow(id,month_from,month_to,company_id)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getsalesorder",{params});
		data.then(function (response) {
			$('#owbscs').html(response.data);
			$('#owbsalesorderWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	fileWindow(style_id){	
		/* let params=this.getParams();
		params.style_id=style_id;	 */
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getfile?style_id="+style_id);
		data.then(function (response) {
		    $('#orderinhandfilesrcwindow').window('open');
			$('#orderinhandfilesrcTbl').datagrid('loadData', response.data);
				    
		})
		.catch(function (error) {
			console.log(error);
		});		
	}
	showGridFileSrc(data)
	{
		$('#orderinhandfilesrcTbl').datagrid({
			border:false,
			singleSelect:true,
			showFooter:false,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
	
		});			
	}
	formatfiles(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderInHand.fileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	detailsConsDznWindow(id,month_from,month_to,company_id)
	{
		let params=this.getParams();
		params.month_from=month_from;
		params.month_to=month_to;
		params.sale_order_id=id;
		params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getconsdzn",{params});
		data.then(function (response) {
			if(id)
			{
				$('#OrdbudgetReportconsdznTbl').datagrid('loadData', response.data);
				$('#owbconsdznWindow').window('open');	
			}
			else
			{
				$('#OrdbudgetReportconsdznTbl2').datagrid('loadData', response.data);
				$('#owbconsdznWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridConsDzn(data)
	{
		var dg = $('#OrdbudgetReportconsdznTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tfinfab=0;
			var tgreyfab=0;
			var tcons=0;
			var treqcons=0;
			for(var i=0; i<data.rows.length; i++){
				
					tfinfab+=data.rows[i]['fin_fab'].replace(/,/g,'')*1;
					tgreyfab+=data.rows[i]['grey_fab'].replace(/,/g,'')*1;
					tcons+=data.rows[i]['cons'].replace(/,/g,'')*1;
					treqcons+=data.rows[i]['req_cons'].replace(/,/g,'')*1;
				
			}
			//tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					fin_fab: tfinfab.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_fab: tgreyfab.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cons: tcons.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					req_cons: treqcons.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatlcsc(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderInHand.lcscWindow('+row.id+')">'+row.lc_sc_no+'</a>';	
	}

	lcscWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/orderinhand/getlcsc?sale_order_id="+sale_order_id);
		data.then(function (response) {
			$('#owblcscTbl').datagrid('loadData', response.data);
			$('#owblcscwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridLcSc(data)
	{
		var dg = $('#owblcscTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				/*var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['out_yarn_isu_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					out_yarn_isu_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				])*/;
			}
		});
		dg.datagrid('loadData', data);
	}

}
window.MsOrderInHand=new MsOrderInHandController(new MsOrderInHandModel());
MsOrderInHand.showGrid({rows :{}});
MsOrderInHand.showGridYarn({rows :{}});
MsOrderInHand.showGridYarn2({rows :{}});
MsOrderInHand.showGridTrim({rows :{}});
MsOrderInHand.showGridTrim2({rows :{}});
MsOrderInHand.showGridFabPur({rows :{}});
MsOrderInHand.showGridFabPur2({rows :{}});
MsOrderInHand.showGridKnit({rows :{}});
MsOrderInHand.showGridKnit2({rows :{}});
MsOrderInHand.showGridYarnDyeing({rows :{}});
MsOrderInHand.showGridYarnDyeing2({rows :{}});
MsOrderInHand.showGridDyeing({rows :{}});
MsOrderInHand.showGridDyeing2({rows :{}});
MsOrderInHand.showGridAop({rows :{}});
MsOrderInHand.showGridAop2({rows :{}});

MsOrderInHand.showGridBoc({rows :{}});
MsOrderInHand.showGridBoc2({rows :{}});

MsOrderInHand.showGridFwc({rows :{}});
MsOrderInHand.showGridFwc2({rows :{}});
MsOrderInHand.showGridGpc({rows :{}});
MsOrderInHand.showGridGpc2({rows :{}});

MsOrderInHand.showGridGec({rows :{}});
MsOrderInHand.showGridGec2({rows :{}});

MsOrderInHand.showGridGsec({rows :{}});
MsOrderInHand.showGridGsec2({rows :{}});

MsOrderInHand.showGridGdc({rows :{}});
MsOrderInHand.showGridGdc2({rows :{}});

MsOrderInHand.showGridGwc({rows :{}});
MsOrderInHand.showGridGwc2({rows :{}});

MsOrderInHand.showGridOth({rows :{}});
MsOrderInHand.showGridOth2({rows :{}});
MsOrderInHand.showGridFileSrc({rows :{}});
MsOrderInHand.showGridConsDzn({rows :{}});
MsOrderInHand.showGridLcSc([]);
