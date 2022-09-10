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
		var formData = new FormData();
		formData.append('id',id);
		formData.append('asset_acquisition_id',asset_acquisition_id);
		var file = document.getElementById('upload_file');
		formData.append('file_src',file.files[0]);
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
        $('#assettechfileuploadFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
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

