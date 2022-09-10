//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsCapacityDistBuyerModel = require('./MsCapacityDistBuyerModel');
class MsCapacityDistBuyerController {
	constructor(MsCapacityDistBuyerModel)
	{
		this.MsCapacityDistBuyerModel = MsCapacityDistBuyerModel;
		this.formId='capacityDistBuyerFrm';
		this.dataTable='#capacityDistBuyerTbl';
		this.route=msApp.baseUrl()+"/capacitydistbuyer"
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
			this.MsCapacityDistBuyerModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCapacityDistBuyerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCapacityDistBuyerModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCapacityDistBuyerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#capacityDistBuyerTbl').datagrid('reload');
		//$('#CapacityDistBuyerFrm  [name=id]').val(d.id);
		msApp.resetForm('capacityDistBuyerFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCapacityDistBuyerModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCapacityDistBuyer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCapacityDistBuyer=new MsCapacityDistBuyerController(new MsCapacityDistBuyerModel());
MsCapacityDistBuyer.showGrid();
