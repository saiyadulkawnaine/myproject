require('./../datagrid-filter.js');
let MsPoDyeingServiceItemModel = require('./MsPoDyeingServiceItemModel');
class MsPoDyeingServiceItemController {
	constructor(MsPoDyeingServiceItemModel)
	{
		this.MsPoDyeingServiceItemModel = MsPoDyeingServiceItemModel;
		this.formId='podyeingserviceitemFrm';
		this.dataTable='#podyeingserviceitemTbl';
		this.route=msApp.baseUrl()+"/podyeingserviceitem"
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
			this.MsPoDyeingServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoDyeingServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
			this.MsPoDyeingServiceItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsPoDyeingServiceItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoDyeingServiceItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoDyeingServiceItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_dyeing_service_id=$('#podyeingserviceFrm  [name=id]').val()
		MsPoDyeingServiceItem.get(po_dyeing_service_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoDyeingServiceItemModel.get(index,row);
	}
	get(po_dyeing_service_id){
		let data= axios.get(this.route+"?po_dyeing_service_id="+po_dyeing_service_id)
		.then(function (response) {
			$('#podyeingserviceitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $('#podyeingserviceitemTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoDyeingServiceItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsPoDyeingServiceItemQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow()
	{
		$('#podyeingserviceitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#podyeingserviceFrm  [name=company_id]').val();
		$('#podyeingserviceitemsearchFrm  [name=company_id]').val(company_id);
		$('#podyeingserviceitemimportWindow').window('open');
	}
	searchFabric(){
		let company_id=$('#podyeingserviceFrm  [name=company_id]').val();
		let budget_id=$('#podyeingserviceitemsearchFrm  [name=budget_id]').val();
		let job_no=$('#podyeingserviceitemsearchFrm  [name=job_no]').val();
		let style_ref=$('#podyeingserviceitemsearchFrm  [name=style_ref]').val();
		let po_dyeing_service_id=$('#podyeingserviceFrm  [name=id]').val();
		let data= axios.get(this.route+"/importfabric"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&style_ref="+style_ref+"&po_dyeing_service_id="+po_dyeing_service_id)
		.then(function (response) {
			$('#podyeingserviceitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	fabricSearchGrid(data)
	{
		var dg = $('#podyeingserviceitemsearchTbl');
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
		formObj.po_dyeing_service_id=$('#podyeingserviceFrm  [name=id]').val();
		let i=1;
		$.each($('#podyeingserviceitemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_fabric_prod_id['+i+']']=val.id
			i++;
		});
		$('#podyeingserviceitemsearchTbl').datagrid('clearChecked');
		$('#podyeingserviceitemimportWindow').window('close');
		return formObj;
	}
}
window.MsPoDyeingServiceItem=new MsPoDyeingServiceItemController(new MsPoDyeingServiceItemModel());
MsPoDyeingServiceItem.fabricSearchGrid([]);
MsPoDyeingServiceItem.showGrid([]);