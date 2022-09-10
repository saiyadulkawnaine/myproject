let MsInvGeneralIsuRqModel = require('./MsInvGeneralIsuRqModel');
require('./../../datagrid-filter.js');
class MsInvGeneralIsuRqController {
	constructor(MsInvGeneralIsuRqModel)
	{
		this.MsInvGeneralIsuRqModel = MsInvGeneralIsuRqModel;
		this.formId='invgeneralisurqFrm';
		this.dataTable='#invgeneralisurqTbl';
		this.route=msApp.baseUrl()+"/invgeneralisurq"
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
			this.MsInvGeneralIsuRqModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuRqModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuRqModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuRqModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneralisurqTbl').datagrid('reload');
		msApp.resetForm('invgeneralisurqFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralIsuRqModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgeneralisurqitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsuRq.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneralisurqFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralIsuRq=new MsInvGeneralIsuRqController(new MsInvGeneralIsuRqModel());
MsInvGeneralIsuRq.showGrid();

$('#invgeneralisurqtabs').tabs({
    onSelect:function(title,index){
        let inv_general_isu_rq_id = $('#invgeneralisurqFrm [name=id]').val();
        var data={};
		data.inv_general_isu_rq_id=inv_general_isu_rq_id;
        if(index==1){
			if(inv_general_isu_rq_id===''){
				$('#invyarnisurqtabs').tabs('select',0);
				msApp.showError('Select Master Entry First',0);
				return;
		    }
			$('#invgeneralisurqitemFrm  [name=inv_general_isu_rq_id]').val(inv_general_isu_rq_id);
			MsInvGeneralIsuRqItem.get(inv_general_isu_rq_id);
        }
    }
});

