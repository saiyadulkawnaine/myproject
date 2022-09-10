let MsAccChartLocationModel = require('./MsAccChartLocationModel');
class MsAccChartLocationController {
	constructor(MsAccChartLocationModel)
	{
		this.MsAccChartLocationModel = MsAccChartLocationModel;
		this.formId='accchartlocationFrm';
		this.dataTable='#accchartlocationTbl';
		this.route=msApp.baseUrl()+"/accchartlocation"
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

		let formObj=msApp.get('accchartlocationFrm');
		let i=1;
		$.each($('#accchartlocationTbl').datagrid('getChecked'), function (idx, val) {
				formObj['location_id['+i+']']=val.id
				
			i++;
		});
		this.MsAccChartLocationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var acc_chart_ctrl_head_id=$('#accchartctrlheadFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/accchartlocation/create?acc_chart_ctrl_head_id="+acc_chart_ctrl_head_id);
				data.then(function (response) {
				$('#accchartlocationTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Location',width:100},
				]],
				});
				
				$('#accchartlocationsavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Location',width:100},
				{field:'action',title:'',width:60,formatter:MsAccChartLocation.formatDetail},
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
		this.MsAccChartLocationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartLocationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#accchartlocationTbl').datagrid('reload');
		 MsAccChartLocation.create();
		//msApp.resetForm('accchartlocationFrm');
       //$('#accchartctrlheadFrm  [name=id]').val($('#accchartsubgroupFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccChartLocationModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick=" MsAccChartLocation.delete(event,'+row.acc_chart_location_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window. MsAccChartLocation=new MsAccChartLocationController(new MsAccChartLocationModel());
//Msaccchartlocation.showGrid();


