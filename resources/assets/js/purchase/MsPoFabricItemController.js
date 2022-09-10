//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoFabricItemModel = require('./MsPoFabricItemModel');
class MsPoFabricItemController {
	constructor(MsPoFabricItemModel)
	{
		this.MsPoFabricItemModel = MsPoFabricItemModel;
		this.formId='pofabricitemFrm';
		this.dataTable='#pofabricitemTbl';
		this.route=msApp.baseUrl()+"/pofabricitem"
	}

	submit()
	{
		/*$.blockUI({
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
		});*/	
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsPoFabricItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoFabricItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	submitAndClose()
	{
		/*$.blockUI({
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
		});	*/
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsPoFabricItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoFabricItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			$('#importfabricWindow').window('close');
			$('#budgetfabricsearchTbl').datagrid('loadData', []);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoFabricItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoFabricItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		let po_fabric_id=$('#pofabricFrm  [name=id]').val()
		MsPoFabricItem.get(po_fabric_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoFabricItemModel.get(index,row);
	}
	get(po_fabric_id){
		let data= axios.get(this.route+"?po_fabric_id="+po_fabric_id)
		.then(function (response) {
			$('#pofabricitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#pofabricitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPoFabricItemQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(id)
	{
		$('#budgetfabricsearchTbl').datagrid('loadData', []);
		let company_id=$('#pofabricFrm  [name=company_id]').val();
		$('#budgetfabricsearchFrm  [name=company_id]').val(company_id);
		$('#importfabricWindow').window('open');
		//$('#budgetfabricsearchTbl').datagrid();
		//MsPurFabric.fabricSearchGrid({rows :{}})
	}
	searchFabric(){
		let company_id=$('#budgetfabricsearchFrm  [name=company_id]').val();
		let budget_id=$('#budgetfabricsearchFrm  [name=budget_id]').val();
		let job_no=$('#budgetfabricsearchFrm  [name=job_no]').val();
		let po_fabric_id=$('#pofabricFrm  [name=id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&po_fabric_id="+po_fabric_id)
		.then(function (response) {
			$('#budgetfabricsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	fabricSearchGrid(data)
	{
		var dg = $('#budgetfabricsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
		//dg.datagrid('loadData', data);

	}
	getSelections()
	{
		let formObj={};
		formObj.po_fabric_id=$('#pofabricFrm  [name=id]').val();
		let i=1;
		$.each($('#budgetfabricsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_fabric_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
}
window.MsPoFabricItem=new MsPoFabricItemController(new MsPoFabricItemModel());
MsPoFabricItem.fabricSearchGrid([]);
MsPoFabricItem.showGrid([]);
