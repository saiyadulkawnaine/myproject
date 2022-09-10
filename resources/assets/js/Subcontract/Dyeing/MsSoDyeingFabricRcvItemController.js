let MsSoDyeingFabricRcvItemModel = require('./MsSoDyeingFabricRcvItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvItemController {
	constructor(MsSoDyeingFabricRcvItemModel)
	{
		this.MsSoDyeingFabricRcvItemModel = MsSoDyeingFabricRcvItemModel;
		this.formId='sodyeingfabricrcvitemFrm';
		this.dataTable='#sodyeingfabricrcvitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcvitem"
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
			this.MsSoDyeingFabricRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let so_dyeing_fabric_rcv_id=$('#sodyeingfabricrcvFrm [name=id]').val()
		let formObj=msApp.get('sodyeingfabricrcvitemmatrixFrm');
		formObj.so_dyeing_fabric_rcv_id=so_dyeing_fabric_rcv_id;
		if(formObj.id){
			this.MsSoDyeingFabricRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingFabricRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingfabricrcvitemWindow').window('close');
		MsSoDyeingFabricRcvItem.get(d.so_dyeing_fabric_rcv_id)
		msApp.resetForm('sodyeingfabricrcvitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_fabric_rcv_id)
	{
		let data= axios.get(this.route+"?so_dyeing_fabric_rcv_id="+so_dyeing_fabric_rcv_id);
		data.then(function (response) {
			$('#sodyeingfabricrcvitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#sodyeingfabricrcvitemWindow').window('open');
	}
	sodyeingfabricrcvitemsoGrid(data){
		let self = this;
		$('#sodyeingfabricrcvitemsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingfabricrcvitemFrm [name=so_dyeing_id]').val(row.id);
				$('#sodyeingfabricrcvitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingfabricrcvitemFrm [name=company_id]').val(row.company_id);
				$('#sodyeingfabricrcvitemFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingfabricrcvitemsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}*/
	import()
	{
		let so_dyeing_fabric_rcv_id=$('#sodyeingfabricrcvFrm  [name=id]').val();
		let data= axios.get(this.route+"/create?so_dyeing_fabric_rcv_id="+so_dyeing_fabric_rcv_id);
		data.then(function (response) {
			$('#sodyeingfabricrcvitemWindowscs').html(response.data);
			$('#sodyeingfabricrcvitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate(iteration,count){
		let qty=$('#sodyeingfabricrcvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#sodyeingfabricrcvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingfabricrcvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}
	calculate_form(){
		
		let qty=$('#sodyeingfabricrcvitemFrm  [name=qty]').val();
		let rate=$('#sodyeingfabricrcvitemFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingfabricrcvitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoDyeingFabricRcvItem=new MsSoDyeingFabricRcvItemController(new MsSoDyeingFabricRcvItemModel());
MsSoDyeingFabricRcvItem.showGrid([]);
//MsSoDyeingFabricRcvItem.sodyeingfabricrcvitemsoGrid([]);