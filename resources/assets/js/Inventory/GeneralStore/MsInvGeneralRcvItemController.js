let MsInvGeneralRcvItemModel = require('./MsInvGeneralRcvItemModel');

class MsInvGeneralRcvItemController {
	constructor(MsInvGeneralRcvItemModel)
	{
		this.MsInvGeneralRcvItemModel = MsInvGeneralRcvItemModel;
		this.formId='invgeneralrcvitemFrm';	             
		this.dataTable='#invgeneralrcvitemTbl';
		this.route=msApp.baseUrl()+"/invgeneralrcvitem"
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
		let inv_rcv_id=$('#invgeneralrcvFrm [name=id]').val()
		let inv_general_rcv_id=$('#invgeneralrcvFrm [name=inv_general_rcv_id]').val();
		let receive_against_id=$('#invgeneralrcvFrm [name=receive_against_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_general_rcv_id=inv_general_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		formObj.receive_against_id=receive_against_id;

		if(formObj.id){
			this.MsInvGeneralRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invgeneralrcvFrm [name=id]').val()
		let inv_general_rcv_id=$('#invgeneralrcvFrm [name=inv_general_rcv_id]').val()
		let receive_against_id=$('#invgeneralrcvFrm [name=receive_against_id]').val()
		let formObj=msApp.get('invyarnrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_general_rcv_id=inv_general_rcv_id;
		formObj.receive_against_id=receive_against_id;
		if(formObj.id){
			this.MsInvGeneralRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		let store_id=$('#invgeneralrcvitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneralrcvitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralRcvItem.resetForm();
		MsInvGeneralRcvItem.get(d.inv_general_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvGeneralRcvItemModel.get(index,row);

	}
	get(inv_general_rcv_id){
		let params={};
		params.inv_general_rcv_id=inv_general_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgeneralrcvitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneralrcvitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneralrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralrcvitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneralrcvitemFrm [name=inv_pur_req_item_id]').val(row.inv_pur_req_item_id);
				$('#invgeneralrcvitemFrm [name=po_general_item_id]').val(row.po_general_item_id);
				$('#invgeneralrcvitemFrm [name=rq_no]').val(row.rq_no);
				$('#invgeneralrcvitemFrm [name=po_no]').val(row.po_no);
				$('#invgeneralrcvitemFrm [name=pi_no]').val(row.pi_no);
				$('#invgeneralrcvitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneralrcvitemFrm [name=item_desc]').val(row.item_description);
				$('#invgeneralrcvitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneralrcvitemFrm [name=currency_code]').val(row.currency_code);
				$('#invgeneralrcvitemFrm [name=exch_rate]').val(row.exch_rate);
				$('#invgeneralrcvitemFrm [name=qty]').val(row.qty);
				$('#invgeneralrcvitemFrm [name=rate]').val(row.rate);
				$('#invgeneralrcvitemFrm [name=amount]').val(row.amount);
				$('#invgeneralrcvitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let po_no=$('#invgeneralrcvitemsearchFrm [name=po_no]').val();
		let pi_no=$('#invgeneralrcvitemsearchFrm [name=pi_no]').val();
		let po_general_id=$('#invgeneralrcvitemsearchFrm [name=po_general_id]').val();
		let inv_pur_req_id=$('#invgeneralrcvitemsearchFrm [name=inv_pur_req_id]').val();
		let requisition_no=$('#invgeneralrcvitemsearchFrm [name=requisition_no]').val();
		let inv_rcv_id=$('#invgeneralrcvFrm [name=id]').val();
		let params={};
		params.po_no=po_no;
		params.pi_no=pi_no;
		params.po_general_id=po_general_id;
		params.inv_pur_req_id=inv_pur_req_id;
		params.requisition_no=requisition_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneralrcvitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	closeinvyarnrcvitemWindow()
	{
		let inv_rcv_id=$('#invgeneralrcvFrm  [name=id]').val();
		let inv_general_rcv_id=$('#invgeneralrcvFrm  [name=inv_general_rcv_id]').val();
		let po_yarn_item_id=this.getSelection();
		let data= axios.get(this.route+"/create"+"?po_yarn_item_id="+po_yarn_item_id+'&inv_general_rcv_id='+inv_general_rcv_id)
		.then(function (response) {
			$('#invgeneralrcvitemscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate_qty_form()
	{
		let rate=$('#invgeneralrcvitemFrm input[name=rate]').val();
		let qty=$('#invgeneralrcvitemFrm input[name=qty]').val();
		let amount=qty*1*rate*1;
		$('#invgeneralrcvitemFrm input[name=amount]').val(amount);
	}

}
window.MsInvGeneralRcvItem=new MsInvGeneralRcvItemController(new MsInvGeneralRcvItemModel());
MsInvGeneralRcvItem.itemSearchGrid([]);
MsInvGeneralRcvItem.showGrid([]);