require('./../datagrid-filter.js');
let MsPoTrimItemModel = require('./MsPoTrimItemModel');
class MsPoTrimItemController {
	constructor(MsPoTrimItemModel)
	{
		this.MsPoTrimItemModel = MsPoTrimItemModel;
		this.formId='potrimitemFrm';
		this.dataTable='#potrimitemTbl';
		this.route=msApp.baseUrl()+"/potrimitem"
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
			this.MsPoTrimItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoTrimItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
			this.MsPoTrimItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoTrimItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			//$('#potrimitemimportWindow').window('close');
			//$('#potrimitemsearchTbl').datagrid('loadData', []);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		return;
		let formObj=msApp.get(this.formId);
		this.MsPoTrimItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		return;
		event.stopPropagation()
		this.MsPoTrimItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#potrimitemsearchTbl').datagrid('loadData', []);
		let po_trim_id=$('#potrimFrm  [name=id]').val()
		MsPoTrimItem.get(po_trim_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoTrimItemModel.get(index,row);
	}
	get(po_trim_id){
		let data= axios.get(this.route+"?po_trim_id="+po_trim_id)
		.then(function (response) {
			$('#potrimitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#potrimitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			showFooter: 'true',
			emptyMsg:'No Record Found',
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
			return '<a href="javascript:void(0)"  onClick="MsPoTrimItemQty.openQtyWindow('+row.id+','+'\''+row.item_account+'\''+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(id)
	{
		$('#potrimitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#potrimFrm  [name=company_id]').val();
		$('#potrimitemsearchFrm  [name=company_id]').val(company_id);
		$('#potrimitemimportWindow').window('open');
	}
	searchTrim(){
        //let buyer_id=$('#potrimFrm  [name=buyer_id]').val();
        let style_ref=$('#potrimitemsearchFrm  [name=style_ref]').val();
		let job_no=$('#potrimitemsearchFrm  [name=job_no]').val();
		let budget_id=$('#potrimitemsearchFrm  [name=budget_id]').val();
		let company_id=$('#potrimitemsearchFrm  [name=company_id]').val();
		let po_trim_id=$('#potrimFrm  [name=id]').val();
		let data= axios.get(this.route+"/importtrim"+"?po_trim_id="+po_trim_id+"&company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&style_ref="+style_ref)
		.then(function (response) {
			$('#potrimitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	trimSearchGrid(data)
	{
		var dg = $('#potrimitemsearchTbl');
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
		formObj.po_trim_id=$('#potrimFrm  [name=id]').val();
		let i=1;
		$.each($('#potrimitemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_trim_id['+i+']']=val.id
			i++;
		});
		$('#potrimitemsearchTbl').datagrid('clearSelections');
		$('#potrimitemimportWindow').window('close');
		return formObj;
	}
	
}
window.MsPoTrimItem=new MsPoTrimItemController(new MsPoTrimItemModel());
MsPoTrimItem.trimSearchGrid([])
MsPoTrimItem.showGrid([])
