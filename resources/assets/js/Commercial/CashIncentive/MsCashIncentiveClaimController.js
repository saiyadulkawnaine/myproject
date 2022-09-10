let MsCashIncentiveClaimModel = require('./MsCashIncentiveClaimModel');
class MsCashIncentiveClaimController {
	constructor(MsCashIncentiveClaimModel)
	{
		this.MsCashIncentiveClaimModel = MsCashIncentiveClaimModel;
		this.formId='cashincentiveclaimFrm';
		this.dataTable='#cashincentiveclaimTbl';
		this.route=msApp.baseUrl()+"/cashincentiveclaim"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsCashIncentiveClaimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveClaimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cashincentiveclaimFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveClaimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveClaimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiveclaimTbl').datagrid('reload');
		msApp.resetForm('cashincentiveclaimFrm');
		$('#cashincentiveclaimFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveClaimModel.get(index,row);
	}

	showGrid(cash_incentive_ref_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_ref_id=cash_incentive_ref_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			showFooter:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var invoiceQty=0;
				var invoiceAmount=0;
				var netWgtExport=0;
				var realizedAmount=0;
				var costOfExport=0;
				var freight=0;
				var netRealizedValue=0;
				var claimAmount=0;
				var localCurrency=0;
				var totalNetWgtQty=0;
				var knitCharge=0;
				var dyeCharge=0;
				for(var i=0; i<data.rows.length; i++){
					invoiceQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoiceAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					netWgtExport+=data.rows[i]['net_wgt_exp_qty'].replace(/,/g,'')*1;
					realizedAmount+=data.rows[i]['realized_amount'].replace(/,/g,'')*1;
					costOfExport+=data.rows[i]['cost_of_export'].replace(/,/g,'')*1;
					freight+=data.rows[i]['freight'].replace(/,/g,'')*1;
					netRealizedValue+=data.rows[i]['net_realized_amount'].replace(/,/g,'')*1;
					claimAmount+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
					localCurrency+=data.rows[i]['local_cur_amount'].replace(/,/g,'')*1;
					totalNetWgtQty+=data.rows[i]['total_net_wgt_qty'].replace(/,/g,'')*1;
					knitCharge+=data.rows[i]['knit_charge'].replace(/,/g,'')*1;
					dyeCharge+=data.rows[i]['dye_charge'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					invoice_qty: invoiceQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: invoiceAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_wgt_exp_qty: netWgtExport.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					realized_amount: realizedAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cost_of_export: costOfExport.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					freight: freight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_realized_amount: netRealizedValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					claim_amount: claimAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					local_cur_amount: localCurrency.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_net_wgt_qty: totalNetWgtQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_charge: knitCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dye_charge: dyeCharge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter');//.datagrid('loadData', data)
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveClaim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateInvoiceRate(){
		let self = this;
		let invoice_qty;
		let invoice_amount;
		
		invoice_qty=$('#cashincentiveclaimFrm [name=invoice_qty]').val();
		invoice_amount=$('#cashincentiveclaimFrm [name=invoice_amount]').val();
		//alert(invoice_qty*rate)
		let rate=invoice_amount/invoice_qty;
		$('#cashincentiveclaimFrm [name=rate]').val(rate);
	}

	calculateNetRealized(){
		let self = this;
		let realized_amount;
		let freight;

		realized_amount=$('#cashincentiveclaimFrm [name=realized_amount]').val();
		freight=$('#cashincentiveclaimFrm [name=freight]').val();

		let net_realized_amount=realized_amount-freight;
		$('#cashincentiveclaimFrm [name=net_realized_amount]').val(net_realized_amount);
		let cost_of_realization=net_realized_amount*(0.80);
		$('#cashincentiveclaimFrm [name=cost_of_realization]').val(cost_of_realization);
		// let cost_of_export=net_realized_amount*(0.80);
		// $('#cashincentiveclaimFrm [name=cost_of_export]').val(cost_of_export);
	}

	// calculateCostOfExport(){
	// 	let self = this;
	// 	let knitting_charge_per_kg;
	// 	let dyeing_charge_per_kg;
	// 	let net_wgt_exp_qty;
	// 	let avg_rate;

	// 	knitting_charge_per_kg=$('#cashincentiveclaimFrm [name=knitting_charge_per_kg]').val();
	// 	dyeing_charge_per_kg=$('#cashincentiveclaimFrm [name=dyeing_charge_per_kg]').val();
	// 	net_wgt_exp_qty=$('#cashincentiveclaimFrm [name=net_wgt_exp_qty]').val();
	// 	avg_rate=$('#cashincentiveclaimFrm [name=avg_rate]').val();

	// 	let consumed_amount=(net_wgt_exp_qty*avg_rate)*1;
	// 	let netknit=(net_wgt_exp_qty*knitting_charge_per_kg)*1;
	// 	let netDye=(net_wgt_exp_qty*dyeing_charge_per_kg)*1;

	// 	let cost_of_export=(consumed_amount+netknit+netDye)*1;
	// 	$('#cashincentiveclaimFrm [name=cost_of_export]').val(cost_of_export);
	// }
	
	calculateCostOfExport(){
		let self = this;
		let knitting_charge_per_kg;
		let dyeing_charge_per_kg;
		let net_wgt_exp_qty;
		let avg_rate;
		let process_loss_per;

		knitting_charge_per_kg=($('#cashincentiveclaimFrm [name=knitting_charge_per_kg]').val())*1;
		dyeing_charge_per_kg=($('#cashincentiveclaimFrm [name=dyeing_charge_per_kg]').val())*1;
		avg_rate=($('#cashincentiveclaimFrm [name=avg_rate]').val())*1;
		process_loss_per=($('#cashincentiveclaimFrm [name=process_loss_per]').val())*1;
		net_wgt_exp_qty=($('#cashincentiveclaimFrm [name=net_wgt_exp_qty]').val())*1;

		let process_loss=net_wgt_exp_qty*(process_loss_per/100);
		let yarn_cost_kg=(net_wgt_exp_qty+process_loss)*1;
		let consumed_amount=(yarn_cost_kg*avg_rate);
		let netknit=(yarn_cost_kg*knitting_charge_per_kg);
		let netDye=(yarn_cost_kg*dyeing_charge_per_kg)*1;
		//alert(consumed_amount);
		let cost_of_export=(consumed_amount+netknit+netDye)*1;
		$('#cashincentiveclaimFrm [name=cost_of_export]').val(cost_of_export);
	}

	calculateClaimAmount(){
		let self=this;
		let claim;
		let net_realized_amount;
		let cost_of_export;

		claim = $('#cashincentiveclaimFrm [name=claim]').val()*1;
		cost_of_export = $('#cashincentiveclaimFrm [name=cost_of_export]').val();
		//claim_par=(claim*1)/100;
		let claim_amount=cost_of_export*(claim/100);
		
		$('#cashincentiveclaimFrm [name=claim_amount]').val(claim_amount);
	}

	calculateLocalCurrencyAmount(){
		let self=this;
		let claim_amount=$('#cashincentiveclaimFrm [name=claim_amount]').val();
		let exch_rate=$('#cashincentiveclaimFrm [name=exch_rate]').val();
		let local_cur_amount=claim_amount*exch_rate;
		$('#cashincentiveclaimFrm [name=local_cur_amount]').val(local_cur_amount);
	}
	
	openDocInvoiceWindow(){
		$('#opendocsubinvoicewindow').window('open');
	}

	getParams(){
		let params={}
		params.cashincentiverefid=$('#cashincentiverefFrm  [name=id]').val();
		params.bank_ref_bill_no=$('#docinvoicesearchFrm  [name=bank_ref_bill_no]').val();
		params.invoice_no=$('#docinvoicesearchFrm  [name=invoice_no]').val();
		//alert(cashincentiverefid)
		return params;
		
	}

	searchDocInvoice(){
		let params=this.getParams();
		let d = axios.get(this.route+'/getexpdocinvoice',{params})
		.then(function(response){
			$('#docinvoicesearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showDocInvoiceGrid(){
		let self = this;
		$('#docinvoicesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			rownumbers:true,
			fit:true,
			onClickRow: function(index,row){
				$('#cashincentiveclaimFrm [name=exp_doc_sub_invoice_id]').val(row.exp_doc_sub_invoice_id);
				$('#cashincentiveclaimFrm [name=invoice_no]').val(row.invoice_no);
				$('#cashincentiveclaimFrm [name=bank_bill_no]').val(row.bank_ref_bill_no);
				$('#cashincentiveclaimFrm [name=invoice_amount]').val(row.u_invoice_value);
				$('#cashincentiveclaimFrm [name=invoice_qty]').val(row.invoice_qty);
				$('#cashincentiveclaimFrm [name=rate]').val(row.rate);
				$('#cashincentiveclaimFrm [name=exp_form_no]').val(row.exp_form_no);
				$('#cashincentiveclaimFrm [name=exp_date]').val(row.exp_form_date);
				$('#cashincentiveclaimFrm [name=bl_date]').val(row.bl_cargo_date);
				$('#cashincentiveclaimFrm [name=realized_date]').val(row.realization_date);
				$('#cashincentiveclaimFrm [name=net_wgt_exp_qty]').val(row.net_wgt_exp_qty);
				$('#cashincentiveclaimFrm [name=avg_rate]').val(row.avg_rate);
				$('#cashincentiveclaimFrm [name=process_loss_per]').val(row.process_loss_per);
				$('#docinvoicesearchTbl').datagrid('loadData',[]);
				$('#opendocsubinvoicewindow').window('close');
			}
		}).datagrid('enableFilter');
	}


}
window.MsCashIncentiveClaim=new MsCashIncentiveClaimController(new MsCashIncentiveClaimModel());
MsCashIncentiveClaim.showDocInvoiceGrid([]);