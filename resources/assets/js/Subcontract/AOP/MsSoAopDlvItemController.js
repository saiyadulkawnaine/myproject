let MsSoAopDlvItemModel = require('./MsSoAopDlvItemModel');
require('./../../datagrid-filter.js');
class MsSoAopDlvItemController {
	constructor(MsSoAopDlvItemModel)
	{
		this.MsSoAopDlvItemModel = MsSoAopDlvItemModel;
		this.formId='soaopdlvitemFrm';
		this.dataTable='#soaopdlvitemTbl';
		this.route=msApp.baseUrl()+"/soaopdlvitem"
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
			this.MsSoAopDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let so_aop_dlv_id=$('#soaopdlvFrm [name=id]').val()
		let formObj=msApp.get('soaopdlvitemmatrixFrm');
		formObj.so_aop_dlv_id=so_aop_dlv_id;
		if(formObj.id){
			this.MsSoAopDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopDlvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopDlvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopdlvitemWindow').window('close');
		MsSoAopDlvItem.get(d.so_aop_dlv_id)
		msApp.resetForm('soaopdlvitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopDlvItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_dlv_id)
	{
		let data= axios.get(this.route+"?so_aop_dlv_id="+so_aop_dlv_id);
		data.then(function (response) {
			$('#soaopdlvitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	import(){
		$('#soaopdlvitemWindow').window('open');
	}
	/*soaopdlvitemsoGrid(data){
		let self = this;
		$('#soaopdlvitemsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopdlvitemFrm [name=so_aop_id]').val(row.id);
				$('#soaopdlvitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopdlvitemFrm [name=company_id]').val(row.company_id);
				$('#soaopdlvitemFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopdlvitemsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}*/
	getitem()
	{
		let so_aop_dlv_id=$('#soaopdlvFrm  [name=id]').val();
		let sales_order_no=$('#soaopdlvitemsearchFrm  [name=sales_order_no]').val();
		let style_ref=$('#soaopdlvitemsearchFrm  [name=style_ref]').val();
		if(sales_order_no==''){
			alert('Please insert Sales Order No');
			return;
		}
		let data= axios.get(this.route+"/create?so_aop_dlv_id="+so_aop_dlv_id+'&sales_order_no='+sales_order_no+'&style_ref='+style_ref);
		data.then(function (response) {
			$('#soaopdlvitemWindowscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	// calculate(iteration,count){
	// 	let qty=$('#soaopdlvitemmatrixFrm input[name="qty['+iteration+']"]').val();
	// 	let rate=$('#soaopdlvitemmatrixFrm input[name="rate['+iteration+']"]').val();
	// 	let amount=msApp.multiply(qty,rate);
	// 	$('#soaopdlvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	// }

	calculate(iteration,count){
		let qty=$('#soaopdlvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let grey_used=$('#soaopdlvitemmatrixFrm input[name="grey_used['+iteration+']"]').val();
		let rate=$('#soaopdlvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let bill_for=$('#soaopdlvitemmatrixFrm  [name="bill_for['+iteration+']"]').val();
		if (bill_for==1) {
			let amount=msApp.multiply(qty,rate);
			$('#soaopdlvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
		}
		if (bill_for==2) {
			let amount=msApp.multiply(grey_used,rate);
			$('#soaopdlvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
		}
	}

	// calculate_form(){
 //        let qty=$('#soaopdlvitemFrm  [name=qty]').val();		
 //        let rate=$('#soaopdlvitemFrm  [name=rate]').val();		
	// 	let amount=msApp.multiply(qty,rate);
 //        $('#soaopdlvitemFrm  [name=amount]').val(amount);		
	// }
	calculate_form(){
        let qty=$('#soaopdlvitemFrm  [name=qty]').val();		
        let grey_used=$('#soaopdlvitemFrm  [name=grey_used]').val();		
        let rate=$('#soaopdlvitemFrm  [name=rate]').val();
        let bill_for=$('select[name=bill_for]').val();
		if (bill_for==1) {
			let amount=msApp.multiply(qty,rate);
			$('#soaopdlvitemFrm  [name=amount]').val(amount);		
		}
		if (bill_for==2) {
			let amount=msApp.multiply(grey_used,rate);
			$('#soaopdlvitemFrm  [name=amount]').val(amount);		
		}
		//let amount=msApp.multiply(qty,rate);
        
	}
}
window.MsSoAopDlvItem=new MsSoAopDlvItemController(new MsSoAopDlvItemModel());
MsSoAopDlvItem.showGrid([]);
//MsSoAopDlvItem.soaopdlvitemsoGrid([]);