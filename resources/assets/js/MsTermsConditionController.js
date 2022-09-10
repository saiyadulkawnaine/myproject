//require('./jquery.easyui.min.js');
let MsTermsConditionModel = require('./MsTermsConditionModel');
require('./datagrid-filter.js');

class MsTermsConditionController {
	constructor(MsTermsConditionModel)
	{
		this.MsTermsConditionModel = MsTermsConditionModel;
		this.formId='termsconditionFrm';
		this.dataTable='#termsconditionTbl';
		this.route=msApp.baseUrl()+"/termscondition"
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
			this.MsTermsConditionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTermsConditionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTermsConditionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTermsConditionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#termsconditionTbl').datagrid('reload');
		$('#termsconditionFrm  [name=id]').val(d.id);
		//$('#teammemberFrm  [name=team_id]').val(d.id);
		//msApp.resetForm('teamFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTermsConditionModel.get(index,row);
		$('#termsconditionFrm  [name=termscondition_id]').val(row.id);
		MsTermsCondition.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsTermsCondition.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsTermsCondition=new MsTermsConditionController(new MsTermsConditionModel());
MsTermsCondition.showGrid();
