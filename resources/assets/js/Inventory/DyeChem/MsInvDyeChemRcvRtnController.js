let MsInvDyeChemRcvRtnModel = require('./MsInvDyeChemRcvRtnModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemRcvRtnController {
	constructor(MsInvDyeChemRcvRtnModel)
	{
		this.MsInvDyeChemRcvRtnModel = MsInvDyeChemRcvRtnModel;
		this.formId='invdyechemrcvrtnFrm';
		this.dataTable='#invdyechemrcvrtnTbl';
		this.route=msApp.baseUrl()+"/invdyechemrcvrtn"
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
			this.MsInvDyeChemRcvRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemRcvRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemRcvRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemRcvRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemrcvrtnTbl').datagrid('reload');
		msApp.resetForm('invdyechemrcvrtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemRcvRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invdyechemrcvrtnitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemRcvRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemrcvrtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvDyeChemRcvRtn=new MsInvDyeChemRcvRtnController(new MsInvDyeChemRcvRtnModel());
MsInvDyeChemRcvRtn.showGrid();

$('#invdyechemrcvrtntabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invdyechemrcvrtnFrm [name=id]').val();
        var data={};
		data.inv_isu_id=inv_isu_id;
        if(index==1){
			if(inv_isu_id===''){
				$('#invdyechemrcvrtntabs').tabs('select',0);
				msApp.showError('Select Master Entry First',0);
				return;
		    }
			$('#invdyechemrcvrtnitemFrm  [name=inv_dye_chem_isu_id]').val(inv_isu_id);
			MsInvDyeChemRcvRtnItem.get(inv_isu_id);
        }
    }
});

