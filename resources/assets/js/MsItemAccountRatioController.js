//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsItemAccountRatioModel = require('./MsItemAccountRatioModel');
class MsItemAccountRatioController {
	constructor(MsItemAccountRatioModel)
	{
		this.MsItemAccountRatioModel = MsItemAccountRatioModel;
		this.formId='itemaccountratioFrm';
		this.dataTable='#itemaccountratioTbl';
		this.route=msApp.baseUrl()+"/itemaccountratio"
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

		let item_account_id=$('#itemaccountFrm  [name=id]').val()
		if(item_account_id==""){
			alert("Select Item Account")
			return;
		}
		let formObj=msApp.get(this.formId);
		formObj['item_account_id']=item_account_id;
		if(formObj.id){
			this.MsItemAccountRatioModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemAccountRatioModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemAccountRatioModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemAccountRatioModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#itemaccountratioTbl').datagrid('reload');
		$('#itemaccountratioFrm  [name=id]').val('');
		$('#itemaccountratioFrm  [name=composition_id]').val('');
		$('#itemaccountratioFrm  [name=ratio]').val('');
		//msApp.resetForm('itemaccountratioFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemAccountRatioModel.get(index,row);
	}

	showGrid(item_account_id)
	{
		let self=this;
		var data={};
		data.item_account_id=item_account_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsItemAccountRatio.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsItemAccountRatio=new MsItemAccountRatioController(new MsItemAccountRatioModel());
