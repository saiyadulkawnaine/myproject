let MsStyleFileUploadModel = require('./MsStyleFileUploadModel');
class MsStyleFileUploadController {
	constructor(MsStyleFileUploadModel)
	{
		this.MsStyleFileUploadModel = MsStyleFileUploadModel;
		this.formId='stylefileuploadFrm';
		this.dataTable='#stylefileuploadTbl';
		this.route=msApp.baseUrl()+"/stylefileupload"
	}

	submit()
	{
		var id = $('#stylefileuploadFrm [name=id]').val();
		var style_id = $('#stylefileuploadFrm [name=style_id]').val();
		var original_name = $('#stylefileuploadFrm [name=original_name]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('style_id',style_id);
		formData.append('original_name',original_name);
		var file = document.getElementById('file_upload');
		formData.append('file_src',file.files[0]);
		this.MsStyleFileUploadModel.upload(this.route,'POST',formData,this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleFileUploadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleFileUploadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylefileuploadTbl').datagrid('reload');
		msApp.resetForm('stylefileuploadFrm');
    $('#stylefileuploadFrm [name=style_id]').val($('#styleFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsStyleFileUploadModel.get(index,row);

	}

	showGrid(style_id)
	{
		let self=this;
		  let data={};
		  data.style_id=style_id;
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
		return '<a href="javascript:void(0)"  onClick="MsStyleFileUpload.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/images/"+row.file_src+ '">'+row.file_src+'</a>';
	}
}
window.MsStyleFileUpload=new MsStyleFileUploadController(new MsStyleFileUploadModel());