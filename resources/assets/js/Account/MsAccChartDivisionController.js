let MsAccChartDivisionModel = require('./MsAccChartDivisionModel');
class MsAccChartDivisionController {
	constructor(MsAccChartDivisionModel)
	{
		this.MsAccChartDivisionModel = MsAccChartDivisionModel;
		this.formId='accchartdivisionFrm';
		this.dataTable='#accchartdivisionTbl';
		this.route=msApp.baseUrl()+"/accchartdivision"
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

		let formObj=msApp.get('accchartdivisionFrm');
		let i=1;
		$.each($('#accchartdivisionTbl').datagrid('getChecked'), function (idx, val) {
				formObj['division_id['+i+']']=val.id
				
			i++;
		});
		this.MsAccChartDivisionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var acc_chart_ctrl_head_id=$('#accchartctrlheadFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/accchartdivision/create?acc_chart_ctrl_head_id="+acc_chart_ctrl_head_id);
				data.then(function (response) {
				$('#accchartdivisionTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Division',width:100},
				]],
				});
				
				$('#accchartdivisionsavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Division',width:100},
				{field:'action',title:'',width:60,formatter:MsAccChartDivision.formatDetail},
				]],
				});
				})
				.catch(function (error) {
				console.log(error);
				});
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccChartDivisionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartDivisionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#accchartdivisionTbl').datagrid('reload');
		 MsAccChartDivision.create();
		//msApp.resetForm('accchartlocationFrm');
       //$('#accchartctrlheadFrm  [name=id]').val($('#accchartsubgroupFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccChartDivisionModel.get(index,row);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick=" MsAccChartDivision.delete(event,'+row.acc_chart_division_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window. MsAccChartDivision=new MsAccChartDivisionController(new MsAccChartDivisionModel());
//MsAccChartDivision.showGrid();


