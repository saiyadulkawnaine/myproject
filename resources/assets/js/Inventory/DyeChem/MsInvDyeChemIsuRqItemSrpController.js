let MsInvDyeChemIsuRqItemSrpModel = require('./MsInvDyeChemIsuRqItemSrpModel');

class MsInvDyeChemIsuRqItemSrpController {
	constructor(MsInvDyeChemIsuRqItemSrpModel)
	{
		this.MsInvDyeChemIsuRqItemSrpModel = MsInvDyeChemIsuRqItemSrpModel;
		this.formId='invdyechemisurqitemsrpFrm';	             
		this.dataTable='#invdyechemisurqitemsrpTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqitemsrp"
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
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqsrpFrm [name=id]').val();
		let rq_basis_id=$('#invdyechemisurqsrpFrm [name=rq_basis_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		formObj.rq_basis_id=rq_basis_id;

		if(formObj.id){
			this.MsInvDyeChemIsuRqItemSrpModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqItemSrpModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let sale_order_no=$('#sale_order_no').val();
		let so_emb_id=$('#so_emb_id').val();
		let print_type_id=$('#print_type_id').val();
		msApp.resetForm(this.formId);
		$('#sale_order_no').val(sale_order_no);
		$('#so_emb_id').val(so_emb_id);
		$('#print_type_id').val(print_type_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqItemSrpModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqItemSrpModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuRqItemSrp.resetForm();
		MsInvDyeChemIsuRqItemSrp.get(d.inv_dye_chem_isu_rq_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqItemSrpModel.get(index,row);
		/*data.then(function (response) {
			let Presponse=response
		    MsInvDyeChemIsuRqItemSrp.getPrintType(response.inv_dye_chem_isu_rq_id)
			.then(function(){
				$('#invdyechemisurqitemsrpFrm [name=print_type_id]').val(Presponse.print_type_id)
			})
		})
		.catch(function (error) {
			console.log(error);
		});*/

	}
	get(inv_dye_chem_isu_rq_id){
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemisurqitemsrpTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqItemSrp.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemisurqitemsrpsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemisurqitemsrpsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemsrpFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisurqitemsrpFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisurqitemsrpFrm [name=specification]').val(row.specification);
				$('#invdyechemisurqitemsrpFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisurqitemsrpFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisurqitemsrpFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisurqitemsrpsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachItem(){
		let item_category=$('#invdyechemisurqitemsrpsearchFrm [name=item_category]').val();
		let item_class=$('#invdyechemisurqitemsrpsearchFrm [name=item_class]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemisurqitemsrpsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty_form(field)
	{
		var paste_wgt= $('#invdyechemisurqsrpFrm  [name=paste_wgt]').val();
		paste_wgt=paste_wgt*1;
		var rto_on_paste_wgt= $('#invdyechemisurqitemsrpFrm  [name=rto_on_paste_wgt]').val();
		rto_on_paste_wgt=rto_on_paste_wgt*1;
		var qty=(paste_wgt*rto_on_paste_wgt)/100;
		$('#invdyechemisurqitemsrpFrm  [name=qty]').val(qty);
	}

	openorderWindow()
	{
		$('#invdyechemisurqorderaopsearchwindow').window('open');
	}

	orderSearchGrid(data){
		let self=this;
		$('#invdyechemisurqorderaopsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemsrpFrm [name=sale_order_no]').val(row.sales_order_no);
				$('#invdyechemisurqitemsrpFrm [name=so_emb_id]').val(row.so_emb_id);
				$('#invdyechemisurqorderaopsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachOrder(){
		let order_no=$('#invdyechemisurqorderaopsearchFrm [name=order_no]').val();
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqsrpFrm [name=id]').val();
		let params={};
		params.order_no=order_no;
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invdyechemisurqorderaopsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	// getPrintType(inv_dye_chem_isu_rq_id){
	// 	let params={};
	// 	params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
	// 	let d=axios.get(this.route+'/getprinttype',{params})
	// 	.then(function(response){
	// 		$('select[name="print_type_id"]').empty();
	// 		$('select[name="print_type_id"]').append('<option value="">-Select-</option>');
	// 		//$('#invdyechemisurqitemsrpTbl').datagrid('loadData',response.data);
	// 			$.each(response.data, function(key, value) {
	// 				$('select[name="print_type_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
	// 			});
	// 	}).catch(function(error){
	// 		console.log(error);
	// 	})
	// 	return d;

	// }

	openrequisitionWindow()
	{
		$('#invDyechemMasterRqAopSearchWindow').window('open');
	}
	serachMasterRq(){

		let company_id=$('#invDyechemMasterRqAopSearchFrm [name=company_id]').val();
		let fabric_color=$('#invDyechemMasterRqAopSearchFrm [name=fabric_color]').val();
		let colorrange_id=$('#invDyechemMasterRqAopSearchFrm [name=colorrange_id]').val();
        let inv_dye_chem_isu_rq_id=$('#invdyechemisurqsrpFrm [name=id]').val();		
		let params={};
		params.company_id=company_id;
		params.fabric_color=fabric_color;
		params.colorrange_id=colorrange_id;
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route+'/getmasterrq',{params})
		.then(function(response){
			$('#invDyechemMasterRqAopSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	masterRqGrid(data){
		let self=this;
		$('#invDyechemMasterRqAopSearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemsrpFrm [name=master_rq_id]').val(row.id);
				$('#invdyechemisurqitemsrpFrm [name=master_rq_no]').val(row.rq_no);
				$('#invDyechemMasterRqAopSearchWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqsrpFrm [name=id]').val();
		let master_rq_id=$('#invdyechemisurqitemsrpFrm [name=master_rq_id]').val();
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		params.master_rq_id=master_rq_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsInvDyeChemIsuRqItemSrp.resetForm();
					MsInvDyeChemIsuRqItemSrp.get(response.data.inv_dye_chem_isu_rq_id);
				}
				else if (response.data.success == false) {
				msApp.showError(response.data.message);
				}

		}).catch(function(error){
			//alert('Copied Not Successfully');
			msApp.showError(error);
			console.log(error);
		})
		
	}
}
window.MsInvDyeChemIsuRqItemSrp=new MsInvDyeChemIsuRqItemSrpController(new MsInvDyeChemIsuRqItemSrpModel());
MsInvDyeChemIsuRqItemSrp.itemSearchGrid([]);
MsInvDyeChemIsuRqItemSrp.orderSearchGrid([]);
MsInvDyeChemIsuRqItemSrp.showGrid([]);
MsInvDyeChemIsuRqItemSrp.masterRqGrid([]);