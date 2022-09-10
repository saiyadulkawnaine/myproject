//require('./../../jquery.easyui.min.js');
let MsItemBankModel = require('./MsItemBankModel');
require('./../../datagrid-filter.js');

class MsItemBankController {
	constructor(MsItemBankModel)
	{
		this.MsItemBankModel = MsItemBankModel;
		this.formId='itembankFrm';
		this.dataTable='#itembankTbl';
		this.route=msApp.baseUrl()+"/itembank/getdata"
	}
	
	get(){
		let params={};
		params.itemcategory_id = $('#itembankFrm  [name=itemcategory_id]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {

			$('#itembankTbl').datagrid('loadData', response.data);
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
			rowStyler:function(index,row)
			{
				if (row.supplier_name==='Item:'){
					return 'background-color:#ccc;color:black;font-weight:bold;';
				}
		    },
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
		window.open(msApp.baseUrl()+"/itembank/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsItemBank.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	}

}
window.MsItemBank = new MsItemBankController(new MsItemBankModel());
MsItemBank.showGrid([]);
