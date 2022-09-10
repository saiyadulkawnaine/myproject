//require('./../jquery.easyui.min.js');
let MsAccChartCtrlHeadMappingModel = require('./MsAccChartCtrlHeadMappingModel');
require('./../datagrid-filter.js');
class MsAccChartCtrlHeadMappingController {
	constructor(MsAccChartCtrlHeadMappingModel)
	{
		this.MsAccChartCtrlHeadMappingModel = MsAccChartCtrlHeadMappingModel;
		this.formId='accchartctrlheadmappingFrm';
		this.dataTable='#accchartctrlheadmappingTbl';
		this.route=msApp.baseUrl()+"/accchartctrlheadmapping"
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
			this.MsAccChartCtrlHeadMappingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccChartCtrlHeadMappingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccChartCtrlHeadMappingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartCtrlHeadMappingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accchartctrlheadmappingTbl').datagrid('reload');
		MsAccChartCtrlHeadMapping.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccChartCtrlHeadMappingModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAccChartCtrlHeadMapping.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetHeadWindow()
	{
		let data= axios.get(msApp.baseUrl()+"/accchartctrlheadmapping/getassethead");
		data.then(function (response) {
			$('#assetheadTbl').datagrid('loadData', response.data);
			$('#assetheadwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showAssetHeadGrid(data)
	{
		var dg = $('#assetheadTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 	$('#accchartctrlheadmappingFrm  [name=asset_head_name]').val(row.asset_head_name);
			 	$('#accchartctrlheadmappingFrm  [name=acc_chart_ctrl_head_id]').val(row.id);
			 	$('#assetheadwindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	openAccumulatedHeadWindow(){
		let adata= axios.get(msApp.baseUrl()+"/accchartctrlheadmapping/getaccumulatedhead");
		adata.then(function (response) {
			$('#accumulatedheadTbl').datagrid('loadData', response.data);
			$('#accumulatedheadwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showAccumulatedHeadGrid(data)
	{
		var sdg = $('#accumulatedheadTbl');
		sdg.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 	$('#accchartctrlheadmappingFrm  [name=accumulate_head_name]').val(row.accumulate_head_name);
			 	$('#accchartctrlheadmappingFrm  [name=acc_acumulate_ctrl_head_id]').val(row.id);
			 	$('#accumulatedheadwindow').window('close');
			}
		});
		sdg.datagrid('enableFilter').datagrid('loadData', data);
	}


}
window.MsAccChartCtrlHeadMapping=new MsAccChartCtrlHeadMappingController(new MsAccChartCtrlHeadMappingModel());
MsAccChartCtrlHeadMapping.showGrid();
MsAccChartCtrlHeadMapping.showAssetHeadGrid([]);
MsAccChartCtrlHeadMapping.showAccumulatedHeadGrid([]);