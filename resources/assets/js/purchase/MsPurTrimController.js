//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurTrimModel = require('./MsPurTrimModel');
class MsPurTrimController {
	constructor(MsPurTrimModel)
	{
		this.MsPurTrimModel = MsPurTrimModel;
		this.formId='purtrimFrm';
		this.dataTable='#purtrimTbl';
		this.route=msApp.baseUrl()+"/purtrim"
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
			this.MsPurTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			
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
			this.MsPurTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			$('#importtrimWindow').window('close');
			$('#budgettrimsearchTbl').datagrid('loadData', []);
			 
			
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsPurTrim.trimSearchGrid({rows :{}})
		let purchase_order_id=$('#purordertrimFrm  [name=id]').val()
		MsPurTrim.get(purchase_order_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurTrimModel.get(index,row);
	}
	get(purchase_order_id){
		
		let data= axios.get(this.route+"?purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#purtrimTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#purtrimTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPurTrimQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(id)
	{
		$('#budgettrimsearchTbl').datagrid('loadData', []);
		let company_id=$('#purordertrimFrm  [name=company_id]').val();
		$('#budgettrimsearchFrm  [name=company_id]').val(company_id);
		$('#importtrimWindow').window('open');
	}
	searchTrim(){
		let company_id=$('#budgettrimsearchFrm  [name=company_id]').val();
		let budget_id=$('#budgettrimsearchFrm  [name=budget_id]').val();
		let job_no=$('#budgettrimsearchFrm  [name=job_no]').val();
		let purchase_order_id=$('#purordertrimFrm  [name=id]').val();
		let data= axios.get(this.route+"/importtrim"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#budgettrimsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	trimSearchGrid(data)
	{
		var dg = $('#budgettrimsearchTbl');
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
		formObj.purchase_order_id=$('#purordertrimFrm  [name=id]').val();
		let i=1;
		$.each($('#budgettrimsearchTbl').datagrid('getSelections'), function (idx, val) {
				formObj['budget_trim_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
	
}
window.MsPurTrim=new MsPurTrimController(new MsPurTrimModel());

$('#budgettrimsearchTbl').datagrid();
MsPurTrim.trimSearchGrid({rows:{}})
$('#purtrimTbl').datagrid();
MsPurTrim.showGrid({rows :{}})
