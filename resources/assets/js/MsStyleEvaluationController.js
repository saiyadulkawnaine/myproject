let MsStyleEvaluationModel = require('./MsStyleEvaluationModel');
class MsStyleEvaluationController {
	constructor(MsStyleEvaluationModel)
	{
		this.MsStyleEvaluationModel = MsStyleEvaluationModel;
		this.formId='styleevaluationFrm';
		this.dataTable='#styleevaluationTbl';
		this.route=msApp.baseUrl()+"/styleevaluation"
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
			this.MsStyleEvaluationModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleEvaluationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleEvaluationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleEvaluationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#styleevaluationTbl').datagrid('reload');
		//$('#StyleEvaluationFrm  [name=id]').val(d.id);
		msApp.resetForm('styleevaluationFrm');
		$('#styleevaluationFrm  [name=style_ref]').val($('#styleFrm  [name=style_ref]').val());
		$('#styleevaluationFrm  [name=style_id]').val($('#styleFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleEvaluationModel.get(index,row);
	}

	showGrid(style_id)
	{
		let self=this;
		var data={};
		data.style_id=style_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleEvaluation.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	upload(){
		var style_id=$('#styleimageFrm  [name=style_id]').val();//document.getElementById("style_id").value;
		var formData = new FormData();
		formData.append("style_id", style_id);
		var file = document.getElementById("uploadfile");
		var file2 = document.getElementById("uploadfilename");
		formData.append("uploaddata", file.files[0]);
		formData.append("uploadfiledata", file2.files[0]);
		this.MsStyleEvaluationModel.upload(msApp.baseUrl()+"/style/upload",'POST',formData,this.response);
	}
	loadFile (event){
		var output = document.getElementById('output');
    	output.src = URL.createObjectURL(event.target.files[0]);
	}



}
window.MsStyleEvaluation=new MsStyleEvaluationController(new MsStyleEvaluationModel());
//MsStyleEvaluation.showGrid();
