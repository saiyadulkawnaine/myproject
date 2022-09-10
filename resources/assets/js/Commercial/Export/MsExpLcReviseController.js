let MsExpLcReviseModel = require('./MsExpLcReviseModel');
class MsExpLcReviseController {
	constructor(MsExpLcReviseModel)
	{
		this.MsExpLcReviseModel = MsExpLcReviseModel;
		this.formId='explcreviseFrm';
		this.dataTable='#explcreviseTbl';
		this.route=msApp.baseUrl()+"/explcrevise"
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
			this.MsExpLcReviseModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcReviseModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#explcreviseFrm  [name=exp_lc_sc_id]').val($('#explcFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcReviseModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcReviseModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explcreviseTbl').datagrid('reload');
		msApp.resetForm('explcreviseFrm');
		$('#explcreviseFrm  [name=exp_lc_sc_id]').val($('#explcFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpLcReviseModel.get(index,row);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpLcRevise.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsExpLcRevise=new MsExpLcReviseController(new MsExpLcReviseModel());