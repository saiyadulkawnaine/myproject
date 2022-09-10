let MsAssetUtilityDetailModel = require('./MsAssetUtilityDetailModel');
class MsAssetUtilityDetailController {
	constructor(MsAssetUtilityDetailModel)
	{
		this.MsAssetUtilityDetailModel = MsAssetUtilityDetailModel;
		this.formId='assetutilitydetailFrm';
		this.dataTable='#assetutilitydetailTbl';
		this.route=msApp.baseUrl()+"/assetutilitydetail"
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
			this.MsAssetUtilityDetailModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetUtilityDetailModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetUtilityDetailModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetUtilityDetailModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assetutilitydetailTbl').datagrid('reload');
		msApp.resetForm('assetutilitydetailFrm');
		$('#assetutilitydetailFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetUtilityDetailModel.get(index,row);

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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAssetUtilityDetail.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAssetUtilityDetail=new MsAssetUtilityDetailController(new MsAssetUtilityDetailModel());

