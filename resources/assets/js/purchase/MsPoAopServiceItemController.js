require('./../datagrid-filter.js');
let MsPoAopServiceItemModel = require('./MsPoAopServiceItemModel');
class MsPoAopServiceItemController {
	constructor(MsPoAopServiceItemModel)
	{
		this.MsPoAopServiceItemModel = MsPoAopServiceItemModel;
		this.formId='poaopserviceitemFrm';
		this.dataTable='#poaopserviceitemTbl';
		this.route=msApp.baseUrl()+"/poaopserviceitem"
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
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsPoAopServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoAopServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	submitAndClose()
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
		let formObj=this.getSelections();
		if(formObj.id)
		{
			this.MsPoAopServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsPoAopServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoAopServiceItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoAopServiceItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_aop_service_id=$('#poaopserviceFrm  [name=id]').val()
		MsPoAopServiceItem.get(po_aop_service_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoAopServiceItemModel.get(index,row);
	}
	get(po_aop_service_id){
		let data= axios.get(this.route+"?po_aop_service_id="+po_aop_service_id)
		.then(function (response) {
			$('#poaopserviceitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#poaopserviceitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			nowrap:false,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceItemQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow()
	{
		$('#poaopserviceitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#poaopserviceFrm  [name=company_id]').val();
		$('#poaopserviceitemsearchFrm  [name=company_id]').val(company_id);
		$('#poaopserviceitemimportWindow').window('open');
	}
	searchFabric(){
		let company_id=$('#poaopserviceFrm  [name=company_id]').val();
		let budget_id=$('#poaopserviceitemsearchFrm  [name=budget_id]').val();
		let job_no=$('#poaopserviceitemsearchFrm  [name=job_no]').val();
		let style_ref=$('#poaopserviceitemsearchFrm  [name=style_ref]').val();
		let po_aop_service_id=$('#poaopserviceFrm  [name=id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&style_ref="+style_ref+"&po_aop_service_id="+po_aop_service_id)
		.then(function (response) {
			$('#poaopserviceitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	fabricSearchGrid(data)
	{
		var dg = $('#poaopserviceitemsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	getSelections()
	{
		let formObj={};
		formObj.po_aop_service_id=$('#poaopserviceFrm  [name=id]').val();
		let i=1;
		$.each($('#poaopserviceitemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_fabric_prod_id['+i+']']=val.id
			i++;
		});
		$('#poaopserviceitemsearchTbl').datagrid('clearChecked');
		$('#poaopserviceitemimportWindow').window('close');
		return formObj;
	}
}
window.MsPoAopServiceItem=new MsPoAopServiceItemController(new MsPoAopServiceItemModel());
MsPoAopServiceItem.fabricSearchGrid([]);
MsPoAopServiceItem.showGrid([]);