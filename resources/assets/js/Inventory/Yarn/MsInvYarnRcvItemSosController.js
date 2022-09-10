let MsInvYarnRcvItemSosModel = require('./MsInvYarnRcvItemSosModel');

class MsInvYarnRcvItemSosController {
	constructor(MsInvYarnRcvItemSosModel)
	{
		this.MsInvYarnRcvItemSosModel = MsInvYarnRcvItemSosModel;
		this.formId='invyarnrcvitemsosFrm';	             
		this.dataTable='#invyarnrcvitemsosTbl';
		this.route=msApp.baseUrl()+"/invyarnrcvitemsos"
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
		//let inv_rcv_id=$('#invyarnrcvFrm [name=id]').val()
		//let inv_yarn_rcv_id=$('#invyarnrcvFrm [name=inv_yarn_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		//formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		//formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvYarnRcvItemSosModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnRcvItemSosModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invyarnrcvFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarnrcvFrm [name=inv_yarn_rcv_id]').val()
		let formObj=msApp.get('invyarnrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		if(formObj.id){
			this.MsInvYarnRcvItemSosModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnRcvItemSosModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let inv_yarn_rcv_item_id=$('#invyarnrcvitemFrm  [name=id]').val();
		$('#invyarnrcvitemsosFrm  [name=inv_yarn_rcv_item_id]').val(inv_yarn_rcv_item_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnRcvItemSosModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnRcvItemSosModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvYarnRcvItemSos.resetForm();
		MsInvYarnRcvItemSos.get(d.inv_yarn_rcv_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvYarnRcvItemSosModel.get(index,row);

	}
	get(inv_yarn_rcv_item_id){
		let params={};
		params.inv_yarn_rcv_item_id=inv_yarn_rcv_item_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invyarnrcvitemsosTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var Qty=0;
				var Rate=0;
				var Amount=0;
				var cumulativeQty=0;
				var balanceQty=0;
				for(var i=0; i<data.rows.length; i++){
					Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					Rate+=data.rows[i]['rate'].replace(/,/g,'')*1;
					Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					cumulativeQty+=data.rows[i]['cumulative_qty'].replace(/,/g,'')*1;
					balanceQty+=data.rows[i]['balance_qty'].replace(/,/g,'')*1;
				}
				
				$('#invyarnrcvitemsosTbl').datagrid('reloadFooter', [
				{
					qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: Rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cumulative_qty: cumulativeQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance_qty: balanceQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvYarnRcvItemSos.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	

	

	
	

	calculate_qty_form(iteration,count)
	{
		let cone_per_bag=$('#invyarnrcvitemsosFrm input[name=cone_per_bag]').val();
		let wgt_per_cone=$('#invyarnrcvitemsosFrm input[name=wgt_per_cone]').val();
		let no_of_bag=$('#invyarnrcvitemsosFrm input[name=no_of_bag]').val();
		let qty=cone_per_bag*wgt_per_cone*no_of_bag;
		let rate=$('#invyarnrcvitemsosFrm input[name=rate]').val();
		let amount=qty*rate;
		$('#invyarnrcvitemsosFrm input[name=qty]').val(qty);
		$('#invyarnrcvitemsosFrm input[name=amount]').val(amount);
	}

	openSalesOrderWindow(id)
	{
		$('#invyarnrcvitemsosserchWindow').window('open');
	}

	salesorderSearch(){
		//let yarn_count=$('#invyarnrcvlibraryitemserchFrm  [name=yarn_count]').val();
		let inv_yarn_rcv_item_id=$('#invyarnrcvitemFrm  [name=id]').val();
		let params={};
		params.inv_yarn_rcv_item_id=inv_yarn_rcv_item_id
		let data= axios.get(this.route+"/getsalesorder",{params})
		.then(function (response) {
			$('#invyarnrcvitemsosserchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	salesorderSearchGrid(data)
	{
		var dg = $('#invyarnrcvitemsosserchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#invyarnrcvitemsosFrm  [name=sales_order_no]').val(row.sale_order_no);
				//$('#invyarnrcvitemsosFrm  [name=sales_order_id]').val(row.id);
				$('#invyarnrcvitemsosFrm  [name=po_yarn_item_bom_qty_id]').val(row.po_yarn_item_bom_qty_id);
				$('#invyarnrcvitemsosFrm  [name=buyer_name]').val(row.buyer_name);
				$('#invyarnrcvitemsosFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnrcvitemsosserchWindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsInvYarnRcvItemSos=new MsInvYarnRcvItemSosController(new MsInvYarnRcvItemSosModel());
MsInvYarnRcvItemSos.salesorderSearchGrid([]);
MsInvYarnRcvItemSos.showGrid([]);
