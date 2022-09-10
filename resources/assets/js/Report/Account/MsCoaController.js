//require('./../../jquery.easyui.min.js');
let MsCoaModel = require('./MsCoaModel');
require('./../../datagrid-filter.js');

class MsCoaController {
	constructor(MsCoaModel)
	{
		this.MsCoaModel = MsCoaModel;
		this.formId='coaFrm';
		this.dataTable='#coaTbl';
		this.route=msApp.baseUrl()+"/coa";
	}

	showGrid()
	{

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			url:this.route+"/html",
		}).datagrid('enableFilter');
	}

	pdf	(){
		window.open(msApp.baseUrl()+"/coa/pdf");
	}

}
window.MsCoa=new MsCoaController(new MsCoaModel());
MsCoa.showGrid();
