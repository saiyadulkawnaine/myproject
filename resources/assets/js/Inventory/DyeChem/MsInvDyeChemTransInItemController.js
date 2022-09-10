let MsInvDyeChemTransInItemModel = require('./MsInvDyeChemTransInItemModel');

class MsInvDyeChemTransInItemController {
	constructor(MsInvDyeChemTransInItemModel)
	{
		this.MsInvDyeChemTransInItemModel = MsInvDyeChemTransInItemModel;
		this.formId='invdyechemtransinitemFrm';	             
		this.dataTable='#invdyechemtransinitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemtransinitem"
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
		let inv_rcv_id=$('#invdyechemtransinFrm [name=id]').val()
		let inv_dye_chem_rcv_id=$('#invdyechemtransinFrm [name=inv_dye_chem_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvDyeChemTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invdyechemtransinitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invdyechemtransinitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemTransInItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemTransInItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemTransInItem.resetForm();
		MsInvDyeChemTransInItem.get(d.inv_dye_chem_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemTransInItemModel.get(index,row);

	}
	get(inv_dye_chem_rcv_id){
		let params={};
		params.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemtransinitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemTransInItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemtransinitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemtransinitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemtransinitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemtransinitemFrm [name=item_id]').val(row.item_account_id);
				$('#invdyechemtransinitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemtransinitemFrm [name=specification]').val(row.specification);
				$('#invdyechemtransinitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemtransinitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemtransinitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemtransinitemFrm [name=qty]').val(row.qty);
				$('#invdyechemtransinitemFrm [name=rate]').val(row.rate);
				$('#invdyechemtransinitemFrm [name=amount]').val(row.amount);
				$('#invdyechemtransinitemFrm [name=transfer_no]').val(row.transfer_no);
				$('#invdyechemtransinitemFrm [name=inv_dye_chem_isu_item_id]').val(row.inv_dye_chem_isu_item_id);
				$('#invdyechemtransinitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invdyechemtransinitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invdyechemtransinFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemtransinitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	
calculate_qty_form()
	{
		
		let qty=$('#invdyechemtransinitemFrm input[name=qty]').val();
		let rate=$('#invdyechemtransinitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invdyechemtransinitemFrm input[name=amount]').val(amount);
	}
	

}
window.MsInvDyeChemTransInItem=new MsInvDyeChemTransInItemController(new MsInvDyeChemTransInItemModel());
MsInvDyeChemTransInItem.itemSearchGrid([]);
MsInvDyeChemTransInItem.showGrid([]);