let MsImpLcFileModel = require('./MsImpLcFileModel');
class MsImpLcFileController {
	constructor(MsImpLcFileModel){
		this.MsImpLcFileModel = MsImpLcFileModel;
		this.formId='implcfileFrm';
		this.dataTable='#implcfileTbl';
		this.route=msApp.baseUrl()+"/implcfile"
	}
	submit(){
		// $.blockUI({
		// 	message: '<i class="icon-spinner4 spinner">Saving...</i>',
		// 	overlayCSS: {
		// 		backgroundColor: '#1b2024',
		// 		opacity: 0.8,
		// 		zIndex: 999999,
		// 		cursor: 'wait'
		// 	},
		// 	css: {
		// 		border: 0,
		// 		color: '#fff',
		// 		padding: 0,
		// 		zIndex: 9999999,
		// 		backgroundColor: 'transparent'
		// 	}
		// });
		var id = $('#implcfileFrm [name=id]').val();
		var imp_lc_id = $('#implcfileFrm [name=imp_lc_id]').val();
		var original_name = $('#implcfileFrm [name=original_name]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('imp_lc_id',imp_lc_id);
		formData.append('original_name',original_name);
		var file = document.getElementById('file_src');
		formData.append('file_src',file.files[0]);

		this.MsImpLcFileModel.upload(this.route,'POST',formData,this.response);
	}
	resetForm (){
		msApp.resetForm(this.formId);
	}
	remove(){
		let formObj=msApp.get(this.formId);
		this.MsImpLcFileModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	delete(event,id){
		event.stopPropagation()
		this.MsImpLcFileModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d){
		$('#implcfileTbl').datagrid('reload');
		msApp.resetForm('implcfileFrm');
		//alert('dd');
		$('#implcfileFrm [name=imp_lc_id]').val($('#implcFrm [name=id]').val());
	}

	edit(index,row){
		row.route=this.route;
		row.formId=this.formId;		
		this.MsImpLcFileModel.get(index,row);
	}

	showGrid(imp_lc_id){
		let self=this;
		  let data={};
		  data.imp_lc_id=imp_lc_id;
		  $(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpLcFile.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	formatFile(value,row)
	{
		var url = msApp.baseUrl()+"/images/"+row.file_src;
    	return '<a target="_blank" href="' + url + '" download><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>download</span></a>';
	}

}
window.MsImpLcFile=new MsImpLcFileController(new MsImpLcFileModel());