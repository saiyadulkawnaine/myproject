let MsInvFinishFabIsuModel = require('./MsInvFinishFabIsuModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabIsuController {
	constructor(MsInvFinishFabIsuModel)
	{
		this.MsInvFinishFabIsuModel = MsInvFinishFabIsuModel;
		this.formId='invfinishfabisuFrm';
		this.dataTable='#invfinishfabisuTbl';
		this.route=msApp.baseUrl()+"/invfinishfabisu"
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
			this.MsInvFinishFabIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invfinishfabisuFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invfinishfabisuFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabisuTbl').datagrid('reload');
		msApp.resetForm('invfinishfabisuFrm');
		$('#invfinishfabisuFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invfinishfabisuFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabIsuModel.get(index,row);
		data.then(function(response){
			$('#invfinishfabisuFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#invfinishfabisuFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			msApp.resetForm('invfinishfabisuitemFrm');
		}).catch(function(error){
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
		return '<a href="javascript:void(0)"  onClick="MsRcvFinishFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invfinishfabisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invfinishfabisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}
}
window.MsInvFinishFabIsu=new MsInvFinishFabIsuController(new MsInvFinishFabIsuModel());
MsInvFinishFabIsu.showGrid();

$('#invfinishfabisutabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invfinishfabisuFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invfinishfabisutabs').tabs('select',0);
				msApp.showError('Select Finish Fab Issue Entry First',0);
				return;
		    }
			$('#invfinishfabisuitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvFinishFabIsuItem.get(inv_isu_id);
        }
    }
});

