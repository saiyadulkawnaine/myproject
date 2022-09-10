require('./../datagrid-filter.js');
let MsPoEmbServiceItemModel = require('./MsPoEmbServiceItemModel');
class MsPoEmbServiceItemController {
	constructor(MsPoEmbServiceItemModel)
	{
		this.MsPoEmbServiceItemModel = MsPoEmbServiceItemModel;
		this.formId='poembserviceitemFrm';
		this.dataTable='#poembserviceitemTbl';
		this.route=msApp.baseUrl()+"/poembserviceitem"
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
			this.MsPoEmbServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoEmbServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
			this.MsPoEmbServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsPoEmbServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoEmbServiceItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoEmbServiceItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_emb_service_id=$('#poembserviceFrm  [name=id]').val()
		MsPoEmbServiceItem.get(po_emb_service_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoEmbServiceItemModel.get(index,row);
	}
	get(po_emb_service_id){
		let data= axios.get(this.route+"?po_emb_service_id="+po_emb_service_id)
		.then(function (response) {
			$('#poembserviceitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#poembserviceitemTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceItemQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow()
	{
		$('#poembserviceitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#poembserviceFrm  [name=company_id]').val();
		$('#poembserviceitemsearchFrm  [name=company_id]').val(company_id);
		$('#poembserviceitemimportWindow').window('open');
	}
	searchFabric(){
		let company_id=$('#poembserviceFrm  [name=company_id]').val();
		let budget_id=$('#poembserviceitemsearchFrm  [name=budget_id]').val();
		let job_no=$('#poembserviceitemsearchFrm  [name=job_no]').val();
		let style_ref=$('#poembserviceitemsearchFrm  [name=style_ref]').val();
		let po_emb_service_id=$('#poembserviceFrm  [name=id]').val();
		let production_area_id=$('#poembserviceFrm  [name=production_area_id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&style_ref="+style_ref+"&po_emb_service_id="+po_emb_service_id+"&production_area_id="+production_area_id)
		.then(function (response) {
			$('#poembserviceitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	fabricSearchGrid(data)
	{
		var dg = $('#poembserviceitemsearchTbl');
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
		formObj.po_emb_service_id=$('#poembserviceFrm  [name=id]').val();
		let i=1;
		$.each($('#poembserviceitemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_emb_id['+i+']']=val.id
			i++;
		});
		$('#poembserviceitemsearchTbl').datagrid('clearChecked');
		$('#poembserviceitemimportWindow').window('close');
		return formObj;
	}
}
window.MsPoEmbServiceItem=new MsPoEmbServiceItemController(new MsPoEmbServiceItemModel());
MsPoEmbServiceItem.fabricSearchGrid([]);
MsPoEmbServiceItem.showGrid([]);