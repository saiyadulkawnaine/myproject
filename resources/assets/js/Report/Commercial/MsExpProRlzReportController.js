let MsExpProRlzReportModel = require('./MsExpProRlzReportModel');
require('./../../datagrid-filter.js');

class MsExpProRlzReportController {
	constructor(MsExpProRlzReportModel)
	{
		this.MsExpProRlzReportModel = MsExpProRlzReportModel;
		this.formId='expprorlzreportFrm';
		this.dataTable='#expprorlzreportTbl';
		this.route=msApp.baseUrl()+"/expprorlzreport";
	}
	
	getParams()
	{
	    let params={};
	    params.date_from = $('#expprorlzreportFrm  [name=date_from]').val();
		params.date_to = $('#expprorlzreportFrm  [name=date_to]').val();
		params.bank_id = $('#expprorlzreportFrm  [name=bank_id]').val();
		params.beneficiary_id = $('#expprorlzreportFrm  [name=beneficiary_id]').val();
		params.buyer_id = $('#expprorlzreportFrm  [name=buyer_id]').val();
		params.file_no = $('#expprorlzreportFrm  [name=file_no]').val();
		return 	params;
	}
	
	get(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#expprorlzreportTbl').datagrid('loadData', response.data);
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var invoice_value=0;
				var deduction=0;
				var local_commission_invoice=0;
				var foreign_commission_invoice=0;
				var freight_invoice=0;
				var net_invoice_value=0;
				var fc_held_btb_lc=0;
				var erq_account=0;
				var packaing_credit=0;
				var cost_of_packaing_credit=0;
				var mda_normal=0;
				var inc_int_pur=0;
				var source_tax=0;
				var frg_bank_charge=0;
				var current_account=0;
				var ad_pu_amount=0;
				var btbm_built=0;
				var erq_cr=0;
				var pc_ad=0;
				var mdan_cr=0;
				var mdas_cr=0;
				var mdau_cr=0;
				var sct_deduct=0;
				var fbc_ad=0;
				var cda_cr=0;
				var fdr_cr=0;
				var commi_cr=0;
				var disc_cr=0;
				var sht_rlz=0;
				var discrip_cr=0;
				var exp_docp=0;
				var cntrl_fund=0;
				var oth_crg=0;
				var exch_vari=0;
				
				var total_cr=0;

				for(var i=0; i<data.rows.length; i++){
					invoice_value+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
					deduction+=data.rows[i]['deduction'].replace(/,/g,'')*1;
					local_commission_invoice+=data.rows[i]['local_commission_invoice'].replace(/,/g,'')*1;
					foreign_commission_invoice+=data.rows[i]['foreign_commission_invoice'].replace(/,/g,'')*1;
					freight_invoice+=data.rows[i]['freight_invoice'].replace(/,/g,'')*1;
					net_invoice_value+=data.rows[i]['net_invoice_value'].replace(/,/g,'')*1;
					fc_held_btb_lc+=data.rows[i]['fc_held_btb_lc'].replace(/,/g,'')*1;
					erq_account+=data.rows[i]['erq_account'].replace(/,/g,'')*1;
					packaing_credit+=data.rows[i]['packaing_credit'].replace(/,/g,'')*1;
					cost_of_packaing_credit+=data.rows[i]['cost_of_packaing_credit'].replace(/,/g,'')*1;
					mda_normal+=data.rows[i]['mda_normal'].replace(/,/g,'')*1;
					inc_int_pur+=data.rows[i]['inc_int_pur'].replace(/,/g,'')*1;
					source_tax+=data.rows[i]['source_tax'].replace(/,/g,'')*1;
					frg_bank_charge+=data.rows[i]['frg_bank_charge'].replace(/,/g,'')*1;
					current_account+=data.rows[i]['current_account'].replace(/,/g,'')*1;
					ad_pu_amount+=data.rows[i]['ad_pu_amount'].replace(/,/g,'')*1;
					btbm_built+=data.rows[i]['btbm_built'].replace(/,/g,'')*1;
					erq_cr+=data.rows[i]['erq_cr'].replace(/,/g,'')*1;
					pc_ad+=data.rows[i]['pc_ad'].replace(/,/g,'')*1;
					mdan_cr+=data.rows[i]['mdan_cr'].replace(/,/g,'')*1;
					mdas_cr+=data.rows[i]['mdas_cr'].replace(/,/g,'')*1;
					mdau_cr+=data.rows[i]['mdau_cr'].replace(/,/g,'')*1;
					sct_deduct+=data.rows[i]['sct_deduct'].replace(/,/g,'')*1;
					fbc_ad+=data.rows[i]['fbc_ad'].replace(/,/g,'')*1;
					cda_cr+=data.rows[i]['cda_cr'].replace(/,/g,'')*1;
					fdr_cr+=data.rows[i]['fdr_cr'].replace(/,/g,'')*1;
					commi_cr+=data.rows[i]['commi_cr'].replace(/,/g,'')*1;
					disc_cr+=data.rows[i]['disc_cr'].replace(/,/g,'')*1;
					sht_rlz+=data.rows[i]['sht_rlz'].replace(/,/g,'')*1;
					discrip_cr+=data.rows[i]['discrip_cr'].replace(/,/g,'')*1;
					exp_docp+=data.rows[i]['exp_docp'].replace(/,/g,'')*1;
					cntrl_fund+=data.rows[i]['cntrl_fund'].replace(/,/g,'')*1;
					oth_crg+=data.rows[i]['oth_crg'].replace(/,/g,'')*1;
					exch_vari+=data.rows[i]['exch_vari'].replace(/,/g,'')*1;
					total_cr+=data.rows[i]['total_cr'].replace(/,/g,'')*1;

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					invoice_value: invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					deduction: deduction.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					local_commission_invoice: local_commission_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	foreign_commission_invoice: foreign_commission_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	freight_invoice: freight_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	net_invoice_value: net_invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fc_held_btb_lc: fc_held_btb_lc.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	erq_account: erq_account.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	packaing_credit: packaing_credit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	cost_of_packaing_credit: cost_of_packaing_credit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	mda_normal: mda_normal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	inc_int_pur: inc_int_pur.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	source_tax: source_tax.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	frg_bank_charge: frg_bank_charge.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	current_account: current_account.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	ad_pu_amount: ad_pu_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	btbm_built: btbm_built.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	erq_cr: erq_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	pc_ad: pc_ad.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	mdan_cr: mdan_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	mdas_cr: mdas_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	mdau_cr: mdau_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	sct_deduct: sct_deduct.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fbc_ad: fbc_ad.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	cda_cr: cda_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fdr_cr: fdr_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	commi_cr: commi_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	disc_cr: disc_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	sht_rlz: sht_rlz.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	discrip_cr: discrip_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	exp_docp: exp_docp.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	cntrl_fund: cntrl_fund.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	oth_crg: oth_crg.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	exch_vari: exch_vari.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	total_cr: total_cr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

   latter(id)
   {
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(msApp.baseUrl()+"/expdocsubmission/latter?id="+id);
   }

   formatDetail(value,row)
   {
		return '<a href="javascript:void(0)"  onClick="MsExpProRlzReport.latter('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Nego</span></a>';
   }
}
window.MsExpProRlzReport=new MsExpProRlzReportController(new MsExpProRlzReportModel());
MsExpProRlzReport.showGrid([]);