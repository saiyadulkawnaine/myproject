let MsSupplierNatureModel = require('./MsSupplierNatureModel');
class MsSupplierNatureController {
	constructor(MsSupplierNatureModel)
	{
		this.MsSupplierNatureModel = MsSupplierNatureModel;
		this.formId='suppliernatureFrm';
		this.dataTable='#suppliernatureTbl';
		this.route=msApp.baseUrl()+"/suppliernature"
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

		let formObj=msApp.get('suppliernatureFrm');
		let i=1;
		$.each($('#suppliernatureTbl').datagrid('getChecked'), function (idx, val) {
				formObj['contact_nature_id['+i+']']=val.id
				
			i++;
		});
		this.MsSupplierNatureModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var supplier_id=$('#supplierFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/suppliernature/create?supplier_id="+supplier_id);
			data.then(function (response) {
				$('#suppliernatureTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Nature',width:300},
				]],
				}).datagrid('enableFilter');
				
				$('#suppliernaturesavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Nature',width:300},
				{field:'action',title:'',width:60,formatter:MsSupplierNature.formatDetail},
				]],
				}).datagrid('enableFilter');
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
		this.MsSupplierNatureModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsSupplierNatureModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#suppliernatureTbl').datagrid('reload');
		MsSupplierNature.create();
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSupplierNatureModel.get(index,row);
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
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSupplierNature.delete(event,'+row.supplier_nature_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSupplierNature=new MsSupplierNatureController(new MsSupplierNatureModel());
//MsSupplierNature.showGrid();
