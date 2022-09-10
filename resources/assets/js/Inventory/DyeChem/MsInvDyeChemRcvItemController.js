let MsInvDyeChemRcvItemModel = require('./MsInvDyeChemRcvItemModel');

class MsInvDyeChemRcvItemController {
	constructor(MsInvDyeChemRcvItemModel)
	{
		this.MsInvDyeChemRcvItemModel = MsInvDyeChemRcvItemModel;
		this.formId='invdyechemrcvitemFrm';	             
		this.dataTable='#invdyechemrcvitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemrcvitem"
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
		let inv_rcv_id=$('#invdyechemrcvFrm [name=id]').val()
		let inv_dye_chem_rcv_id=$('#invdyechemrcvFrm [name=inv_dye_chem_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvDyeChemRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invdyechemrcvFrm [name=id]').val()
		let inv_dye_chem_rcv_id=$('#invdyechemrcvFrm [name=inv_dye_chem_rcv_id]').val()
		let receive_against_id=$('#invdyechemrcvFrm [name=receive_against_id]').val()
		let formObj=msApp.get('invyarnrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		formObj.receive_against_id=receive_against_id;
		if(formObj.id){
			this.MsInvDyeChemRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		let store_id=$('#invdyechemrcvitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invdyechemrcvitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemRcvItem.resetForm();
		MsInvDyeChemRcvItem.get(d.inv_dye_chem_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemRcvItemModel.get(index,row);

	}
	get(inv_dye_chem_rcv_id){
		let params={};
		params.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemrcvitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemrcvitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemrcvitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemrcvitemFrm [name=inv_pur_req_item_id]').val(row.inv_pur_req_item_id);
				$('#invdyechemrcvitemFrm [name=po_dye_chem_item_id]').val(row.po_dye_chem_item_id);
				$('#invdyechemrcvitemFrm [name=rq_no]').val(row.rq_no);
				$('#invdyechemrcvitemFrm [name=po_no]').val(row.po_no);
				$('#invdyechemrcvitemFrm [name=pi_no]').val(row.pi_no);
				$('#invdyechemrcvitemFrm [name=item_id]').val(row.item_account_id);
				$('#invdyechemrcvitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemrcvitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemrcvitemFrm [name=currency_code]').val(row.currency_code);
				$('#invdyechemrcvitemFrm [name=exch_rate]').val(row.exch_rate);
				$('#invdyechemrcvitemFrm [name=qty]').val(row.qty);
				$('#invdyechemrcvitemFrm [name=rate]').val(row.rate);
				$('#invdyechemrcvitemFrm [name=amount]').val(row.amount);
				$('#invdyechemrcvitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let po_no=$('#invdyechemrcvitemsearchFrm [name=po_no]').val();
		let pi_no=$('#invdyechemrcvitemsearchFrm [name=pi_no]').val();
		let po_dye_chem_id=$('#invdyechemrcvitemsearchFrm [name=po_dye_chem_id]').val();
		let inv_pur_req_id=$('#invdyechemrcvitemsearchFrm [name=inv_pur_req_id]').val();
		let requisition_no=$('#invdyechemrcvitemsearchFrm [name=requisition_no]').val();
		let inv_rcv_id=$('#invdyechemrcvFrm [name=id]').val();
		let params={};
		params.po_no=po_no;
		params.pi_no=pi_no;
		params.po_dye_chem_id=po_dye_chem_id;
		params.inv_pur_req_id=inv_pur_req_id;
		params.requisition_no=requisition_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemrcvitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	closeinvyarnrcvitemWindow()
	{
		let inv_rcv_id=$('#invdyechemrcvFrm  [name=id]').val();
		let inv_dye_chem_rcv_id=$('#invdyechemrcvFrm  [name=inv_dye_chem_rcv_id]').val();
		let po_yarn_item_id=this.getSelection();
		let data= axios.get(this.route+"/create"+"?po_yarn_item_id="+po_yarn_item_id+'&inv_dye_chem_rcv_id='+inv_dye_chem_rcv_id)
		.then(function (response) {
			$('#invdyechemrcvitemscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate_qty_form()
	{
		let rate=$('#invdyechemrcvitemFrm input[name=rate]').val();
		let qty=$('#invdyechemrcvitemFrm input[name=qty]').val();
		let amount=qty*1*rate*1;
		$('#invdyechemrcvitemFrm input[name=amount]').val(amount);
	}
}
window.MsInvDyeChemRcvItem=new MsInvDyeChemRcvItemController(new MsInvDyeChemRcvItemModel());
MsInvDyeChemRcvItem.itemSearchGrid([]);
MsInvDyeChemRcvItem.showGrid([]);