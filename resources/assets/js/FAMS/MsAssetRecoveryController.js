let MsAssetRecoveryModel = require('./MsAssetRecoveryModel');
class MsAssetRecoveryController {
	constructor(MsAssetRecoveryModel)
	{
		this.MsAssetRecoveryModel = MsAssetRecoveryModel;
		this.formId='assetrecoveryFrm';
		this.dataTable='#assetrecoveryTbl';
		this.route=msApp.baseUrl()+"/assetrecovery"
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
		var id = $('#assetbreakdownFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.id = id;
		//alert(id);
		if(formObj.id){
			this.MsAssetRecoveryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetRecoveryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetRecoveryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetRecoveryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$(this.dataTable).datagrid('reload');
		msApp.resetForm('assetrecoveryFrm');
		$('#assetrecoveryFrm [name=id]').val($('#assetbreakdownFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetRecoveryModel.get(index,row);
	}

	showGrid(id)
	{
		let self=this;
		let data ={};
		data.id = id;
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
		return '<a href="javascript:void(0)"  onClick="MsAssetRecovery.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAssetRecovery=new MsAssetRecoveryController(new MsAssetRecoveryModel());