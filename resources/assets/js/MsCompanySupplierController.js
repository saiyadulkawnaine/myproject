let MsCompanySupplierModel = require('./MsCompanySupplierModel');
class MsCompanySupplierController {
	constructor(MsCompanySupplierModel)
	{
		this.MsCompanySupplierModel = MsCompanySupplierModel;
		this.formId='companysupplierFrm';
		this.dataTable='#companysupplierTbl';
		this.route=msApp.baseUrl()+"/companysupplier"
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

		let formObj=msApp.get('companysupplierFrm');
		let i=1;
		$.each($('#companysupplierTbl').datagrid('getChecked'), function (idx, val) {
				formObj['company_id['+i+']']=val.id
				
			i++;
		});
		this.MsCompanySupplierModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var supplier_id=$('#supplierFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/companysupplier/create?supplier_id="+supplier_id);
				data.then(function (response) {
				$('#companysupplierTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Company',width:100},
				]],
				});
				
				$('#companysuppliersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Company',width:100},
				{field:'action',title:'',width:60,formatter:MsCompanySupplier.formatDetail},
				]],
				});
				})
				.catch(function (error) {
				console.log(error);
				});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCompanySupplierModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsCompanySupplierModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#suppliernatureTbl').datagrid('reload');
		MsCompanySupplier.create()
		//msApp.resetForm('suppliernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompanySupplierModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCompanySupplier.delete(event,'+row.company_supplier_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCompanySupplier=new MsCompanySupplierController(new MsCompanySupplierModel());

