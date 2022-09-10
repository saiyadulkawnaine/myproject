let MsPurchaseRequisitionReportModel = require('./MsPurchaseRequisitionReportModel');
require('./../../datagrid-filter.js');

class MsPurchaseRequisitionReportController {
	constructor(MsPurchaseRequisitionReportModel)
	{
		this.MsPurchaseRequisitionReportModel = MsPurchaseRequisitionReportModel;
		this.formId='purchaserequisitionreportFrm';
		this.dataTable='#purchaserequisitionreportTbl';
		this.route=msApp.baseUrl()+"/purchaserequisitionreport/getdata"
	}
	
	get(){
		let params={};
		params.date_from = $('#purchaserequisitionreportFrm  [name=date_from]').val();
		params.date_to = $('#purchaserequisitionreportFrm  [name=date_to]').val();
		params.company_id = $('#purchaserequisitionreportFrm  [name=company_id]').val();
		params.requisition_no = $('#purchaserequisitionreportFrm  [name=requisition_no]').val();
        // if(!params.menu_id){
		// 	alert('Select A Menu Name First ');
		// 	return;
		// }
		let d= axios.get(this.route,{params})
		.then(function (response) {

			$('#purchaserequisitionreportTbl').datagrid('loadData', response.data);
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
				var tRate=0;
				var tAmount=0;
				var tPaidAmount=0;
				var tBalanceAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tPaidAmount+=data.rows[i]['paid_amount'].replace(/,/g,'')*1;
					tBalanceAmount+=data.rows[i]['balance_amount'].replace(/,/g,'')*1;
				}
				tRate=tAmount/tQty;
				$('#purchaserequisitionreportTbl').datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						paid_amount: tPaidAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						balance_amount: tBalanceAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
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

	formatprintpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPurchaseRequisitionReport.pdf('+row.id+')">'+row.requisition_no+'</a>';
	}

	pdf(id)
	{
		//var id = $('#invpurreqFrm [name=id]').val();
		if(id==""){
			alert("Select a Purchase Requisition No");
			return;
		}
		window.open(msApp.baseUrl()+"/invpurreq/getprpdf?id="+id);
	}

	openInvPurReqWindow(){
		$('#invpurreqWindow').window('open');
	}

    getPoParams(){
        let params = {};
		params.requisition_no = $('#invpurreqsearchFrm [name="requisition_no"]').val();
		params.company_id = $('#invpurreqsearchFrm [name="company_id"]').val();
        return params;
    }

	searchRequisition(){
		let params=this.getPoParams();

		let lcsc= axios.get(msApp.baseUrl()+"/purchaserequisitionreport/getrequisition",{params})
		.then(function (response) {
			$('#invpurreqsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
        return lcsc;
	}

    showRequisitionGrid(data){
        let self = this;
		$('#invpurreqsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
               // $('#purchaserequisitionreportFrm [name=id]').val(row.id);
                $('#purchaserequisitionreportFrm [name=requisition_no]').val(row.requisition_no);
                $('#invpurreqWindow').window('close');
                $('#invpurreqsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
    }

	jobdonemsg(value,row,index)
	{
		if (row.job_done=='Yes'){
		    return 'background-color:#4CAE4C;color:white;font-weight: bold';
	    }
	}

}
window.MsPurchaseRequisitionReport = new MsPurchaseRequisitionReportController(new MsPurchaseRequisitionReportModel());
MsPurchaseRequisitionReport.showGrid([]);
MsPurchaseRequisitionReport.showRequisitionGrid([]);
