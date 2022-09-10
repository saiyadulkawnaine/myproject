//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurFabricModel = require('./MsPurFabricModel');
class MsPurFabricController {
	constructor(MsPurFabricModel)
	{
		this.MsPurFabricModel = MsPurFabricModel;
		this.formId='purfabricFrm';
		this.dataTable='#purfabricTbl';
		this.route=msApp.baseUrl()+"/purfabric"
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
			this.MsPurFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		if(formObj.id){
			this.MsPurFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		this.MsPurFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsPurFabric.fabricSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderfabricFrm  [name=id]').val()
		MsPurFabric.get(purchase_order_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurFabricModel.get(index,row);
	}
	get(purchase_order_id){
		let data= axios.get(this.route+"?purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#purfabricTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#purfabricTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPurFabricQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(id)
	{
		$('#budgetfabricsearchTbl').datagrid('loadData', []);
		let company_id=$('#purorderfabricFrm  [name=company_id]').val();
		$('#budgetfabricsearchFrm  [name=company_id]').val(company_id);
		$('#importfabricWindow').window('open');
		//$('#budgetfabricsearchTbl').datagrid();
		//MsPurFabric.fabricSearchGrid({rows :{}})
	}
	searchFabric(){
		let company_id=$('#budgetfabricsearchFrm  [name=company_id]').val();
		let budget_id=$('#budgetfabricsearchFrm  [name=budget_id]').val();
		let job_no=$('#budgetfabricsearchFrm  [name=job_no]').val();
		let purchase_order_id=$('#purorderfabricFrm  [name=id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&purchase_order_id="+purchase_order_id)
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
		formObj.purchase_order_id=$('#purorderfabricFrm  [name=id]').val();
		let i=1;
		$.each($('#budgetfabricsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_fabric_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
}
window.MsPurFabric=new MsPurFabricController(new MsPurFabricModel());
$('#budgetfabricsearchTbl').datagrid();
MsPurFabric.fabricSearchGrid({rows:{}});
$('#purfabricTbl').datagrid();
MsPurFabric.showGrid({rows :{}});
