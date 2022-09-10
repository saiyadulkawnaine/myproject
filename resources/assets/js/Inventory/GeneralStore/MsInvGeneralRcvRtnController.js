let MsInvGeneralRcvRtnModel = require('./MsInvGeneralRcvRtnModel');
require('./../../datagrid-filter.js');
class MsInvGeneralRcvRtnController {
	constructor(MsInvGeneralRcvRtnModel)
	{
		this.MsInvGeneralRcvRtnModel = MsInvGeneralRcvRtnModel;
		this.formId='invgeneralrcvrtnFrm';
		this.dataTable='#invgeneralrcvrtnTbl';
		this.route=msApp.baseUrl()+"/invgeneralrcvrtn"
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
			this.MsInvGeneralRcvRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralRcvRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralRcvRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneralrcvrtnTbl').datagrid('reload');
		msApp.resetForm('invgeneralrcvrtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralRcvRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgeneralrcvrtnitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralRcvRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneralrcvrtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralRcvRtn=new MsInvGeneralRcvRtnController(new MsInvGeneralRcvRtnModel());
MsInvGeneralRcvRtn.showGrid();

$('#invgeneralrcvrtntabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invgeneralrcvrtnFrm [name=id]').val();
        var data={};
		data.inv_isu_id=inv_isu_id;
        if(index==1){
			if(inv_isu_id===''){
				$('#invyarnrcvrtnrqtabs').tabs('select',0);
				msApp.showError('Select Master Entry First',0);
				return;
		    }
			$('#invgeneralrcvrtnitemFrm  [name=inv_general_isu_id]').val(inv_isu_id);
			MsInvGeneralRcvRtnItem.get(inv_isu_id);
        }
    }
});

