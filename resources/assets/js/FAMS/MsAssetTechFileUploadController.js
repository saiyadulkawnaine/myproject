let MsAssetTechFileUploadModel = require('./MsAssetTechFileUploadModel');
class MsAssetTechFileUploadController {
	constructor(MsAssetTechFileUploadModel)
	{
		this.MsAssetTechFileUploadModel = MsAssetTechFileUploadModel;
		this.formId='assettechfileuploadFrm';
		this.dataTable='#assettechfileuploadTbl';
		this.route=msApp.baseUrl()+"/assettechfileupload"
	}

	submit()
	{
		var id = $('#assettechfileuploadFrm [name=id]').val();
		var asset_acquisition_id = $('#assettechfileuploadFrm [name=asset_acquisition_id]').val();
		//var file_src = $('#assettechfileuploadFrm [name=file_src]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('asset_acquisition_id',asset_acquisition_id);
		//formData.append('file_src',file_src);
		var file = document.getElementById('file_upload');
		formData.append('file_src',file.files[0]);
		
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

		this.MsAssetTechFileUploadModel.upload(this.route,'POST',formData,this.response);   
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetTechFileUploadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetTechFileUploadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assettechfileuploadTbl').datagrid('reload');
		msApp.resetForm('assettechfileuploadFrm');
        $('#assettechfileuploadFrm [name=asset_acquisition_id]').val($('#assettechfeatureFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetTechFileUploadModel.get(index,row);

	}

	showGrid(asset_acquisition_id)
	{
		let self=this;
		  let data={};
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
		return '<a href="javascript:void(0)"  onClick="MsAssetTechFileUpload.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAssetTechFileUpload=new MsAssetTechFileUploadController(new MsAssetTechFileUploadModel());

