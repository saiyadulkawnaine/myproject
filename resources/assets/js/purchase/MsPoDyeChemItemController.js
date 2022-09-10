require('./../datagrid-filter.js');
let MsPoDyeChemItemModel = require('./MsPoDyeChemItemModel');
class MsPoDyeChemItemController {
	constructor(MsPoDyeChemItemModel)
	{
		this.MsPoDyeChemItemModel = MsPoDyeChemItemModel;
		this.formId='podyechemitemFrm';
		this.dataTable='#podyechemitemTbl';
		this.route=msApp.baseUrl()+"/podyechemitem"
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
			this.MsPoDyeChemItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoDyeChemItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitBatch()
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
		let po_dye_chem_id=$('#podyechemFrm  [name=id]').val();
		let formObj=msApp.get('podyechemitemmultiFrm');
		formObj.po_dye_chem_id=po_dye_chem_id;
		this.MsPoDyeChemItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}



	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoDyeChemItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoDyeChemItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_dye_chem_id=$('#podyechemFrm  [name=id]').val()
		MsPoDyeChemItem.get(po_dye_chem_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoDyeChemItemModel.get(index,row);	
	}
	get(po_dye_chem_id){
		let data= axios.get(this.route+"?po_dye_chem_id="+po_dye_chem_id)
		.then(function (response) {
			$('#podyechemitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		var dg = $('#podyechemitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			showFooter: 'true',
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChemItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	

	openItemWindow()
	{
		$('#importdyechemWindow').window('open');
	}
	searchDyeChem(){
		let requisition_no=$('#podyechemsearchFrm  [name=requisition_no]').val();
		let item_description=$('#podyechemsearchFrm  [name=item_description]').val();
		let po_dye_chem_id=$('#podyechemFrm  [name=id]').val();
		let data= axios.get(this.route+"/importitem"+"?requisition_no="+requisition_no+"&item_description="+item_description+"&po_dye_chem_id="+po_dye_chem_id)
		.then(function (response) {
			$('#podyechemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	dyechemSearchGrid(data)
	{
		var dg = $('#podyechemsearchTbl');
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

	closeItemWindow()
	{
		let po_dye_chem_id=$('#podyechemFrm  [name=id]').val();
		let inv_pur_req_item_id=this.getSelections();
		$('#importdyechemWindow').window('close');
		let data= axios.get(this.route+"/create"+"?inv_pur_req_item_id="+inv_pur_req_item_id+'&po_dye_chem_id='+po_dye_chem_id)
		.then(function (response) {
			$('#importdyechemscs').html(response.data);
			$('#podyechemitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSelections()
	{
		let po_dye_chem_id=$('#podyechemFrm  [name=id]').val();
		let inv_pur_req_item_id=[];
		let checked=$('#podyechemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 )
		{
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) 
		{
			inv_pur_req_item_id.push(val.id)
		});
		inv_pur_req_item_id=inv_pur_req_item_id.join(',');
		$('#podyechemsearchTbl').datagrid('clearSelections');
		return inv_pur_req_item_id;
	}

	calculateAmount(iteration,count,field)
	{
		let rate=$('#podyechemitemmultiFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#podyechemitemmultiFrm input[name="qty['+iteration+']"]').val();
		let balance_qty=$('#podyechemitemmultiFrm input[name="balance_qty['+iteration+']"]').val();
		if(qty*1>balance_qty*1){
			alert('More than balance not allowed');
			$('#podyechemitemmultiFrm input[name="qty['+iteration+']"]').val('');
			return;
		}
		let amount=msApp.multiply(qty,rate);
		$('#podyechemitemmultiFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculate()
	{
		let rate=$('#podyechemitemFrm  [name=rate]').val();
		let qty=$('#podyechemitemFrm  [name=qty]').val();
		let balance_qty=$('#podyechemitemFrm  [name=balance_qty]').val();
		if(qty*1>balance_qty*1){
			alert('More than balance not allowed');
			$('#podyechemitemFrm  [name=qty]').val('');
			return;
		}
		let amount=msApp.multiply(qty,rate);
		$('#podyechemitemFrm  [name=amount]').val(amount);
	}
}
window.MsPoDyeChemItem=new MsPoDyeChemItemController(new MsPoDyeChemItemModel());
MsPoDyeChemItem.dyechemSearchGrid([]);
MsPoDyeChemItem.showGrid([]);
