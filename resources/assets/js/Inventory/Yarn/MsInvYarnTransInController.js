let MsInvYarnTransInModel = require('./MsInvYarnTransInModel');
require('./../../datagrid-filter.js');
class MsInvYarnTransInController {
	constructor(MsInvYarnTransInModel)
	{
		this.MsInvYarnTransInModel = MsInvYarnTransInModel;
		this.formId='invyarntransinFrm';
		this.dataTable='#invyarntransinTbl';
		this.route=msApp.baseUrl()+"/invyarntransin"
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
			this.MsInvYarnTransInModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnTransInModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnTransInModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnTransInModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarntransinTbl').datagrid('reload');
		msApp.resetForm('invyarntransinFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnTransInModel.get(index,row);
		data.then(function (response) {
			//$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsInvYarnTransIn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarntransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvYarnTransIn=new MsInvYarnTransInController(new MsInvYarnTransInModel());
MsInvYarnTransIn.showGrid();

$('#invyarntransintabs').tabs({
    onSelect:function(title,index){
        let inv_yarn_rcv_id = $('#invyarntransinFrm [name=inv_yarn_rcv_id]').val();
        if(index==1){
			if(inv_yarn_rcv_id==='')
			{
				$('#invyarntransintabs').tabs('select',0);
				msApp.showError('Select  Entry First',0);
				return;
		    }
		    msApp.resetForm('invyarntransinitemFrm');
			$('#invyarntransinitemFrm  [name=inv_yarn_rcv_id]').val(inv_yarn_rcv_id);
			MsInvYarnTransInItem.get(inv_yarn_rcv_id);
        }
    }
});

