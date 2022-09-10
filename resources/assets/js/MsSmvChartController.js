//require('./jquery.easyui.min.js');
let MsSmvChartModel = require('./MsSmvChartModel');
require('./datagrid-filter.js');
class MsSmvChartController {
	constructor(MsSmvChartModel)
	{
		this.MsSmvChartModel = MsSmvChartModel;
		this.formId='smvchartFrm';
		this.dataTable='#smvchartTbl';
		this.route=msApp.baseUrl()+"/smvchart"
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
			this.MsSmvChartModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmvChartModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmvChartModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmvChartModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smvchartTbl').datagrid('reload');
		//$('#SmvChartFrm  [name=id]').val(d.id);
		msApp.resetForm('smvchartFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmvChartModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			filterBtnIconCls:'icon-filter',
			singleSelect:true,
			fit:true,
			fitColumns:true,
			striped:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter',[{
				field:'sew_target_per_hour',
				type:'numberbox',
				options:{precision:0},
				op:['equal','notequal','less','greater']
			}]);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmvChart.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculateSewTargetPerHour (){
		let gmt_smv=$("#gmt_smv").val();
		let man_power_line=$("#man_power_line").val();
		let sew_efficiency_per=$("#sew_efficiency_per").val();
		sew_efficiency_per=sew_efficiency_per/100;
		if(gmt_smv && man_power_line && sew_efficiency_per){
		let TargetPerHour=Math.round((man_power_line*60*sew_efficiency_per)/(gmt_smv));
		return TargetPerHour;
		}else{
			return 0;
		}

	}
	setSewTargetPerHour(){
		let gmt_smv=$("#sew_target_per_hour").val(this.calculateSewTargetPerHour ());
	}
}
window.MsSmvChart=new MsSmvChartController(new MsSmvChartModel());
MsSmvChart.showGrid();
