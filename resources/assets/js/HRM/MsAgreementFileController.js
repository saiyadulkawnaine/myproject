let MsAgreementFileModel = require('./MsAgreementFileModel');
class MsAgreementFileController {
	constructor(MsAgreementFileModel)
	{
		this.MsAgreementFileModel = MsAgreementFileModel;
		this.formId='agreementfileFrm';
		this.dataTable='#agreementfileTbl';
		this.route=msApp.baseUrl()+"/agreementfile"
	}

	submit()
	{
		var id = $('#agreementfileFrm [name=id]').val();
		var agreement_id = $('#agreementfileFrm [name=agreement_id]').val();
		var original_name = $('#agreementfileFrm [name=original_name]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('agreement_id',agreement_id);
		formData.append('original_name',original_name);
		var file = document.getElementById('file_upload');
		formData.append('file_src',file.files[0]);
		this.MsAgreementFileModel.upload(this.route,'POST',formData,this.response);
	}
	

	resetForm ()
	{
        msApp.resetForm(this.formId);
        $('#agreementfileFrm [name=agreement_id]').val($('#agreementFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAgreementFileModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAgreementFileModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#agreementfileTbl').datagrid('reload');
		msApp.resetForm('agreementfileFrm');
        $('#agreementfileFrm [name=agreement_id]').val($('#agreementFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAgreementFileModel.get(index,row);

	}

	showGrid(agreement_id)
	{
		let self=this;
		  let data={};
		  data.agreement_id=agreement_id;
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
		return '<a href="javascript:void(0)"  onClick="MsAgreementFile.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/images/"+row.file_src+ '">'+row.file_src+'</a>';
	}
}
window.MsAgreementFile=new MsAgreementFileController(new MsAgreementFileModel());