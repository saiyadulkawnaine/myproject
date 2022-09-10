let MsSignatureUserModel = require('./MsSignatureUserModel');
class MsSignatureUserController {
	constructor(MsSignatureUserModel)
	{
		this.MsSignatureUserModel = MsSignatureUserModel;
		this.formId='signatureuserFrm';
		this.dataTable='#signatureuserTbl';
		this.route=msApp.baseUrl()+"/signatureuser"
	}

	submit()
	{
		var id = $('#signatureuserFrm [name=id]').val();
        var formData = new FormData();
        formData.append('id',id);
        var file = document.getElementById('signature_file');
        formData.append('signature_file',file.files[0]);
        this.MsSignatureUserModel.upload(this.route,'POST',formData,this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSignatureUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSignatureUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#signatureuserTbl').datagrid('reload');
		msApp.resetForm('signatureuserFrm');
        $('#signatureuserFrm [name=id]').val($('#userFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSignatureUserModel.get(index,row);
	}

	showGrid(id)
	{
		let self=this;
		  let data={};
		  data.id=id;
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
		return '<a href="javascript:void(0)"  onClick="MsSignatureUser.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/images/"+row.signature_file+ '">'+row.signature_file+'</a>';
	}

    // upload(){
	// 	var id = $('#signatureuserFrm [name=id]').val();//document.getElementById("style_id").value;
	// 	var formData = new FormData();
	// 	formData.append("id", id);
	// 	var file = document.getElementById("uploadfile");

	// 	formData.append("uploaddata", file.files[0]);

	// 	this.MsSignatureUserModel.upload(msApp.baseUrl()+"/style/upload",'POST',formData,this.response);
	// }
}
window.MsSignatureUser=new MsSignatureUserController(new MsSignatureUserModel());