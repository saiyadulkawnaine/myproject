//require('./jquery.easyui.min.js');
let MsBuyerKnitChargeModel = require('./MsBuyerKnitChargeModel');
require('./datagrid-filter.js');

class MsBuyerKnitChargeController {
	constructor(MsBuyerKnitChargeModel)
	{
		this.MsBuyerKnitChargeModel = MsBuyerKnitChargeModel;
		this.formId='buyerknitchargeFrm';
		this.dataTable='#buyerknitchargeTbl';
		this.route=msApp.baseUrl()+"/buyerknitcharge"
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
			this.MsBuyerKnitChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerKnitChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerKnitChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerKnitChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#buyerknitchargeTbl').datagrid('reload');
		//$('#BuyerKnitChargeFrm  [name=id]').val(d.id);
		msApp.resetForm('buyerknitchargeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerKnitChargeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBuyerKnitCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerKnitCharge=new MsBuyerKnitChargeController(new MsBuyerKnitChargeModel());
MsBuyerKnitCharge.showGrid();