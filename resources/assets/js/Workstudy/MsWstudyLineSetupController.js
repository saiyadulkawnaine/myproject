let MsWstudyLineSetupModel = require('./MsWstudyLineSetupModel');
require('./../datagrid-filter.js');
class MsWstudyLineSetupController {
	constructor(MsWstudyLineSetupModel)
	{
		this.MsWstudyLineSetupModel = MsWstudyLineSetupModel;
		this.formId='wstudylinesetupFrm';
		this.dataTable='#wstudylinesetupTbl';
		this.route=msApp.baseUrl()+"/wstudylinesetup"
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
            this.MsWstudyLineSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsWstudyLineSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsWstudyLineSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWstudyLineSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#wstudylinesetupTbl').datagrid('reload');
		msApp.resetForm('wstudylinesetupFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsWstudyLineSetupModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsWstudyLineSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsWstudyLineSetup = new MsWstudyLineSetupController(new MsWstudyLineSetupModel());
MsWstudyLineSetup.showGrid();

$('#workstudylinetabs').tabs({
	onSelect:function(title,index){
		let wstudy_line_setup_id = $('#wstudylinesetupFrm [name=id]').val();
		let wstudy_line_setup_dtl_id = $('#wstudylinesetupdtlFrm [name=id]').val();
		 
		var data={};
		data.wstudy_line_setup_id=wstudy_line_setup_id;
		if(index==1){
			if(wstudy_line_setup_id===''){
				$('#workstudylinetabs').tabs('select',0);
				msApp.showError('Select a Reference Details First',0);
				return;
			}
			MsWstudyLineSetupLine.resetForm();
			$('#wstudylinesetuplineFrm  [name=wstudy_line_setup_id]').val(wstudy_line_setup_id);
			MsWstudyLineSetupLine.create()
		}
		if(index==2){
			if(wstudy_line_setup_id===''){
				$('#workstudylinetabs').tabs('select',0);
				msApp.showError('Select a Reference Details First',0);
				return;
			}
			MsWstudyLineSetupDtl.resetForm();
			$('#wstudylinesetupdtlFrm [name=wstudy_line_setup_id]').val(wstudy_line_setup_id);
			$('#wstudylinesetupdtlFrm [name=sewing_start_at]').val('08:00:00 AM');
			$('#wstudylinesetupdtlFrm [name=sewing_end_at]').val('05:00:00 PM');
			$('#wstudylinesetupdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
			$('#wstudylinesetupdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
			MsWstudyLineSetupDtl.showGrid(wstudy_line_setup_id);
		}
		if(index==3){
			if(wstudy_line_setup_dtl_id===''){
				$('#workstudylinetabs').tabs('select',2);
				msApp.showError('Select a Reference Details First',2);
				return;
			}
			MsWstudyLineSetupDtlOrd.resetForm();
			$('#wstudylinesetupdtlordFrm [name=wstudy_line_setup_dtl_id]').val(wstudy_line_setup_dtl_id);
			MsWstudyLineSetupDtlOrd.get(wstudy_line_setup_dtl_id);
		}
		if(index==4){
			if(wstudy_line_setup_dtl_id===''){
				$('#workstudylinetabs').tabs('select',2);
				msApp.showError('Select an Engaged Resource Details First',2);
				return;
			}
			MsWstudyLineSetupMinAdj.resetForm();
			$('#wstudylinesetupminadjFrm [name=wstudy_line_setup_dtl_id]').val(wstudy_line_setup_dtl_id);
			MsWstudyLineSetupMinAdj.get(wstudy_line_setup_dtl_id);
		}		
	}
});