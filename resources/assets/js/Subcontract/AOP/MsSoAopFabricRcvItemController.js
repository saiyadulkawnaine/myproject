let MsSoAopFabricRcvItemModel = require('./MsSoAopFabricRcvItemModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvItemController {
	constructor(MsSoAopFabricRcvItemModel)
	{
		this.MsSoAopFabricRcvItemModel = MsSoAopFabricRcvItemModel;
		this.formId='soaopfabricrcvitemFrm';
		this.dataTable='#soaopfabricrcvitemTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcvitem"
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
			this.MsSoAopFabricRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let so_aop_fabric_rcv_id=$('#soaopfabricrcvFrm [name=id]').val()
		let formObj=msApp.get('soaopfabricrcvitemmatrixFrm');
		formObj.so_aop_fabric_rcv_id=so_aop_fabric_rcv_id;
		if(formObj.id){
			this.MsSoAopFabricRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopFabricRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricrcvitemWindow').window('close');
		MsSoAopFabricRcvItem.get(d.so_aop_fabric_rcv_id)
		msApp.resetForm('soaopfabricrcvitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_fabric_rcv_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_rcv_id="+so_aop_fabric_rcv_id);
		data.then(function (response) {
			$('#soaopfabricrcvitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#soaopfabricrcvitemWindow').window('open');
	}
	soaopfabricrcvitemsoGrid(data){
		let self = this;
		$('#soaopfabricrcvitemsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopfabricrcvitemFrm [name=so_aop_id]').val(row.id);
				$('#soaopfabricrcvitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopfabricrcvitemFrm [name=company_id]').val(row.company_id);
				$('#soaopfabricrcvitemFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopfabricrcvitemsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}*/
	import()
	{
		let so_aop_fabric_rcv_id=$('#soaopfabricrcvFrm  [name=id]').val();
		let data= axios.get(this.route+"/create?so_aop_fabric_rcv_id="+so_aop_fabric_rcv_id);
		data.then(function (response) {
			$('#soaopfabricrcvitemWindowscs').html(response.data);
			$('#soaopfabricrcvitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate(iteration,count){
		let qty=$('#soaopfabricrcvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#soaopfabricrcvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soaopfabricrcvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}
	calculate_form(){
		
		let qty=$('#soaopfabricrcvitemFrm  [name=qty]').val();
		let rate=$('#soaopfabricrcvitemFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soaopfabricrcvitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoAopFabricRcvItem=new MsSoAopFabricRcvItemController(new MsSoAopFabricRcvItemModel());
MsSoAopFabricRcvItem.showGrid([]);
//MsSoAopFabricRcvItem.soaopfabricrcvitemsoGrid([]);