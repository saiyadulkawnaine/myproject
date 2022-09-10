let MsSoDyeingDlvItemModel = require('./MsSoDyeingDlvItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingDlvItemController {
	constructor(MsSoDyeingDlvItemModel)
	{
		this.MsSoDyeingDlvItemModel = MsSoDyeingDlvItemModel;
		this.formId='sodyeingdlvitemFrm';
		this.dataTable='#sodyeingdlvitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingdlvitem"
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
			this.MsSoDyeingDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let so_dyeing_dlv_id=$('#sodyeingdlvFrm [name=id]').val()
		let formObj=msApp.get('sodyeingdlvitemmatrixFrm');
		formObj.so_dyeing_dlv_id=so_dyeing_dlv_id;
		if(formObj.id){
			this.MsSoDyeingDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingDlvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingDlvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingdlvitemWindow').window('close');
		MsSoDyeingDlvItem.get(d.so_dyeing_dlv_id)
		msApp.resetForm('sodyeingdlvitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingDlvItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_dlv_id)
	{
		let data= axios.get(this.route+"?so_dyeing_dlv_id="+so_dyeing_dlv_id);
		data.then(function (response) {
			$('#sodyeingdlvitemTbl').datagrid('loadData', response.data);
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
				var tGrayUsed=0;
				var tRoll=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tGrayUsed+=data.rows[i]['grey_used'].replace(/,/g,'')*1;
				tRoll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_used: tGrayUsed.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_roll: tRoll.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	import(){
		$('#sodyeingdlvitemWindow').window('open');
	}
	/*sodyeingdlvitemsoGrid(data){
		let self = this;
		$('#sodyeingdlvitemsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingdlvitemFrm [name=so_dyeing_id]').val(row.id);
				$('#sodyeingdlvitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingdlvitemFrm [name=company_id]').val(row.company_id);
				$('#sodyeingdlvitemFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingdlvitemsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}*/
	getitem()
	{
		let so_dyeing_dlv_id=$('#sodyeingdlvFrm  [name=id]').val();
		let sales_order_no=$('#sodyeingdlvitemsearchFrm  [name=sales_order_no]').val();
		let style_ref=$('#sodyeingdlvitemsearchFrm  [name=style_ref]').val();
		if(sales_order_no==''){
			alert('Please insert Sales Order No');
			return;
		}
		let data= axios.get(this.route+"/create?so_dyeing_dlv_id="+so_dyeing_dlv_id+'&sales_order_no='+sales_order_no+'&style_ref='+style_ref);
		data.then(function (response) {
			$('#sodyeingdlvitemWindowscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate(iteration,count){
		let grey_used=$('#sodyeingdlvitemmatrixFrm input[name="grey_used['+iteration+']"]').val();
		let rate=$('#sodyeingdlvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(grey_used,rate);
		$('#sodyeingdlvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculate_form(){
        let grey_used=$('#sodyeingdlvitemFrm  [name=grey_used]').val();		
        let rate=$('#sodyeingdlvitemFrm  [name=rate]').val();		
		let amount=msApp.multiply(grey_used,rate);
        $('#sodyeingdlvitemFrm  [name=amount]').val(amount);		
	}
}
window.MsSoDyeingDlvItem=new MsSoDyeingDlvItemController(new MsSoDyeingDlvItemModel());
MsSoDyeingDlvItem.showGrid([]);
//MsSoDyeingDlvItem.sodyeingdlvitemsoGrid([]);