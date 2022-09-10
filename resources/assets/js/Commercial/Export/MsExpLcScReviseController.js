let MsExpLcScReviseModel = require('./MsExpLcScReviseModel');
class MsExpLcScReviseController {
	constructor(MsExpLcScReviseModel)
	{
		this.MsExpLcScReviseModel = MsExpLcScReviseModel;
		this.formId='explcscreviseFrm';
		this.dataTable='#explcscreviseTbl';
		this.route=msApp.baseUrl()+"/explcscrevise"
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
			this.MsExpLcScReviseModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcScReviseModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#explcscreviseFrm  [name=exp_lc_sc_id]').val($('#explcscFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcScReviseModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcScReviseModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explcscreviseTbl').datagrid('reload');
		msApp.resetForm('explcscreviseFrm');
		$('#explcscreviseFrm  [name=exp_lc_sc_id]').val($('#explcscFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpLcScReviseModel.get(index,row);
	}

	showGrid(exp_lc_sc_id)
	{
		let self=this;
        var data={};
		data.exp_lc_sc_id=exp_lc_sc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
            queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpLcScRevise.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsExpLcScRevise=new MsExpLcScReviseController(new MsExpLcScReviseModel());