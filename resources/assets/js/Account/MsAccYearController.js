//require('./../jquery.easyui.min.js');
let MsAccYearModel = require('./MsAccYearModel');
require('./../datagrid-filter.js');
class MsAccYearController {
	constructor(MsAccYearModel)
	{
		this.MsAccYearModel = MsAccYearModel;
		this.formId='accyearFrm';
		this.dataTable='#accyearTbl';
		this.route=msApp.baseUrl()+"/accyear"
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
			this.MsAccYearModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccYearModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#accyearFrm  [name=is_open]').val(1);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccYearModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccYearModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accyearTbl').datagrid('reload');
		msApp.resetForm('accyearFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccYearModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccYear.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	checkisFirst(value){
		let dd=msApp.firstDate(value);
		alert(dd);

	}
}
window.MsAccYear=new MsAccYearController(new MsAccYearModel());
MsAccYear.showGrid()
$('#acctabs').tabs({
    onSelect:function(title,index){
        let acc_year_id = $('#accyearFrm [name=id]').val();
        
        var data={};
		    data.acc_year_id=acc_year_id;
        if(index==1){
				if(acc_year_id===''){
					$('#acctabs').tabs('select',0);
					msApp.showError('Select Accounting Year First',0);
					return;
			    }
				$('#accperiodFrm  [name=acc_year_id]').val(acc_year_id);
				MsAccPeriod.showGrid(acc_year_id);
            }
    }
});

