//require('./../../jquery.easyui.min.js');
let MsEmployeeToDoListReportModel = require('./MsEmployeeToDoListReportModel');
require('./../../datagrid-filter.js');

class MsEmployeeToDoListReportController {
	constructor(MsEmployeeToDoListReportModel)
	{
		this.MsEmployeeToDoListReportModel = MsEmployeeToDoListReportModel;
		this.formId='employeetodolistreportFrm';
		this.dataTable='#employeetodolistreportTbl';
		this.route=msApp.baseUrl()+"/employeetodolistreport/getdata"
	}
	
	get(){
		let params={};
		params.user_id = $('#employeetodolistreportFrm  [name=user_id]').val();
		params.priority_id = $('#employeetodolistreportFrm  [name=priority_id]').val();
		params.date_from = $('#employeetodolistreportFrm  [name=date_from]').val();
        params.date_to = $('#employeetodolistreportFrm  [name=date_to]').val();
        //let params=this.getParams();
        if(!params.date_from && !params.date_to){
			alert('Select A Date Name First ');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
            $('#employeetodolistreportmatrix').html(response.data);
			//$('#employeetodolistreportTbl').datagrid('loadData', response.data);
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
            nowrap:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				/* for(var i=0;i<data.rows.length;i++){
					//
				} */
				/* $(this).datagrid('reloadFooter', [
				{ pdf: ' '}
				]); */
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getpdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/employeetodolistreport/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsEmployeeToDoListReport.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	}

}
window.MsEmployeeToDoListReport = new MsEmployeeToDoListReportController(new MsEmployeeToDoListReportModel());
MsEmployeeToDoListReport.showGrid({rows:{}});
