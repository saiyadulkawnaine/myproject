let MsRenewalItemModel = require('./MsRenewalItemModel');
require('./../../datagrid-filter.js');
class MsRenewalItemController {
	constructor(MsRenewalItemModel)
	{
		this.MsRenewalItemModel = MsRenewalItemModel;
		this.formId='renewalitemFrm';
		this.dataTable='#renewalitemTbl';
		this.route=msApp.baseUrl()+"/renewalitem";
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
			this.MsRenewalItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRenewalItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsRenewalItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRenewalItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#renewalitemTbl').datagrid('reload');
		msApp.resetForm('renewalitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRenewalItemModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsRenewalItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsRenewalItem=new MsRenewalItemController(new MsRenewalItemModel());

MsRenewalItem.showGrid();
$('#renewalitemtabs').tabs({
	onSelect:function(title,index){
	   let renewal_item_id = $('#renewalitemFrm  [name=id]').val();

		var data={};
		data.renewal_item_id=renewal_item_id;

		if(index==1){
			if(renewal_item_id===''){
				$('#renewalitemtabs').tabs('select',0);
				msApp.showError('Select Renewal Item First',0);
				return;
			}
			$('#renewalitemdocFrm  [name=renewal_item_id]').val(renewal_item_id)
			MsRenewalItemDoc.showGrid(renewal_item_id);
		}
}
});

