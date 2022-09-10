let MsSupplierSettingModel = require('./MsSupplierSettingModel');
require('./datagrid-filter.js');
class MsSupplierSettingController {
	constructor(MsSupplierSettingModel)
	{
		this.MsSupplierSettingModel = MsSupplierSettingModel;
		this.formId='suppliersettingFrm';
		this.dataTable='#suppliersettingTbl';
		this.route=msApp.baseUrl()+"/suppliersetting"
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
			this.MsSupplierSettingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSupplierSettingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#suppliersettingFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSupplierSettingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSupplierSettingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#suppliersettingTbl').datagrid('reload');
		msApp.resetForm('suppliersettingFrm');
		$('#suppliersettingFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let dlvorder = this.MsSupplierSettingModel.get(index,row);
		dlvorder.then(function (response) {
			$('#suppliersettingFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function (error) {
			console.log(error);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsSupplierSetting.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}

window.MsSupplierSetting=new MsSupplierSettingController(new MsSupplierSettingModel());
MsSupplierSetting.showGrid([]);