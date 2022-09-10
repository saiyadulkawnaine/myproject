let MsInvGeneralIsuModel = require('./MsInvGeneralIsuModel');
require('./../../datagrid-filter.js');
class MsInvGeneralIsuController {
	constructor(MsInvGeneralIsuModel)
	{
		this.MsInvGeneralIsuModel = MsInvGeneralIsuModel;
		this.formId='invgeneralisuFrm';
		this.dataTable='#invgeneralisuTbl';
		this.route=msApp.baseUrl()+"/invgeneralisu"
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
			this.MsInvGeneralIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneralisuTbl').datagrid('reload');
		msApp.resetForm('invgeneralisuFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralIsuModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgeneralisuitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsu.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneralisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralIsu=new MsInvGeneralIsuController(new MsInvGeneralIsuModel());
MsInvGeneralIsu.showGrid();

$('#invgeneralisutabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invgeneralisuFrm [name=id]').val();
        var data={};
		data.inv_isu_id=inv_isu_id;
        if(index==1){
			if(inv_isu_id===''){
				$('#invyarnisurqtabs').tabs('select',0);
				msApp.showError('Select Master Entry First',0);
				return;
		    }
			$('#invgeneralisuitemFrm  [name=inv_general_isu_id]').val(inv_isu_id);
			MsInvGeneralIsuItem.get(inv_isu_id);
        }
    }
});

