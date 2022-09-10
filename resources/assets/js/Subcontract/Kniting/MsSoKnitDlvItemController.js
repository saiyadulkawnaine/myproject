let MsSoKnitDlvItemModel = require('./MsSoKnitDlvItemModel');
require('./../../datagrid-filter.js');
class MsSoKnitDlvItemController {
	constructor(MsSoKnitDlvItemModel)
	{
		this.MsSoKnitDlvItemModel = MsSoKnitDlvItemModel;
		this.formId='soknitdlvitemFrm';
		this.dataTable='#soknitdlvitemTbl';
		this.route=msApp.baseUrl()+"/soknitdlvitem"
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
		let so_knit_dlv_id = $('#soknitdlvFrm  [name=id]').val();
		formObj.so_knit_dlv_id=so_knit_dlv_id;
		if(formObj.id){
			this.MsSoKnitDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
	    let so_knit_dlv_id = $('#soknitdlvFrm  [name=id]').val();
		let formObj=msApp.get('soknitdlvitemmatrixFrm');
		formObj.so_knit_dlv_id=so_knit_dlv_id;
		if(formObj.id){
			this.MsSoKnitDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoKnitDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitDlvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitDlvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoKnitDlvItem.get(d.so_knit_dlv_id)
		msApp.resetForm('soknitdlvitemFrm');
					$('#soknitdlvitemFrm [id="uom_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitDlvItemModel.get(index,row);
		workReceive.then(function(response){
			$('#soknitdlvitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_knit_dlv_id)
	{
		let data= axios.get(this.route+"?so_knit_dlv_id="+so_knit_dlv_id);
		data.then(function (response) {
			$('#soknitdlvitemTbl').datagrid('loadData', response.data);
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
			//fitColumns:true,
			//url:this.route,
			showFooter:true,
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
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	itemWindow(){
		$('#soknitdlvitemWindow').window('open');
	}
	
	itemGrid(data){
		let self = this;
		$('#soknitdlvitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknitdlvitemFrm [name=item_account_id]').val(row.id);
				$('#soknitdlvitemFrm [name=count]').val(row.count);
				$('#soknitdlvitemFrm [name=item_description]').val(row.composition_name);
				$('#soknitdlvitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		let sales_order_no=$('#soknitdlvitemsearchFrm  [name=sales_order_no]').val();
		let style_ref=$('#soknitdlvitemsearchFrm  [name=style_ref]').val();
		let company_id=$('#soknitdlvFrm  [name=company_id]').val();
		let buyer_id=$('#soknitdlvFrm  [name=buyer_id]').val();
		let so_knit_dlv_id=$('#soknitdlvFrm  [name=id]').val();
		if(sales_order_no==''){
			alert('Please insert Sales Order No');
			return;
		}
		let data= axios.get(this.route+"/create?sales_order_no="+sales_order_no+'&style_ref='+style_ref+'&so_knit_dlv_id='+so_knit_dlv_id);
		data.then(function (response) {
			//$('#soknitdlvitemsearchTbl').datagrid('loadData', response.data);
			$('#soknitdlvitemWindowscs').html(response.data);

		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate_form()
	{
		let qty = $('#soknitdlvitemFrm  [name=qty]').val();
		let rate = $('#soknitdlvitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soknitdlvitemFrm  [name=amount]').val(amount);
	}

	calculate(iteration,count){
		let qty=$('#soknitdlvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#soknitdlvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soknitdlvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}
}
window.MsSoKnitDlvItem=new MsSoKnitDlvItemController(new MsSoKnitDlvItemModel());
MsSoKnitDlvItem.showGrid([]);
//MsSoKnitDlvItem.itemGrid([]);
 
