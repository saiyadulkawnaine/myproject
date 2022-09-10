let MsAccPeriodModel = require('./MsAccPeriodModel');
class MsAccPeriodController {
	constructor(MsAccPeriodModel)
	{
		this.MsAccPeriodModel = MsAccPeriodModel;
		this.formId='accperiodFrm';
		this.dataTable='#accperiodTbl';
		this.route=msApp.baseUrl()+"/accperiod"
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
			this.MsAccPeriodModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccPeriodModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccPeriodModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccPeriodModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accperiodTbl').datagrid('reload');
		//MsAccPeriod.create()
		msApp.resetForm('accperiodFrm');
		$('#accyearFrm  [name=acc_year_id]').val(acc_year_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccPeriodModel.get(index,row);
	}

	showGrid(acc_year_id)
	{
		let self=this;
		var data={};
		data.acc_year_id=acc_year_id;
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
		return '<a href="javascript:void(0)"  onClick="MsAccPeriod.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAccPeriod = new MsAccPeriodController(new MsAccPeriodModel());

