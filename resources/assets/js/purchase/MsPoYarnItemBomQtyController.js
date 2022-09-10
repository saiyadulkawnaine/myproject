require('./../datagrid-filter.js');
let MsPoYarnItemBomQtyModel = require('./MsPoYarnItemBomQtyModel');

class MsPoYarnItemBomQtyController {
	constructor(MsPoYarnItemBomQtyModel)
	{
		this.MsPoYarnItemBomQtyModel = MsPoYarnItemBomQtyModel;
		this.formId='poyarnitembomqtyFrm';
		this.dataTable='#poyarnitembomqtyTbl';
		this.route=msApp.baseUrl()+"/poyarnitembomqty"
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
		let po_yarn_id = $('#poyarnFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_yarn_id=po_yarn_id;
		if(formObj.id){
			this.MsPoYarnItemBomQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnItemBomQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitMalti()
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
		let po_yarn_id = $('#poyarnFrm  [name=id]').val();
		let formObj=msApp.get('poyarnitembomqtymultiFrm');
		formObj.po_yarn_id=po_yarn_id;
		this.MsPoYarnItemBomQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		msApp.resetForm('poyarnitembomqtymultiFrm');
		$('#poyarnitembomqtymultiWindow').window('close');
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnItemBomQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnItemBomQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_yarn_id=$('#poyarnFrm  [name=id]').val()
		MsPoYarnItem.get(po_yarn_id);
		MsPoYarnItemBomQty.openpoyarnitembomqtyWindow(d.po_yarn_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsPoYarnItemBomQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		let self=this;
		var dg = $('#poyarnitembomqtyTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			showFooter:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tbom_qty=0;
				var tbom_amount=0;
				var tprev_po_qty=0;
				var tbalance_qty=0;
				var tQty=0;
				var tAmout=0;

				for(var i=0; i<data.rows.length; i++){
				tbom_qty+=data.rows[i]['bom_qty'].replace(/,/g,'')*1;
				tbom_amount+=data.rows[i]['bom_amount'].replace(/,/g,'')*1;
				tprev_po_qty+=data.rows[i]['prev_po_qty'].replace(/,/g,'')*1;
				tbalance_qty+=data.rows[i]['balance_qty'].replace(/,/g,'')*1;
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}

				var tRate=0;
				var tbom_rate=0;
				
				if(tQty){
				   tRate=(tAmout/tQty);	
				}
				if(tbom_qty){
				   tbom_rate=(tbom_amount/tbom_qty);	
				}

				$(this).datagrid('reloadFooter', [
					{ 
						bom_qty: tbom_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						bom_rate: tbom_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						bom_amount: tbom_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						prev_po_qty: tprev_po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						balance_qty: tbalance_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnItemBomQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	
	
	openQtyWindow(po_yarn_item_id,sales_order_id,budget_yarn_id)
	{
		if(!po_yarn_item_id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/poyarnitembomqty/create?po_yarn_item_id="+po_yarn_item_id+'&sales_order_id='+sales_order_id+'&budget_yarn_id='+budget_yarn_id);
		let g=data.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poyarnitembomqtymultiWindow').window('open');
		})
	}


	openpoyarnitembomqtyWindow(po_yarn_item_id)
	{
		if(!po_yarn_item_id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/poyarnitembomqty?po_yarn_item_id="+po_yarn_item_id);
		let g=data.then(function (response) {
			$('#poyarnitembomqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poyarnitembomqtyFrm  [name=po_yarn_item_id]').val(po_yarn_item_id);
			$('#poyarnitembomqtyWindow').window('open');
		})
	}


	openOrderSearchWindow()
	{
		let po_yarn_item_id=$('#poyarnitembomqtyFrm  [name=po_yarn_item_id]').val();
		let data= axios.get(msApp.baseUrl()+"/poyarnitembomqty/getorder?po_yarn_item_id="+po_yarn_item_id);
		let g=data.then(function (response) {
			$('#poyarnordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poyarnordersearchWindow').window('open');
		})
	}

	calculateAmount(iteration,count,field)
	{
		let rate=$('#poyarnitembomqtymultiFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poyarnitembomqtymultiFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarnitembomqtymultiFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculateAmountfrom(iteration,count,field)
	{
		let qty=$('#poyarnitembomqtyFrm  [name=qty]').val();
		let rate=$('#poyarnitembomqtyFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarnitembomqtyFrm  [name=amount]').val(amount);
	}
	
}
window.MsPoYarnItemBomQty=new MsPoYarnItemBomQtyController(new MsPoYarnItemBomQtyModel());
MsPoYarnItemBomQty.showGrid([]);


