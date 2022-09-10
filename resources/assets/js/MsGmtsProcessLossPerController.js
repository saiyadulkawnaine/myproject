let MsGmtsProcessLossPerModel = require('./MsGmtsProcessLossPerModel');
class MsGmtsProcessLossPerController {
	constructor(MsGmtsProcessLossPerModel)
	{
		this.MsGmtsProcessLossPerModel = MsGmtsProcessLossPerModel;
		this.formId='gmtsprocesslossperFrm';
		this.dataTable='#gmtsprocesslossperTbl';
		this.route=msApp.baseUrl()+"/gmtsprocesslossper"
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
			this.MsGmtsProcessLossPerModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtsProcessLossPerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#gmtsprocesslossperFrm  [name=gmts_process_loss_id]').val($('#gmtsprocesslossFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtsProcessLossPerModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtsProcessLossPerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtsprocesslossperTbl').datagrid('reload');
		msApp.resetForm('gmtsprocesslossperFrm');
		$('#gmtsprocesslossperFrm  [name=gmts_process_loss_id]').val($('#gmtsprocesslossFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsGmtsProcessLossPerModel.get(index,row);
	}

	showGrid(gmts_process_loss_id)
	{
		var data={};
		data.gmts_process_loss_id=gmts_process_loss_id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsGmtsProcessLossPer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsGmtsProcessLossPer=new MsGmtsProcessLossPerController(new MsGmtsProcessLossPerModel());