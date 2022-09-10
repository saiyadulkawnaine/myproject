//require('./jquery.easyui.min.js');
let MsBudgetAndCostingComparisonModel = require('./MsBudgetAndCostingComparisonModel');
require('./datagrid-filter.js');

class MsBudgetAndCostingComparisonController {
	constructor(MsBudgetAndCostingComparisonModel)
	{
		this.MsBudgetAndCostingComparisonModel = MsBudgetAndCostingComparisonModel;
		this.formId='budgetandcostingcomparisonFrm';
		this.dataTable='#budgetandcostingcomparisonTbl';
		this.route=msApp.baseUrl()+"/budgetandcostingcomparison"
	}
	getParams()
	{
		let params={};
		params.company_id = $('#orderinhandFrm  [name=company_id]').val();
		params.buyer_id = $('#orderinhandFrm  [name=buyer_id]').val();
		params.style_ref = $('#orderinhandFrm  [name=style_ref]').val();
		params.job_no = $('#orderinhandFrm  [name=job_no]').val();
		params.date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		params.date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		params.order_status = $('#orderinhandFrm  [name=order_status]').val();
		params.year = $('#orderinhandFrm  [name=year]').val();
		return params;
	}
	
	get(){
		let params={};
		params.company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		params.style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		params.job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		params.date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		params.date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		params.order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#budgetandcostingcomparisonTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatTow(){
		let params={};
		params.company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		params.style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		params.job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		params.date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		params.date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		params.order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		
		let d= axios.get(this.route+'/formatTow',{params})
		.then(function (response) {
			$('#budgetandcostingcomparisonmatrix').html(response.data);
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

				var tMktYarnAmount=0;
				var tYarnVari=0;
				var tMktTrimAmount=0;
				var tTrimVari=0;
				var tMktYdAmount=0;
				var tYdVari=0;
				var tMktKnitAmount =0;
				var tKnitingVari=0;
				var tMktDyeAmount=0;
				var tDyeVari=0;
				var tMktAopAmount=0;
				var tAopVari=0;
				var tMktBurnAmount=0;
				var tBurnOutVari=0;
				var tMktFabWashAmount=0;
				var tWashVari=0;
				var tMktPrintAmount=0;
				var tPrintVari=0;
				var tMktEmbelAmount=0;
				var tEmbVari=0;
				var tMktSpembelAmount=0;
				var tSpembVari=0;
				var tMktGmtDyeAmount=0;
				var tGmtDyeVari=0;
				var tMktGmtWashAmount=0;
				var tGmtWashVari=0;
				var tMktOtherAmount=0;
				var tGmtOtherVari=0;
				var tMktFreiAmount=0;
				var tGmtFreiVari=0;
				var tMktCmAmount=0;
				var tGmtCmVari=0;
				var tMktCommiAmount=0;
				var tGmtCommiVari=0;
				var tMktCommerAmount=0;
				var tGmtCommerVari=0;
				var tMktTotalAmount=0;
				var tTotalAmountVari=0;
				var tMktTotalProfit=0;
				var tTotalProfitVari=0;
				
				

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
				tMktYarnAmount+=data.rows[i]['mkt_yarn_amount'].replace(/,/g,'')*1;
				tYarnVari+=data.rows[i]['yarn_vari'].replace(/,/g,'')*1;
				tMktTrimAmount+=data.rows[i]['mkt_trim_amount'].replace(/,/g,'')*1;
				tTrimVari+=data.rows[i]['trim_vari'].replace(/,/g,'')*1;
				tMktYdAmount+=data.rows[i]['mkt_yd_amount'].replace(/,/g,'')*1;
				tYdVari+=data.rows[i]['yd_vari'].replace(/,/g,'')*1;
				tMktKnitAmount+=data.rows[i]['mkt_knit_amount'].replace(/,/g,'')*1;
				tKnitingVari+=data.rows[i]['kniting_vari'].replace(/,/g,'')*1;
				tMktDyeAmount+=data.rows[i]['mkt_dye_amount'].replace(/,/g,'')*1;
				tDyeVari+=data.rows[i]['dye_vari'].replace(/,/g,'')*1;
				tMktAopAmount+=data.rows[i]['mkt_aop_amount'].replace(/,/g,'')*1;
				tAopVari+=data.rows[i]['aop_vari'].replace(/,/g,'')*1;
				tMktBurnAmount+=data.rows[i]['mkt_burn_amount'].replace(/,/g,'')*1;
				tBurnOutVari+=data.rows[i]['burn_out_vari'].replace(/,/g,'')*1;
				tMktFabWashAmount+=data.rows[i]['mkt_fab_wash_amount'].replace(/,/g,'')*1;
				tWashVari+=data.rows[i]['wash_vari'].replace(/,/g,'')*1;
				tMktPrintAmount+=data.rows[i]['mkt_print_amount'].replace(/,/g,'')*1;
				tPrintVari+=data.rows[i]['print_vari'].replace(/,/g,'')*1;
				tMktEmbelAmount+=data.rows[i]['mkt_embel_amount'].replace(/,/g,'')*1;
				tEmbVari+=data.rows[i]['emb_vari'].replace(/,/g,'')*1;
				tMktSpembelAmount+=data.rows[i]['mkt_spembel_amount'].replace(/,/g,'')*1;
				tSpembVari+=data.rows[i]['spemb_vari'].replace(/,/g,'')*1;
				tMktGmtDyeAmount+=data.rows[i]['mkt_gmt_dye_amount'].replace(/,/g,'')*1;
				tGmtDyeVari+=data.rows[i]['gmt_dye_vari'].replace(/,/g,'')*1;
				tMktGmtWashAmount+=data.rows[i]['mkt_gmt_wash_amount'].replace(/,/g,'')*1;
				tGmtWashVari+=data.rows[i]['gmt_wash_vari'].replace(/,/g,'')*1;
				tMktOtherAmount+=data.rows[i]['mkt_other_amount'].replace(/,/g,'')*1;
				tGmtOtherVari+=data.rows[i]['gmt_other_vari'].replace(/,/g,'')*1;
				tMktFreiAmount+=data.rows[i]['mkt_frei_amount'].replace(/,/g,'')*1;
				tGmtFreiVari+=data.rows[i]['gmt_frei_vari'].replace(/,/g,'')*1;
				tMktCmAmount+=data.rows[i]['mkt_cm_amount'].replace(/,/g,'')*1;
				tGmtCmVari+=data.rows[i]['gmt_cm_vari'].replace(/,/g,'')*1;
				tMktCommiAmount+=data.rows[i]['mkt_commi_amount'].replace(/,/g,'')*1;
				tGmtCommiVari+=data.rows[i]['gmt_commi_vari'].replace(/,/g,'')*1;
				tMktCommerAmount+=data.rows[i]['mkt_commer_amount'].replace(/,/g,'')*1;
				tGmtCommerVari+=data.rows[i]['gmt_commer_vari'].replace(/,/g,'')*1;
				tMktTotalAmount+=data.rows[i]['mkt_total_amount'].replace(/,/g,'')*1;
				tTotalAmountVari+=data.rows[i]['total_amount_vari'].replace(/,/g,'')*1;
				tMktTotalProfit+=data.rows[i]['mkt_total_profit'].replace(/,/g,'')*1;
				tTotalProfitVari+=data.rows[i]['total_profit_vari'].replace(/,/g,'')*1;
				
				}
				var tRate=0;
				tProfitPer=(tProfit/tAmout)*100;
				tRate=(tAmout/tQty);
				tMktTotalProfitPer=(tMktTotalProfit/tAmout)*100;
				tTotalProfitPerVari=(tTotalProfitVari/tAmout)*100;
				
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
						mkt_yarn_amount: tMktYarnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yarn_vari: tYarnVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_trim_amount: tMktTrimAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						trim_vari: tTrimVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_yd_amount: tMktYdAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yd_vari: tYdVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_knit_amount: tMktKnitAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						kniting_vari: tKnitingVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_dye_amount: tMktDyeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						dye_vari: tDyeVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_aop_amount: tMktAopAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						aop_vari: tAopVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_burn_amount: tMktBurnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						burn_out_vari: tBurnOutVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_fab_wash_amount: tMktFabWashAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						wash_vari: tWashVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_print_amount: tMktPrintAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						print_vari: tPrintVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_embel_amount: tMktEmbelAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						emb_vari: tEmbVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_spembel_amount: tMktSpembelAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						spemb_vari: tSpembVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_gmt_dye_amount: tMktGmtDyeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_dye_vari: tGmtDyeVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_gmt_wash_amount: tMktGmtWashAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_wash_vari: tGmtWashVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_other_amount: tMktOtherAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_other_vari: tGmtOtherVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_frei_amount: tMktFreiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_frei_vari: tGmtFreiVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_cm_amount: tMktCmAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_cm_vari: tGmtCmVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_commi_amount: tMktCommiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_commi_vari: tGmtCommiVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_commer_amount: tMktCommerAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						gmt_commer_vari: tGmtCommerVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_total_amount: tMktTotalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_amount_vari: tTotalAmountVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_total_profit: tMktTotalProfit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_profit_vari: tTotalProfitVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						mkt_total_profit_per: tMktTotalProfitPer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_profit_per_vari: tTotalProfitPerVari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
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
	imageWindow(flie_src){
		var output = document.getElementById('orderWiseBudgetImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+flie_src;
    	            output.src =  fp;
			$('#orderWiseBudgetImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsBudgetAndCostingComparison.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	formatSaleOrder(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsSaleOrderWindow('+row.id+')">'+row.qty+'</a>';
	}
	formatfabpur(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsFabPurWindow('+row.id+')">'+row.fab_pur_amount+'</a>';
	}

	formatYarn(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsYarnWindow('+row.id+')">'+row.yarn_amount+'</a>';
	}
	formatTrim(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsTrimWindow('+row.id+')">'+row.trim_amount+'</a>';
	}
	formatKnit(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsKnitWindow('+row.id+')">'+row.kniting_amount+'</a>';
	}
	formatYarnDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsYarnDyeingWindow('+row.id+')">'+row.yarn_dying_amount+'</a>';
	}
	formatWeaving(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.weaving_amount+'</a>';
	}
	formatDyeing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsDyeingWindow('+row.id+')">'+row.dying_amount+'</a>';
	}
	formatAop(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsAopWindow('+row.id+')">'+row.aop_amount+'</a>';
	}

	formatBurnOut(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsBocWindow('+row.id+')">'+row.burn_out_amount+'</a>';
	}
	formatFinishing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.finishing_amount+'</a>';
	}
	formatWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsFwcWindow('+row.id+')">'+row.washing_amount+'</a>';
	}

