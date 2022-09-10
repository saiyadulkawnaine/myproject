//require('./../jquery.easyui.min.js');
let MsAccChartCtrlHeadModel = require('./MsAccChartCtrlHeadModel');
require('./../datagrid-filter.js');
class MsAccChartCtrlHeadController {
	constructor(MsAccChartCtrlHeadModel)
	{
		this.MsAccChartCtrlHeadModel = MsAccChartCtrlHeadModel;
		this.formId='accchartctrlheadFrm';
		this.dataTable='#accchartctrlheadTbl';
		this.route=msApp.baseUrl()+"/accchartctrlhead"
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
			this.MsAccChartCtrlHeadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccChartCtrlHeadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#accchartctrlheadFrm [id="root_id"]').combotree('setValue', 0);
		$('#accchartctrlheadFrm [id="acc_chart_sub_group_id"]').combobox('setValue', '');
		$('#accchartctrlheadFrm [id="retained_earning_account_id"]').combobox('setValue', '');
		$('#accchartctrlheadFrm [id="row_status"]').val(1);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccChartCtrlHeadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartCtrlHeadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accchartctrlheadTbl').datagrid('reload');
		$('#accchartctrlheadFrm [id="root_id"]').combotree('reload');
		MsAccChartCtrlHead.resetForm();
		$('#accchartctrlheadFrm [id="root_id"]').combotree('setValue', 0);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let chart=this.MsAccChartCtrlHeadModel.get(index,row);
		chart.then(function (response) {
			$('#accchartctrlheadFrm [id="root_id"]').combotree('setValue', response.data.fromData.root_id);
			$('#accchartctrlheadFrm [id="retained_earning_account_id"]').combobox('setValue', response.data.fromData.retained_earning_account_id);
			$('#accchartctrlheadFrm [id="acc_chart_sub_group_id"]').combobox('setValue', response.data.fromData.acc_chart_sub_group_id);
			MsAccChartCtrlHead.setClass(response.data.fromData.ctrlhead_type_id);
			MsAccChartCtrlHead.setClassForRetainedEarnings(response.data.fromData.statement_type_id);
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsAccChartCtrlHead.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	ctrlheadtypechange()
	{
		let ctrlhead_type_id=$('#accchartctrlheadFrm [name="ctrlhead_type_id"]').val();
		MsAccChartCtrlHead.setClass(ctrlhead_type_id);
	}

	statementtypechange()
	{
		let statement_type_id=$('#accchartctrlheadFrm [name="statement_type_id"]').val();
		MsAccChartCtrlHead.setClassForRetainedEarnings(statement_type_id);
	}

	setClass(ctrlhead_type_id)
	{
		
		if(ctrlhead_type_id==1 )
		{
			$(".for-coa").addClass("req-text");
		}
		else{
			$(".for-coa").removeClass("req-text");

		}
		
	}

	setClassForRetainedEarnings(statement_type_id)
	{
		
		if(statement_type_id==2 )
		{
			$(".for-income_st").addClass("req-text");
			
		}
		else{
			$(".for-income_st").removeClass("req-text");
		}
		
	}
}
window.MsAccChartCtrlHead=new MsAccChartCtrlHeadController(new MsAccChartCtrlHeadModel());
MsAccChartCtrlHead.showGrid();
$('#utilAccHeadtabs').tabs({
        onSelect:function(title,index){
		   let acc_chart_ctrl_head_id = $('#accchartctrlheadFrm  [name=id]').val();

			var data={};
		    data.acc_chart_ctrl_head_id=acc_chart_ctrl_head_id;

			if(index==1){
				if(acc_chart_ctrl_head_id===''){
					$('#utilAccHeadtabs').tabs('select',0);
					msApp.showError('Select Chart Head First',0);
					return;
			    }
				$('#accchartlocationFrm  [name=acc_chart_ctrl_head_id]').val(acc_chart_ctrl_head_id)
				MsAccChartLocation.create(acc_chart_ctrl_head_id);
			}
            if(index==2){
				if(acc_chart_ctrl_head_id===''){
					$('#utilAccHeadtabs').tabs('select',0);
					msApp.showError('Select Chart Head First',0);
					return;
			    }
				$('#accchartdivisionFrm  [name=acc_chart_ctrl_head_id]').val(acc_chart_ctrl_head_id)
				MsAccChartDivision.create(acc_chart_ctrl_head_id);
            }
            if(index==3){
                if(acc_chart_ctrl_head_id===''){
                    $('#utilAccHeadtabs').tabs('select',0);
                    msApp.showError('Select Chart Head First',0);
                    return;
                }
                $('#accchartdepartmentFrm  [name=acc_chart_ctrl_head_id]').val(acc_chart_ctrl_head_id)
				MsAccChartDepartment.create(acc_chart_ctrl_head_id);
            }
            if(index==4){
                if(acc_chart_ctrl_head_id===''){
                    $('#utilAccHeadtabs').tabs('select',0);
                    msApp.showError('Select Chart Head First',0);
                    return;
                }
                $('#accchartsectionFrm  [name=acc_chart_ctrl_head_id]').val(acc_chart_ctrl_head_id)
				MsAccChartSection.create(acc_chart_ctrl_head_id);
            }
    }
 });

