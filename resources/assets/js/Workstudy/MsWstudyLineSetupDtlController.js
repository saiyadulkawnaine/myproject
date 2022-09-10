let MsWstudyLineSetupDtlModel = require('./MsWstudyLineSetupDtlModel');

class MsWstudyLineSetupDtlController {
	constructor(MsWstudyLineSetupDtlModel)
	{
		this.MsWstudyLineSetupDtlModel = MsWstudyLineSetupDtlModel;
		this.formId='wstudylinesetupdtlFrm';
		this.dataTable='#wstudylinesetupdtlTbl';
		this.route=msApp.baseUrl()+"/wstudylinesetupdtl"
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
			this.MsWstudyLineSetupDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWstudyLineSetupDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#wstudylinesetupdtlFrm [name=wstudy_line_setup_id]').val($('#wstudylinesetupFrm [name=id]').val());
		$('#wstudylinesetupdtlFrm [name=sewing_start_at]').val('08:00:00 AM');
		$('#wstudylinesetupdtlFrm [name=sewing_end_at]').val('05:00:00 PM');
		$('#wstudylinesetupdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
		$('#wstudylinesetupdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWstudyLineSetupDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWstudyLineSetupDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#wstudylinesetupdtlTbl').datagrid('reload');
		msApp.resetForm('wstudylinesetupdtlFrm');
		$('#wstudylinesetupdtlFrm [name=wstudy_line_setup_id]').val($('#wstudylinesetupFrm [name=id]').val());
		$('#wstudylinesetupdtlFrm [name=sewing_start_at]').val('08:00:00 AM');
		$('#wstudylinesetupdtlFrm [name=sewing_end_at]').val('05:00:00 PM');
		$('#wstudylinesetupdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
		$('#wstudylinesetupdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsWstudyLineSetupDtlModel.get(index,row);

	}

	showGrid(wstudy_line_setup_id)
	{
		let self=this;
		var data={};
		data.wstudy_line_setup_id=wstudy_line_setup_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	//////////////////////////////////
	openLineSetupStyleRefWindow(){
		$('#openlinesetupstylerefwindow').window('open');
	}
	searchLineSetupStyleRefGrid(){
		let data={};
		data.company_id=$('#linesetupstylerefsearchFrm [name=company_id]').val();
		data.buyer_id=$('#linesetupstylerefsearchFrm [name=buyer_id]').val();
		data.job_no=$('#linesetupstylerefsearchFrm [name=job_no]').val();
		data.style_ref=$('#linesetupstylerefsearchFrm [name=style_ref]').val();
		let self = this;
		$('#linesetupstylerefsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			queryParams:data,
			//showFilterBar:true,
			url:msApp.baseUrl()+"/wstudylinesetupdtl/linesetupstyleref",
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#wstudylinesetupdtlFrm [name=style_id]').val(row.id);
				$('#wstudylinesetupdtlFrm [name=style_ref]').val(row.style_ref);
				$('#openlinesetupstylerefwindow').window('close');
			}
		}).datagrid('enableFilter');
	}
	//////////////

	calculateTotalmnt(){
		let self = this;
		let operator;
		let helper;
		let working_hour;
		let overtime_hour;
		operator=($('#wstudylinesetupdtlFrm [name=operator]').val())*1;
		helper=($('#wstudylinesetupdtlFrm [name=helper]').val())*1;
		working_hour=($('#wstudylinesetupdtlFrm [name=working_hour]').val())*1;
		overtime_hour=($('#wstudylinesetupdtlFrm [name=overtime_hour]').val())*1;
		let total_mnt=(operator+helper)*(working_hour+overtime_hour)*60;
		$('#wstudylinesetupdtlFrm [name=total_mnt]').val(total_mnt);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsWstudyLineSetupDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsWstudyLineSetupDtl=new MsWstudyLineSetupDtlController(new MsWstudyLineSetupDtlModel());