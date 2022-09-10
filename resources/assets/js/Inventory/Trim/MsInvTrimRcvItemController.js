let MsInvTrimRcvItemModel = require('./MsInvTrimRcvItemModel');

class MsInvTrimRcvItemController {
	constructor(MsInvTrimRcvItemModel)
	{
		this.MsInvTrimRcvItemModel = MsInvTrimRcvItemModel;
		this.formId='invtrimrcvitemFrm';	             
		this.dataTable='#invtrimrcvitemTbl';
		this.route=msApp.baseUrl()+"/invtrimrcvitem"
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
		let inv_rcv_id=$('#invtrimrcvFrm [name=id]').val()
		let inv_trim_rcv_id=$('#invtrimrcvFrm [name=inv_trim_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_trim_rcv_id=inv_trim_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvTrimRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvTrimRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invtrimrcvFrm [name=id]').val()
		let inv_trim_rcv_id=$('#invtrimrcvFrm [name=inv_trim_rcv_id]').val()
		let formObj=msApp.get('invtrimrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_trim_rcv_id=inv_trim_rcv_id;
		if(formObj.id){
			this.MsInvTrimRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvTrimRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		let store_id=$('#invtrimrcvitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invtrimrcvitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvTrimRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvTrimRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvTrimRcvItem.resetForm();
		$('#invtrimrcvitemWindow').window('close');
		MsInvTrimRcvItem.get(d.inv_trim_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvTrimRcvItemModel.get(index,row);

	}
	get(inv_trim_rcv_id){
		let params={};
		params.inv_trim_rcv_id=inv_trim_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invtrimrcvitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvTrimRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invtrimrcvitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invtrimrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invtrimrcvitemFrm [name=po_trim_item_report_id]').val(row.po_trim_item_report_id);
				$('#invtrimrcvitemFrm [name=po_no]').val(row.po_no);
				$('#invtrimrcvitemFrm [name=pi_no]').val(row.pi_no);
				$('#invtrimrcvitemFrm [name=itemclass_id]').val(row.itemclass_id);
				$('#invtrimrcvitemFrm [name=item_desc]').val(row.item_description);
				$('#invtrimrcvitemFrm [name=trim_color_id]').val(row.trim_color_id);
				$('#invtrimrcvitemFrm [name=item_color_name]').val(row.item_color_name);
				$('#invtrimrcvitemFrm [name=measurment]').val(row.measurment);
				$('#invtrimrcvitemFrm [name=style_color_name]').val(row.style_color_name);
				$('#invtrimrcvitemFrm [name=style_size_name]').val(row.style_size_name);
				$('#invtrimrcvitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invtrimrcvitemFrm [name=uom_code]').val(row.uom_name);
				$('#invtrimrcvitemFrm [name=currency_code]').val(row.currency_code);
				if(row.currency_code=='USD'){
					$('#invtrimrcvitemFrm [name=exch_rate]').val(84);
				}
				else{
					$('#invtrimrcvitemFrm [name=exch_rate]').val(1);
				}
				$('#invtrimrcvitemFrm [name=qty]').val(row.qty);
				$('#invtrimrcvitemFrm [name=rate]').val(row.rate);
				$('#invtrimrcvitemFrm [name=amount]').val(row.amount);
				$('#invtrimrcvitemsearchwindow').window('close');*/
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let po_no=$('#invtrimrcvitemsearchFrm [name=po_no]').val();
		let pi_no=$('#invtrimrcvitemsearchFrm [name=pi_no]').val();
		let po_trim_id=$('#invtrimrcvitemsearchFrm [name=po_trim_id]').val();
		let inv_pur_req_id=$('#invtrimrcvitemsearchFrm [name=inv_pur_req_id]').val();
		let requisition_no=$('#invtrimrcvitemsearchFrm [name=requisition_no]').val();
		let inv_rcv_id=$('#invtrimrcvFrm [name=id]').val();
		if(po_no==''){
			alert('Please Enter Po No');
			return;
		}
		let params={};
		params.po_no=po_no;
		params.pi_no=pi_no;
		params.po_trim_id=po_trim_id;
		params.inv_pur_req_id=inv_pur_req_id;
		params.requisition_no=requisition_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invtrimrcvitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	

	calculate_qty_form()
	{
		let rate=$('#invtrimrcvitemFrm input[name=rate]').val();
		let qty=$('#invtrimrcvitemFrm input[name=qty]').val();
		let amount=qty*1*rate*1;
		$('#invtrimrcvitemFrm input[name=amount]').val(amount);
	}

	getSelection(){
		let po_trim_item_report_id=[];
		let checked=$('#invtrimrcvitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			po_trim_item_report_id.push(val.po_trim_item_report_id)
		});
		po_trim_item_report_id=po_trim_item_report_id.join(',');
		$('#invtrimrcvitemsearchTbl').datagrid('clearSelections');
		$('#invtrimrcvitemsearchTbl').datagrid('loadData',[]);
		$('#invtrimrcvitemsearchwindow').window('close');
		return po_trim_item_report_id;
	}

	closeinvtrimrcvitemWindow()
	{
		//let po_trim_item_report_id=this.getSelection();
		//alert(po_trim_item_report_id);
		let inv_rcv_id=$('#invtrimrcvFrm  [name=id]').val();
		let inv_trim_rcv_id=$('#invtrimrcvFrm  [name=inv_trim_rcv_id]').val();
		let po_trim_item_report_id=this.getSelection();
		let data= axios.get(this.route+"/create"+"?po_trim_item_report_id="+po_trim_item_report_id+'&inv_trim_rcv_id='+inv_trim_rcv_id+'&inv_rcv_id='+inv_rcv_id)
		.then(function (response) {
			$('#invtrimrcvitemscs').html(response.data);
			$('#invtrimrcvitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate_qty(iteration,count)
	{
		let qty=$('#invtrimrcvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#invtrimrcvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=qty*1*rate*1;
		$('#invtrimrcvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount);
	}

	copyStore(val,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			$('#invtrimrcvitemmatrixFrm select[name="store_id['+i+']"]').val(val);
		}

	}

	copyRoom(val,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			$('#invtrimrcvitemmatrixFrm input[name="room['+i+']"]').val(val);
		}

	}

	copyRack(val,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			$('#invtrimrcvitemmatrixFrm input[name="rack['+i+']"]').val(val);
		}
	}

	copyShelf(val,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			$('#invtrimrcvitemmatrixFrm input[name="shelf['+i+']"]').val(val);
		}
	}

	copyRemarks(val,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			$('#invtrimrcvitemmatrixFrm input[name="remarks['+i+']"]').val(val);
		}
	}
}
window.MsInvTrimRcvItem=new MsInvTrimRcvItemController(new MsInvTrimRcvItemModel());
MsInvTrimRcvItem.itemSearchGrid([]);
MsInvTrimRcvItem.showGrid([]);