require('./../datagrid-filter.js');
let MsBuyerDevelopmentDocModel = require('./MsBuyerDevelopmentDocModel');
class MsBuyerDevelopmentDocController {
	constructor(MsBuyerDevelopmentDocModel)
	{
		this.MsBuyerDevelopmentDocModel = MsBuyerDevelopmentDocModel;
		this.formId='buyerdevelopmentdocFrm';
		this.dataTable='#buyerdevelopmentdocTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopmentdoc"
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
		//let buyer_development_id = $('#buyerdevelopmentFrm  [name=id]').val();
		var id = $('#buyerdevelopmentdocFrm [name=id]').val();
		var buyer_development_id = $('#buyerdevelopmentFrm [name=id]').val();
		var original_name = $('#buyerdevelopmentdocFrm [name=original_name]').val();
		var file_type_id = $('#buyerdevelopmentdocFrm [name=file_type_id]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('buyer_development_id',buyer_development_id);
		formData.append('original_name',original_name);
		formData.append('file_type_id',file_type_id);
		var file = document.getElementById('file_upload');
		formData.append('file_src',file.files[0]);
		this.MsBuyerDevelopmentDocModel.upload(this.route,'POST',formData,this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#buyerdevelopmentdocFrm [id="buyer_id"]').combobox('setValue', '');
		//$('#buyerdevelopmentdocFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerDevelopmentDocModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerDevelopmentDocModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let buyer_development_id = $('#buyerdevelopmentFrm  [name=id]').val();
		MsBuyerDevelopmentDoc.get(buyer_development_id);
		MsBuyerDevelopmentDoc.resetForm();		
		//$('#buyerdevelopmentdocFrm  [name=id]').val(d.id);

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBuyerDevelopmentDocModel.get(index,row);
		data.then(function(response){
			//$('#buyerdevelopmentdocFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			//$('#buyerdevelopmentdocFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			//MsTargetTransfer.getInfo($('#targettransferFrm  [name=process_id] option:selected').val())

		}).catch(function(error){
			console.log(error);
		});
		
	}

	get(buyer_development_id){
		let data= axios.get(this.route+"?buyer_development_id="+buyer_development_id);
		data.then(function (response) {
			$('#buyerdevelopmentdocTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentDoc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/images/"+row.file_src+ '">'+row.file_src+'</a>';
	}
}


window.MsBuyerDevelopmentDoc=new MsBuyerDevelopmentDocController(new MsBuyerDevelopmentDocModel());
MsBuyerDevelopmentDoc.showGrid([]);