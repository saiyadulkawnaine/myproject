require('./../datagrid-filter.js');
let MsPoGeneralItemModel = require('./MsPoGeneralItemModel');
class MsPoGeneralItemController {
	constructor(MsPoGeneralItemModel)
	{
		this.MsPoGeneralItemModel = MsPoGeneralItemModel;
		this.formId='pogeneralitemFrm';
		this.dataTable='#pogeneralitemTbl';
		this.route=msApp.baseUrl()+"/pogeneralitem"
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
			this.MsPoGeneralItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoGeneralItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let po_general_id=$('#pogeneralFrm  [name=id]').val();
		let formObj=msApp.get('pogeneralitemmultiFrm');
		formObj.po_general_id=po_general_id;
		this.MsPoGeneralItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoGeneralItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoGeneralItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_general_id=$('#pogeneralFrm  [name=id]').val()
		MsPoGeneralItem.get(po_general_id);	
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoGeneralItemModel.get(index,row);	
	}
	get(po_general_id){
		let data= axios.get(this.route+"?po_general_id="+po_general_id)
		.then(function (response) {
			$('#pogeneralitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		var dg = $('#pogeneralitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		});
		dg.datagrid('loadData', data);
		
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	

	openItemWindow()
	{
		$('#importdyechemWindow').window('open');
	}
	searchDyeChem(){
		let requisition_no=$('#pogeneralsearchFrm  [name=requisition_no]').val();
		let item_description=$('#pogeneralsearchFrm  [name=item_description]').val();
		let po_general_id=$('#pogeneralFrm  [name=id]').val();
		let data= axios.get(this.route+"/importitem"+"?requisition_no="+requisition_no+"&item_description="+item_description+"&po_general_id="+po_general_id)
		.then(function (response) {
			$('#pogeneralsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	dyechemSearchGrid(data)
	{
		var dg = $('#pogeneralsearchTbl');
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
		let po_general_id=$('#pogeneralFrm  [name=id]').val();
		let inv_pur_req_item_id=this.getSelections();
		$('#importdyechemWindow').window('close');
		let data= axios.get(this.route+"/create"+"?inv_pur_req_item_id="+inv_pur_req_item_id+'&po_general_id='+po_general_id)
		.then(function (response) {
			$('#importdyechemscs').html(response.data);
			$('#pogeneralitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSelections()
	{
		let po_general_id=$('#pogeneralFrm  [name=id]').val();
		let inv_pur_req_item_id=[];
		let checked=$('#pogeneralsearchTbl').datagrid('getSelections');
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
		$('#pogeneralsearchTbl').datagrid('clearSelections');
		return inv_pur_req_item_id;
	}

	calculateAmount(iteration,count,field)
	{
		let rate=$('#pogeneralitemmultiFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#pogeneralitemmultiFrm input[name="qty['+iteration+']"]').val();
		let balance_qty=$('#pogeneralitemmultiFrm input[name="balance_qty['+iteration+']"]').val();
		if(qty*1>balance_qty*1){
			alert('More than balance not allowed');
			$('#pogeneralitemmultiFrm input[name="qty['+iteration+']"]').val('');
			return;
		}
		let amount=msApp.multiply(qty,rate);
		$('#pogeneralitemmultiFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculate()
	{
		let rate=$('#pogeneralitemFrm  [name=rate]').val();
		let qty=$('#pogeneralitemFrm  [name=qty]').val();
		let balance_qty=$('#pogeneralitemFrm  [name=balance_qty]').val();
		if(qty*1>balance_qty*1){
			alert('More than balance not allowed');
			$('#pogeneralitemFrm  [name=qty]').val('');
			return;
		}
		let amount=msApp.multiply(qty,rate);
		$('#pogeneralitemFrm  [name=amount]').val(amount);
	}
}
window.MsPoGeneralItem=new MsPoGeneralItemController(new MsPoGeneralItemModel());
MsPoGeneralItem.dyechemSearchGrid([]);
MsPoGeneralItem.showGrid([]);
