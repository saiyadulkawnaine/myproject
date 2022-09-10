//require('./jquery.easyui.min.js');
let MsKnitChargeModel = require('./MsKnitChargeModel');
require('./datagrid-filter.js');

class MsKnitChargeController {
	constructor(MsKnitChargeModel)
	{
		this.MsKnitChargeModel = MsKnitChargeModel;
		this.formId='knitchargeFrm';
		this.dataTable='#knitchargeTbl';
		this.route=msApp.baseUrl()+"/knitcharge"
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
			this.MsKnitChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsKnitChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsKnitChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsKnitChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#knitchargeTbl').datagrid('reload');
		$('#knitchargeFrm  [name=id]').val(d.id);
		msApp.resetForm('buyerknitchargeFrm');
		msApp.resetForm('knitchargesupplierFrm');
	  $('#buyerknitchargeFrm  [name=knit_charge_id]').val(d.id);
	  $('#knitchargesupplierFrm  [name=knit_charge_id]').val(d.id);
		//msApp.resetForm('knitchargeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsKnitChargeModel.get(index,row);
		msApp.resetForm('buyerknitchargeFrm');
		msApp.resetForm('knitchargesupplierFrm');
		$('#buyerknitchargeFrm  [name=knit_charge_id]').val(row.id);
	  $('#knitchargesupplierFrm  [name=knit_charge_id]').val(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsKnitCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsKnitCharge=new MsKnitChargeController(new MsKnitChargeModel());
MsKnitCharge.showGrid();
