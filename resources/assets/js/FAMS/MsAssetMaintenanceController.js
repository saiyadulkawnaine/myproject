//require('../jquery.easyui.min.js');
let MsAssetMaintenanceModel = require('./MsAssetMaintenanceModel');
//require('../datagrid-filter.js');
class MsAssetMaintenanceController {
	constructor(MsAssetMaintenanceModel)
	{
		this.MsAssetMaintenanceModel = MsAssetMaintenanceModel;
		this.formId='assetmaintenanceFrm';
		this.dataTable='#assetmaintenanceTbl';
		this.route=msApp.baseUrl()+"/assetmaintenance"
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
			this.MsAssetMaintenanceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetMaintenanceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetMaintenanceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetMaintenanceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assetmaintenanceTbl').datagrid('reload');
		msApp.resetForm('assetmaintenanceFrm');
		$('#assetmaintenanceFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let asset=this.MsAssetMaintenanceModel.get(index,row);

	}

	showGrid(asset_acquisition_id)
	{
		let self=this;
		var data={};
		data.asset_acquisition_id=asset_acquisition_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	//////////////////////////////////
	openItemDescWindow(){
		$('#OpenItemWindow').window('open');
	}
	searchItemDesGrid(){
		let data={};
		data.item_account_id=$('#itemDescSearchFrm [name=item_account_id]').val();
		data.itemcategory_id=$('#itemDescSearchFrm [name=itemcategory_id]').val();
		data.itemclass_id=$('#itemDescSearchFrm [name=itemclass_id]').val();
		let self = this;
		$('#itemDescSearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/itemaccount/getItemAccount",
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#assetmaintenanceFrm [name=item_account_id]').val(row.id);
				$('#assetmaintenanceFrm [name=item_description]').val(row.item_description);
				$('#OpenItemWindow').window('close');
			}
		}).datagrid('enableFilter');
	}
	//////////////

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAssetMaintenance.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAssetMaintenance=new MsAssetMaintenanceController(new MsAssetMaintenanceModel());

