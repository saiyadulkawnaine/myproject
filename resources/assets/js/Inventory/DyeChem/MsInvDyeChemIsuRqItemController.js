let MsInvDyeChemIsuRqItemModel = require('./MsInvDyeChemIsuRqItemModel');

class MsInvDyeChemIsuRqItemController {
	constructor(MsInvDyeChemIsuRqItemModel)
	{
		this.MsInvDyeChemIsuRqItemModel = MsInvDyeChemIsuRqItemModel;
		this.formId='invdyechemisurqitemFrm';	             
		this.dataTable='#invdyechemisurqitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqitem"
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
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;

		if(formObj.id){
			this.MsInvDyeChemIsuRqItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let sub_process_id = $('#invdyechemisurqitemFrm [name=sub_process_id]').val();
		msApp.resetForm(this.formId);
		$('#invdyechemisurqitemFrm [name=sub_process_id]').val(sub_process_id)
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuRqItem.resetForm();
		MsInvDyeChemIsuRqItem.get(d.inv_dye_chem_isu_rq_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemIsuRqItemModel.get(index,row);

	}
	get(inv_dye_chem_isu_rq_id){
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemisurqitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemisurqitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemisurqitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisurqitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisurqitemFrm [name=specification]').val(row.specification);
				$('#invdyechemisurqitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisurqitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisurqitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisurqitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_category=$('#invdyechemisurqitemsearchFrm [name=item_category]').val();
		let item_class=$('#invdyechemisurqitemsearchFrm [name=item_class]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemisurqitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	

	calculate_qty_form(field)
	{
		if(field=='per_on_batch_wgt')
		{
             $('#invdyechemisurqitemFrm input[name=gram_per_ltr_liqure]').val('');
             let batch_wgt=$('#invdyechemisurqFrm input[name=batch_wgt]').val();
             batch_wgt=batch_wgt*1;
             let per_on_batch_wgt=$('#invdyechemisurqitemFrm input[name=per_on_batch_wgt]').val();
             per_on_batch_wgt=per_on_batch_wgt*1;
             let qty=batch_wgt*(per_on_batch_wgt/100);
             $('#invdyechemisurqitemFrm input[name=qty]').val(qty);


		}
		if(field=='gram_per_ltr_liqure')
		{
             $('#invdyechemisurqitemFrm input[name=per_on_batch_wgt]').val('');
             let liqure_wgt=$('#invdyechemisurqFrm input[name=liqure_wgt]').val();
             liqure_wgt=liqure_wgt*1;
             let gram_per_ltr_liqure=$('#invdyechemisurqitemFrm input[name=gram_per_ltr_liqure]').val();
             gram_per_ltr_liqure=gram_per_ltr_liqure*1;
             let qty=(liqure_wgt*gram_per_ltr_liqure)/1000;
             $('#invdyechemisurqitemFrm input[name=qty]').val(qty);


		}
	}
	openrequisitionWindow()
	{
		$('#invDyechemMasterRqSearchWindow').window('open');
	}
	serachMasterRq(){

		let company_id=$('#invDyechemMasterRqSearchFrm [name=company_id]').val();
		let fabric_color=$('#invDyechemMasterRqSearchFrm [name=fabric_color]').val();
		let colorrange_id=$('#invDyechemMasterRqSearchFrm [name=colorrange_id]').val();
        let inv_dye_chem_isu_rq_id=$('#invdyechemisurqFrm [name=id]').val();		let params={};
		params.company_id=company_id;
		params.fabric_color=fabric_color;
		params.colorrange_id=colorrange_id;
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route+'/getmasterrq',{params})
		.then(function(response){
			$('#invDyechemMasterRqSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	masterRqGrid(data){
		let self=this;
		$('#invDyechemMasterRqSearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqitemFrm [name=master_rq_id]').val(row.id);
				$('#invdyechemisurqitemFrm [name=master_rq_no]').val(row.rq_no);
				$('#invDyechemMasterRqSearchWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqFrm [name=id]').val();
		let master_rq_id=$('#invdyechemisurqitemFrm [name=master_rq_id]').val();
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		params.master_rq_id=master_rq_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsInvDyeChemIsuRqItem.resetForm();
					MsInvDyeChemIsuRqItem.get(response.data.inv_dye_chem_isu_rq_id);
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
window.MsInvDyeChemIsuRqItem=new MsInvDyeChemIsuRqItemController(new MsInvDyeChemIsuRqItemModel());
MsInvDyeChemIsuRqItem.itemSearchGrid([]);
MsInvDyeChemIsuRqItem.showGrid([]);
MsInvDyeChemIsuRqItem.masterRqGrid([]);