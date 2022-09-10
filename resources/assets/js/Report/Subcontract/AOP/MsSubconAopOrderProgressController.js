require('./../../../datagrid-filter.js');
let MsSubconAopOrderProgressModel = require('./MsSubconAopOrderProgressModel');

class MsSubconAopOrderProgressController {
	constructor(MsSubconAopOrderProgressModel)
	{
		this.MsSubconAopOrderProgressModel = MsSubconAopOrderProgressModel;
		this.formId='subconaoporderprogressFrm';
		this.dataTable='#subconaoporderprogressTbl';
		this.route=msApp.baseUrl()+"/subconaoporderprogress";
	}
	
	get(){
		let params={};
		params.company_id = $('#subconaoporderprogressFrm  [name=company_id]').val();
		params.buyer_id = $('#subconaoporderprogressFrm  [name=buyer_id]').val();
		params.teammember_id = $('#subconaoporderprogressFrm  [name=teammember_id]').val();
		params.sales_order_no = $('#subconaoporderprogressFrm  [name=sales_order_no]').val();
		params.rcv_date_from = $('#subconaoporderprogressFrm  [name=rcv_date_from]').val();
		params.rcv_date_to = $('#subconaoporderprogressFrm  [name=rcv_date_to]').val();
		params.dlv_date_from = $('#subconaoporderprogressFrm  [name=dlv_date_from]').val();
		params.dlv_date_to = $('#subconaoporderprogressFrm  [name=dlv_date_to]').val();

		let d= axios.get(this.route+"/getdata",{params})
		.then(function (response) {
			$('#subconaoporderprogressTbl').datagrid('loadData', response.data);
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
				var qty=0;
				var amount=0;
				var pi_amount=0;
				var grey_rcv_qty=0;
				var grey_isu_qty=0;
				var batch_qty=0;
				var batch_wip=0;
				var batch_bal=0;
				var aop_qty=0;
				var aop_wip=0;
				var aop_bal=0;
				var fin_qty=0;
				var fin_wip=0;
				var fin_bal=0;
				var dlv_qty=0;
				var grey_used=0;
				var dlv_wip=0;
				var dlv_bal=0;
				var bill_value=0;
				var bill_value_bal=0;
				var ci_qty=0;
				var ci_qty_wip=0;
				var ci_qty_bal=0;
				var ci_amount=0;
				var ci_amount_wip=0;
				var ci_amount_bal=0;
				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					pi_amount+=data.rows[i]['pi_amount'].replace(/,/g,'')*1;
					grey_rcv_qty+=data.rows[i]['grey_rcv_qty'].replace(/,/g,'')*1;
					grey_isu_qty+=data.rows[i]['grey_isu_qty'].replace(/,/g,'')*1;
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
					batch_wip+=data.rows[i]['batch_wip'].replace(/,/g,'')*1;
					batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
					aop_qty+=data.rows[i]['aop_qty'].replace(/,/g,'')*1;
					aop_wip+=data.rows[i]['aop_wip'].replace(/,/g,'')*1;
					aop_bal+=data.rows[i]['aop_bal'].replace(/,/g,'')*1;
					fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
					fin_wip+=data.rows[i]['fin_wip'].replace(/,/g,'')*1;
					fin_bal+=data.rows[i]['fin_bal'].replace(/,/g,'')*1;
					dlv_qty+=data.rows[i]['dlv_qty'].replace(/,/g,'')*1;
					grey_used+=data.rows[i]['grey_used'].replace(/,/g,'')*1;
					dlv_wip+=data.rows[i]['dlv_wip'].replace(/,/g,'')*1;
					dlv_bal+=data.rows[i]['dlv_bal'].replace(/,/g,'')*1;
					bill_value+=data.rows[i]['bill_value'].replace(/,/g,'')*1;
					bill_value_bal+=data.rows[i]['bill_value_bal'].replace(/,/g,'')*1;
					ci_qty+=data.rows[i]['ci_qty'].replace(/,/g,'')*1;
					ci_qty_wip+=data.rows[i]['ci_qty_wip'].replace(/,/g,'')*1;
					ci_qty_bal+=data.rows[i]['ci_qty_bal'].replace(/,/g,'')*1;
					ci_amount+=data.rows[i]['ci_amount'].replace(/,/g,'')*1;
					ci_amount_wip+=data.rows[i]['ci_amount_wip'].replace(/,/g,'')*1;
					ci_amount_bal+=data.rows[i]['ci_amount_bal'].replace(/,/g,'')*1;
				}
				
				if (qty) {
					rate=amount/qty;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pi_amount: pi_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_rcv_qty: grey_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_isu_qty: grey_isu_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_wip: batch_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_qty: aop_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_wip: aop_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_bal: aop_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_wip: fin_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_bal: fin_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_qty: dlv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_used: grey_used.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_wip: dlv_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_bal: dlv_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bill_value: bill_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bill_value_bal: bill_value_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty: ci_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_wip: ci_qty_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_bal: ci_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount: ci_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount_wip: ci_amount_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount_bal: ci_amount_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsSubconAopOrderProgress= new MsSubconAopOrderProgressController(new MsSubconAopOrderProgressModel());
MsSubconAopOrderProgress.showGrid([]);