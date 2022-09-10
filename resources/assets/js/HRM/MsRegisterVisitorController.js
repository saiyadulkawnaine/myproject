let MsRegisterVisitorModel = require('./MsRegisterVisitorModel');
require('./../datagrid-filter.js');
class MsRegisterVisitorController {
	constructor(MsRegisterVisitorModel)
	{
		this.MsRegisterVisitorModel = MsRegisterVisitorModel;
		this.formId='registervisitorFrm';
		this.dataTable='#registervisitorTbl';
		this.route=msApp.baseUrl()+"/registervisitor";
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
			this.MsRegisterVisitorModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRegisterVisitorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#registervisitorFrm [id="user_id"]').combobox('setValue','');
		$('#registervisitorFrm [id="approve_user_id"]').val($('#registervisitorFrm [id="approve_user_id"]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsRegisterVisitorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRegisterVisitorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#registervisitorTbl').datagrid('reload');
		msApp.resetForm('registervisitorFrm');
		$('#registervisitorFrm [id="user_id"]').combobox('setValue','');
		$('#registervisitorFrm [id="approve_user_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let visit=this.MsRegisterVisitorModel.get(index,row);
		visit.then(function(response){
			$('#registervisitorFrm [id="user_id"]').combobox('setValue',response.data.fromData.user_id);
			$('#registervisitorFrm [id="approve_user_id"]').combobox('setValue',response.data.fromData.approve_user_id);
		})
		.catch(function(error){
			console.log(error);
		})
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRegisterVisitor.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	/*pdf(id){
		var id=$('#registervisitorFrm [name=id]').val();
		if(id==''){
			alert("Select An Entry First");
			return;
		}
		window.open(this.route+"/getrenewalpdf?id="+id);
		//window.open(this.route+"/getrenewalpdf");
	}*/
	
	pdf(id){
		var id=$('#registervisitorFrm [name=id]').val();
		if(id==''){
			alert("Select An Entry First");
			return;
		}
		window.open(msApp.baseUrl()+"/registervisitor/getapprovedpdf?id="+id);
	}

	// formatPdf(value,row){
		
	// 	return '<a href="javascript:void(0)"  onClick="MsRegisterVisitor.pdf('+row.approved_by+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Approved</span></a>';
	// }

}
window.MsRegisterVisitor=new MsRegisterVisitorController(new MsRegisterVisitorModel());
MsRegisterVisitor.showGrid();