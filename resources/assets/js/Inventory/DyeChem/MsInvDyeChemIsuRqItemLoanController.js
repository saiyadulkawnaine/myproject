let MsInvDyeChemIsuRqItemLoanModel = require('./MsInvDyeChemIsuRqItemLoanModel');

class MsInvDyeChemIsuRqItemLoanController {
	constructor(MsInvDyeChemIsuRqItemLoanModel)
	{
		this.MsInvDyeChemIsuRqItemLoanModel = MsInvDyeChemIsuRqItemLoanModel;
		this.formId='invdyechemisurqitemloanFrm';	             
		this.dataTable='#invdyechemisurqitemloanTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqitemloan"
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
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqloanFrm [name=id]').val();
		let rq_basis_id=$('#invdyechemisurqloanFrm [name=rq_basis_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		formObj.rq_basis_id=rq_basis_id;
		if(formObj.id){
			this.MsInvDyeChemIsuRqItemLoanModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqItemLoanModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqItemLoanModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqItemLoanModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuRqItemLoan.resetForm();
		MsInvDyeChemIsuRqItemLoan.get(d.inv_dye_chem_isu_rq_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemIsuRqItemLoanModel.get(index,row);
	}
	get(inv_dye_chem_isu_rq_id)
	{
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemisurqitemloanTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(data)
	{
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqItemLoan.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemisurqitemloansearchwindow').window('open');

	}

	itemSearchGrid(data)
	{
		let self=this;
		$('#invdyechemisurqitemloansearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemloanFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisurqitemloanFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisurqitemloanFrm [name=specification]').val(row.specification);
				$('#invdyechemisurqitemloanFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisurqitemloanFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisurqitemloanFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisurqitemloansearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem()
	{
		let item_category=$('#invdyechemisurqitemloansearchFrm [name=item_category]').val();
		let item_class=$('#invdyechemisurqitemloansearchFrm [name=item_class]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemisurqitemloansearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty_form(field)
	{
		if(field=='per_on_batch_wgt')
		{
             $('#invdyechemisurqitemloanFrm input[name=gram_per_ltr_liqure]').val('');
             let batch_wgt=$('#invdyechemisurqFrm input[name=batch_wgt]').val();
             batch_wgt=batch_wgt*1;
             let per_on_batch_wgt=$('#invdyechemisurqitemloanFrm input[name=per_on_batch_wgt]').val();
             per_on_batch_wgt=per_on_batch_wgt*1;
             let qty=batch_wgt*(per_on_batch_wgt/100);
             $('#invdyechemisurqitemloanFrm input[name=qty]').val(qty);
		}
		if(field=='gram_per_ltr_liqure')
		{
             $('#invdyechemisurqitemloanFrm input[name=per_on_batch_wgt]').val('');
             let liqure_wgt=$('#invdyechemisurqFrm input[name=liqure_wgt]').val();
             liqure_wgt=liqure_wgt*1;
             let gram_per_ltr_liqure=$('#invdyechemisurqitemloanFrm input[name=gram_per_ltr_liqure]').val();
             gram_per_ltr_liqure=gram_per_ltr_liqure*1;
             let qty=(liqure_wgt*gram_per_ltr_liqure)/1000;
             $('#invdyechemisurqitemloanFrm input[name=qty]').val(qty);
		}
	}

	openmachineWindow()
	{
		$('#invdyechemisurqmachineloansearchwindow').window('open');
	}


	machineSearchGrid(data){
		let self=this;
		$('#invdyechemisurqmachineloansearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemloanFrm [name=custom_no]').val(row.custom_no);
				$('#invdyechemisurqitemloanFrm [name=asset_quantity_cost_id]').val(row.id);
				$('#invdyechemisurqmachineloansearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachMachine(){
		let dia_width=$('#invdyechemisurqmachineloansearchFrm [name=dia_width]').val();
		let no_of_feeder=$('#invdyechemisurqmachineloansearchFrm [name=no_of_feeder]').val();
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqloanFrm [name=id]').val();
		let params={};
		params.dia_width=dia_width;
		params.no_of_feeder=no_of_feeder;
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route+'/getmachine',{params})
		.then(function(response){
			$('#invdyechemisurqmachineloansearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
}
window.MsInvDyeChemIsuRqItemLoan=new MsInvDyeChemIsuRqItemLoanController(new MsInvDyeChemIsuRqItemLoanModel());
MsInvDyeChemIsuRqItemLoan.itemSearchGrid([]);
MsInvDyeChemIsuRqItemLoan.machineSearchGrid([]);
MsInvDyeChemIsuRqItemLoan.showGrid([]);