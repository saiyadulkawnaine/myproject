let MsInvFinishFabTransOutModel = require('./MsInvFinishFabTransOutModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabTransOutController {
	constructor(MsInvFinishFabTransOutModel)
	{
		this.MsInvFinishFabTransOutModel = MsInvFinishFabTransOutModel;
		this.formId='invfinishfabtransoutFrm';
		this.dataTable='#invfinishfabtransoutTbl';
		this.route=msApp.baseUrl()+"/invfinishfabtransout"
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
			this.MsInvFinishFabTransOutModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransOutModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabTransOutModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabTransOutModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabtransoutTbl').datagrid('reload');
		msApp.resetForm('invfinishfabtransoutFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabTransOutModel.get(index,row);
		data.then(function (response) {
			//$('#invfinishfabisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransOut.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invfinishfabtransoutFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invfinishfabtransoutFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvFinishFabTransOut=new MsInvFinishFabTransOutController(new MsInvFinishFabTransOutModel());
MsInvFinishFabTransOut.showGrid();

$('#invfinishfabtransouttabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invfinishfabtransoutFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invfinishfabtransouttabs').tabs('select',0);
				msApp.showError('Select  Entry First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabtransoutitemFrm');
			$('#invfinishfabtransoutitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvFinishFabTransOutItem.get(inv_isu_id);
			//MsInvFinishFabTransOutItem.showGrid(inv_isu_id);
        }
    }
});

