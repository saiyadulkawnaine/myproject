require('./../datagrid-filter.js');
let MsPoKnitServiceItemModel = require('./MsPoKnitServiceItemModel');
class MsPoKnitServiceItemController {
	constructor(MsPoKnitServiceItemModel)
	{
		this.MsPoKnitServiceItemModel = MsPoKnitServiceItemModel;
		this.formId='poknitserviceitemFrm';
		this.dataTable='#poknitserviceitemTbl';
		this.route=msApp.baseUrl()+"/poknitserviceitem"
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
			this.MsPoKnitServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoKnitServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
			this.MsPoKnitServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsPoKnitServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoKnitServiceItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoKnitServiceItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_knit_service_id=$('#poknitserviceFrm  [name=id]').val()
		MsPoKnitServiceItem.get(po_knit_service_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoKnitServiceItemModel.get(index,row);
	}
	get(po_knit_service_id){
		let data= axios.get(this.route+"?po_knit_service_id="+po_knit_service_id)
		.then(function (response) {
			$('#poknitserviceitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#poknitserviceitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			nowrap:false,
			showFooter: 'true',
			emptyMsg:'No Record Found',
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceItemQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow()
	{
		$('#poknitserviceitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#poknitserviceFrm  [name=company_id]').val();
		$('#poknitserviceitemsearchFrm  [name=company_id]').val(company_id);
		$('#poknitserviceitemimportWindow').window('open');
	}
	searchFabric(){
		let company_id=$('#poknitserviceFrm  [name=company_id]').val();
		let budget_id=$('#poknitserviceitemsearchFrm  [name=budget_id]').val();
		let job_no=$('#poknitserviceitemsearchFrm  [name=job_no]').val();
		let style_ref=$('#poknitserviceitemsearchFrm  [name=style_ref]').val();
		let po_knit_service_id=$('#poknitserviceFrm  [name=id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&style_ref="+style_ref+"&po_knit_service_id="+po_knit_service_id)
		.then(function (response) {
			$('#poknitserviceitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	fabricSearchGrid(data)
	{
		var dg = $('#poknitserviceitemsearchTbl');
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
		formObj.po_knit_service_id=$('#poknitserviceFrm  [name=id]').val();
		let i=1;
		$.each($('#poknitserviceitemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_fabric_prod_id['+i+']']=val.id
			i++;
		});
		$('#poknitserviceitemsearchTbl').datagrid('clearChecked');
		$('#poknitserviceitemimportWindow').window('close');
		return formObj;
	}
}
window.MsPoKnitServiceItem=new MsPoKnitServiceItemController(new MsPoKnitServiceItemModel());
MsPoKnitServiceItem.fabricSearchGrid([]);
MsPoKnitServiceItem.showGrid([]);