	formatPrinting(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsGpcWindow('+row.id+')">'+row.printing_amount+'</a>';
	}

	formatEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsGecWindow('+row.id+')">'+row.emb_amount+'</a>';
	}

	formatSpEmb(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsGsecWindow('+row.id+')">'+row.spemb_amount+'</a>';
	}

	formatGmtDying(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsGdcWindow('+row.id+')">'+row.gmt_dyeing_amount+'</a>';
	}

	formatGmtWashing(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsGwcWindow('+row.id+')">'+row.gmt_washing_amount+'</a>';
	}

	formatCourier(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsOthWindow('+row.id+')">'+row.courier_amount+'</a>';
	}

	formatFreight(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.freight_amount+'</a>';
	}

	formatCm(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.cm_amount+'</a>';
	}

	formatCommi(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.commi_amount+'</a>';
	}

	formatCommer(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.detailsWindow('+row.id+')">'+row.commer_amount+'</a>';
	}

	detailsYarnWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getyarn?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bncReportyarnTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacyarnWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getyarn?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bncReportyarnTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/gettrim?sale_order_id="+id);
			let g=data.then(function (response) {
				$('#bacReporttrimTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
			});
			$('#bactrimWindow').window('open');
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
			let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
			let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
			let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
			let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
			let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
			let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
			let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/gettrim?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReporttrimTbl');
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
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
			tRate=(tAmout/tQty);
			}
			$(this).datagrid('reloadFooter', [
				{ 
					bom_trim: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['bom_trim'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['trim_amount'].replace(/,/g,'')*1;
			}
			if(tQty){
			tRate=(tAmout/tQty);
			}
			$(this).datagrid('reloadFooter', [
				{ 
					bom_trim: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trim_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	detailsFabPurWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getfabpur?sale_order_id="+id);
			let g=data.then(function (response) {
				$('#bacReportfabpurTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacfabpurWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getknit?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#OrdbudgetReportfabpurTbl2').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl2').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbfabpurWindow2').window('open');
		}
	}

	showGridFabPur(data)
	{
		var dg = $('#bacReportfabpurTbl');
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
				tCons+=data.rows[i]['grey_fab_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['fab_pur_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					grey_fab_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fab_pur_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}
		});
		dg.datagrid('loadData', data);
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
			var tCons=0;
			var tAmout=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['grey_fab_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['fab_pur_amount'].replace(/,/g,'')*1;
			}
			tRate=(tAmout/tCons);
			$(this).datagrid('reloadFooter', [
				{ 
					grey_fab_qty: tCons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fab_pur_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getknit?sale_order_id="+id);
			let g=data.then(function (response) {
				$('#bacReportknitTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#backnitWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getknit?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportknitTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getyarndyeing?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportyarndyeingTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacyarndyeingWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getyarndyeing?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportyarndyeingTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getdyeing?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportdyeingTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacdyeingWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getdyeing?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#bacReportdyeingTbl2').datagrid('loadData', response.data);
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
		var dg = $('#bacReportdyeingTbl');
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
		var dg = $('#bacReportdyeingTbl2');
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


	detailsAopWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getaop?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportaopTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacaopWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getaop?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#bacReportaopTbl2').datagrid('loadData', response.data);
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
		var dg = $('#bacReportaopTbl');
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
		dg.datagrid('loadData', data);
	}

	showGridAop2(data)
	{
		var dg = $('#bacReportaopTbl2');
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
		dg.datagrid('loadData', data);
	}


	detailsBocWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getboc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportbocTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacbocWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getboc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportbocTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getfwc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportfwcTbl').datagrid('loadData', response.data);
			/*for(var key in response.data){
				//msApp.setHtml(key,response.data.dropDown[key]);
				$('#OrdbudgetReportyarnTbl').datagrid('loadData', response.data);
			}*/
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacfwcWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getfwc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportfwcTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgpc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportgpcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacgpcWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgpc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#bacReportgpcTbl2').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbgpcWindow2').window('open');
		}
	}

	showGridGpc(data)
	{
		var dg = $('#bacReportgpcTbl');
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
			tScreenPrintCharge=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
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
		dg.datagrid('loadData', data);
	}

	showGridGpc2(data)
	{
		var dg = $('#bacReportgpcTbl2');
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
			tScreenPrintCharge=0;
			tRate=0;
			for(var i=0; i<data.rows.length; i++){
				tCons+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tOhAmout+=data.rows[i]['overhead_amount'].replace(/,/g,'')*1;
				ttAmout+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
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
		dg.datagrid('loadData', data);
	}



	detailsGecWindow(id)
	{

		if(id)
		{
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgec?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportgecTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacgecWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgec?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportgecTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgsec?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportgsecTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacgsecWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgsec?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportgsecTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgdc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportgdcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacgdcWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgdc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportgdcTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgwc?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportgwcTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacgwcWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getgwc?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportgwcTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getoth?sale_order_id="+id);
			let g=data.then(function (response) {
			$('#bacReportothTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#bacothWindow').window('open');	
		}
		else
		{
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getoth?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
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
		var dg = $('#bacReportothTbl');
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
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getsalesorder?sale_order_id="+id);
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
			let company_id = $('#budgetandcostingcomparisonFrm  [name=company_id]').val();
		    let buyer_id = $('#budgetandcostingcomparisonFrm  [name=buyer_id]').val();
		    let style_ref = $('#budgetandcostingcomparisonFrm  [name=style_ref]').val();
		    let job_no = $('#budgetandcostingcomparisonFrm  [name=job_no]').val();
		    let date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		    let date_to = $('#budgetandcostingcomparisonFrm  [name=date_to]').val();
		    let order_status = $('#budgetandcostingcomparisonFrm  [name=order_status]').val();
		    let id=0;
			let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getsalesorder?sale_order_id="+id+'&company_id='+company_id+'&buyer_id='+buyer_id+'&style_ref='+style_ref+'&job_no='+job_no+'&date_from='+date_from+'&date_to='+date_to+'&order_status='+order_status);
			let g=data.then(function (response) {
				$('#owbscs').html(response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	        $('#owbsalesorderWindow').window('open');
		}
	}

	
	teamleaderWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}	
	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}	
	showGridDlmct(data)
	{
		var dg = $('#dealmctinfoTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}
	buyingAgentWindow(buying_agent_id){
		
		let agent= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getbuyhouse?buyer_id="+buying_agent_id);
		agent.then(function (response) {
			$('#buyagentTbl').datagrid('loadData', response.data);
			$('#buyagentwindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBAgent(data)
	{
		var dg = $('#buyagentTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:false,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}
	//files upload open window
	
	/*fileWindow(style_id){
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getfile?style_id="+style_id);
		data.then(function (response) {
			$('#filesrcTbl').datagrid('loadData', response.data);
			$('#filesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridFileSrc(data)
	{
		var dg = $('#filesrcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}

	formatfiles(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.fileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}

	formatShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}*/
	bacfileWindow(style_id){
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getbacfile?style_id="+style_id);
		data.then(function (response) {
			$('#bacfilesrcTbl').datagrid('loadData', response.data);
			$('#bacfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBacFileSrc(data)
	{
		var dg = $('#bacfilesrcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}
	formatBacFiles(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.bacfileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatShowBacFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}
	formatteamleader(value,row){
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.teamleaderWindow('+row.teamleader_id+')">'+row.team_name+'</a>';
	}
	formatdlmerchant(value,row){
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.dlmerchantWindow('+row.user_id+')">'+row.team_member+'</a>';
	}
	formatbuyingAgent(value,row){
		//return '<a href="javascript:void(0)" onClick="MsSampleRequirement.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
	}

	detailsConsDznWindow(id)
	{
		let params=this.getParams();
		//params.month_from=month_from;
		//params.month_to=month_to;
		params.sale_order_id=id;
		//params.company_id=company_id;
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getconsdzn",{params});
		data.then(function (response) {
			if(id)
			{
				$('#mnccReportconsdznTbl').datagrid('loadData', response.data);
				$('#mnccconsdznWindow').window('open');	
			}
			else
			{
				$('#mnccReportconsdznTbl2').datagrid('loadData', response.data);
				$('#mnccconsdznWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridConsDzn(data)
	{
		var dg = $('#mnccReportconsdznTbl');
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
		dg.datagrid('loadData', data);
	}

	formatOverheadRate(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.bepWindow('+'\''+row.ship_date+'\''+')">'+row.overhead_rate+'</a>';
	}/*  */
	bepWindow (ship_date)
	{
		let params={};
		params.company_id=5;
		//params.date_from = $('#budgetandcostingcomparisonFrm  [name=date_from]').val();
		params.date_to = ship_date;
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getbep",{params});
		data.then(function (response) {
			$('#bacbepmatrix').html(response.data);
			$('#bacbepWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	bepGpcWindow (ship_date)
	{
		let params={};
		params.company_id=41;
		params.date_to = ship_date;
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getbepgpc",{params});
		data.then(function (response) {
			$('#bacbepgpcmatrix').html(response.data);
			$('#bacbepgpcWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	formatOverheadRateGpc(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.bepGpcWindow('+'\''+row.ship_date+'\''+')">'+row.overhead_rate+'</a>';
	}
	bepAopWindow (ship_date)
	{
		let params={};
		params.company_id=6;
		params.date_to = ship_date;
		let data= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/getbepaop",{params});
		data.then(function (response) {
			$('#bacbepmatrix').html(response.data);
			$('#bacbepWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	formatOverheadRateAop(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsBudgetAndCostingComparison.bepAopWindow('+'\''+row.ship_date+'\''+')">'+row.overhead_rate+'</a>';
	}

}
window.MsBudgetAndCostingComparison=new MsBudgetAndCostingComparisonController(new MsBudgetAndCostingComparisonModel());
MsBudgetAndCostingComparison.showGrid({rows :{}});
MsBudgetAndCostingComparison.showGridYarn({rows :{}});
MsBudgetAndCostingComparison.showGridYarn2({rows :{}});
MsBudgetAndCostingComparison.showGridFabPur({rows :{}});
MsBudgetAndCostingComparison.showGridFabPur2({rows :{}});
MsBudgetAndCostingComparison.showGridTrim({rows :{}});
MsBudgetAndCostingComparison.showGridTrim2({rows :{}});
MsBudgetAndCostingComparison.showGridKnit({rows :{}});
MsBudgetAndCostingComparison.showGridKnit2({rows :{}});
MsBudgetAndCostingComparison.showGridYarnDyeing({rows :{}});
MsBudgetAndCostingComparison.showGridYarnDyeing2({rows :{}});
MsBudgetAndCostingComparison.showGridDyeing({rows :{}});
MsBudgetAndCostingComparison.showGridDyeing2({rows :{}});
MsBudgetAndCostingComparison.showGridAop({rows :{}});
MsBudgetAndCostingComparison.showGridAop2({rows :{}});

MsBudgetAndCostingComparison.showGridBoc({rows :{}});
MsBudgetAndCostingComparison.showGridBoc2({rows :{}});

MsBudgetAndCostingComparison.showGridFwc({rows :{}});
MsBudgetAndCostingComparison.showGridFwc2({rows :{}});
MsBudgetAndCostingComparison.showGridGpc({rows :{}});
MsBudgetAndCostingComparison.showGridGpc2({rows :{}});

MsBudgetAndCostingComparison.showGridGec({rows :{}});
MsBudgetAndCostingComparison.showGridGec2({rows :{}});

MsBudgetAndCostingComparison.showGridGsec({rows :{}});
MsBudgetAndCostingComparison.showGridGsec2({rows :{}});

MsBudgetAndCostingComparison.showGridGdc({rows :{}});
MsBudgetAndCostingComparison.showGridGdc2({rows :{}});

MsBudgetAndCostingComparison.showGridGwc({rows :{}});
MsBudgetAndCostingComparison.showGridGwc2({rows :{}});

MsBudgetAndCostingComparison.showGridOth({rows :{}});
MsBudgetAndCostingComparison.showGridOth2({rows :{}});
MsBudgetAndCostingComparison.showGridDlmct({rows :{}});
MsBudgetAndCostingComparison.showGridBAgent({rows :{}});
MsBudgetAndCostingComparison.showGridBacFileSrc({rows :{}});
MsBudgetAndCostingComparison.showGridConsDzn({rows :{}});