let MsPendingImpLcPoReportModel = require('./MsPendingImpLcPoReportModel');
require('./../../datagrid-filter.js');

class MsPendingImpLcPoReportController {
	constructor(MsPendingImpLcPoReportModel)
	{
		this.MsPendingImpLcPoReportModel = MsPendingImpLcPoReportModel;
		this.formId='pendingimplcporeportFrm';
		this.dataTable='#pendingimplcporeportTbl';
		this.route=msApp.baseUrl()+"/pendingimplcporeport";
	}
	getParams()
	{
	    let params={};
		params.company_id = $('#pendingimplcporeportFrm  [name=company_id]').val();
		params.supplier_id = $('#pendingimplcporeportFrm  [name=supplier_id]').val();
        params.menu_id = $('#pendingimplcporeportFrm  [name=menu_id]').val();
        params.pi_no = $('#pendingimplcporeportFrm  [name=pi_no]').val();
	    params.date_from = $('#pendingimplcporeportFrm  [name=date_from]').val();
		params.date_to = $('#pendingimplcporeportFrm  [name=date_to]').val();
        
		return 	params;
	}
	
	get(){
        let params=this.getParams();
        if(!params.date_from && !params.date_to){
            alert('Select A Date Range');
            return;
        }
        if(!params.menu_id){
            alert('Select An Item');
            return;
        }
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#pendingimplcporeportTbl').datagrid('loadData', response.data);
				
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
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){	
				var tAmount=0;

				for(var i=0; i<data.rows.length; i++){
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;	
				}
					$(this).datagrid('reloadFooter', [
				{
					amount: tAmount.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
window.MsPendingImpLcPoReport=new MsPendingImpLcPoReportController(new MsPendingImpLcPoReportModel());
MsPendingImpLcPoReport.showGrid([]);