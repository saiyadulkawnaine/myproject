//require('./../jquery.easyui.min.js');
let MsAccChartSubGroupModel = require('./MsAccChartSubGroupModel');
require('./../datagrid-filter.js');
class MsAccChartSubGroupController {
	constructor(MsAccChartSubGroupModel)
	{
		this.MsAccChartSubGroupModel = MsAccChartSubGroupModel;
		this.formId='accchartsubgroupFrm';
		this.dataTable='#accchartsubgroupTbl';
		this.route=msApp.baseUrl()+"/accchartsubgroup"
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
			this.MsAccChartSubGroupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccChartSubGroupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}	
	
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccChartSubGroupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartSubGroupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accchartsubgroupTbl').datagrid('reload');
		//MsAccChartSubGroup.showGrid();
	
		msApp.resetForm('accchartsubgroupFrm');
	}

	edit(index,row)
	{
        row.route=this.route;
		row.formId=this.formId;
		this.MsAccChartSubGroupModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccChartSubGroup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAccChartSubGroup=new MsAccChartSubGroupController(new MsAccChartSubGroupModel());
MsAccChartSubGroup.showGrid();
//$('#utilAccSubtabs').tabs({
//        onSelect:function(title,index){
//		   let acc_chart_sub_group_id = $('#accchartsubgroupFrm  [name=id]').val();
//
//			var data={};
//		    data.acc_chart_sub_group_id=acc_chart_sub_group_id;
//
//			if(index==1){
//				if(acc_chart_sub_group_id===''){
//					$('#utilAccSubtabs').tabs('select',0);
//					msApp.showError('Select Sub Group First',0);
//					return;
//			    }
//				$('#accchartctrlheadFrm  [name=acc_chart_sub_group_id]').val(acc_chart_sub_group_id)
//				MsAccChartCtrlHead.showGrid(acc_chart_sub_group_id);
//			}
//    }
// });
