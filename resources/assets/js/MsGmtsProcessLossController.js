//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsGmtsProcessLossModel = require('./MsGmtsProcessLossModel');
class MsGmtsProcessLossController {
	constructor(MsGmtsProcessLossModel)
	{
		this.MsGmtsProcessLossModel = MsGmtsProcessLossModel;
		this.formId='gmtsprocesslossFrm';
		this.dataTable='#gmtsprocesslossTbl';
		this.route=msApp.baseUrl()+"/gmtsprocessloss"
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
			this.MsGmtsProcessLossModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtsProcessLossModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtsProcessLossModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtsProcessLossModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtsprocesslossTbl').datagrid('reload');
		msApp.resetForm('gmtsprocesslossFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsGmtsProcessLossModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsGmtsProcessLoss.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsGmtsProcessLoss=new MsGmtsProcessLossController(new MsGmtsProcessLossModel());
MsGmtsProcessLoss.showGrid();
$('#UtilGmtProcessLosstabs').tabs({
    onSelect:function(title,index){
        let gmts_process_loss_id = $('#gmtsprocesslossFrm  [name=id]').val();
        
        var data={};
		data.gmts_process_loss_id=gmts_process_loss_id;
        if(index==1){
			if(gmts_process_loss_id===''){
				$('#UtilGmtProcessLosstabs').tabs('select',0);
				msApp.showError('Select A Gmt Process loss First',0);
				return;
			}
			$('#gmtsprocesslossperFrm  [name=gmts_process_loss_id]').val(gmts_process_loss_id);
			MsGmtsProcessLossPer.showGrid(gmts_process_loss_id);
		}
    }
});