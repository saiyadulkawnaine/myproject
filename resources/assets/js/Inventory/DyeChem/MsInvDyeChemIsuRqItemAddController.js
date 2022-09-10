let MsInvDyeChemIsuRqItemAddModel = require('./MsInvDyeChemIsuRqItemAddModel');

class MsInvDyeChemIsuRqItemAddController {
	constructor(MsInvDyeChemIsuRqItemAddModel)
	{
		this.MsInvDyeChemIsuRqItemAddModel = MsInvDyeChemIsuRqItemAddModel;
		this.formId='invdyechemisurqitemaddFrm';	             
		this.dataTable='#invdyechemisurqitemaddTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqitemadd"
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
		let inv_dye_chem_isu_rq_id=$('#invdyechemisurqaddFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;

		if(formObj.id){
			this.MsInvDyeChemIsuRqItemAddModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqItemAddModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqItemAddModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqItemAddModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuRqItemAdd.resetForm();
		MsInvDyeChemIsuRqItemAdd.get(d.inv_dye_chem_isu_rq_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemIsuRqItemAddModel.get(index,row);

	}
	get(inv_dye_chem_isu_rq_id){
		let params={};
		params.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rq_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			//$('#invdyechemisurqitemaddTbl').datagrid('loadData',response.data);
			$('#invdyechemisurqitemaddmatrix').html(response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqItemAdd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	/*openitemWindow()
	{
		$('#invdyechemisurqadditemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemisurqadditemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqadditemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisurqadditemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisurqadditemFrm [name=specification]').val(row.specification);
				$('#invdyechemisurqadditemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisurqadditemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisurqadditemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisurqadditemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let po_no=$('#invdyechemisurqadditemsearchFrm [name=po_no]').val();
		let pi_no=$('#invdyechemisurqadditemsearchFrm [name=pi_no]').val();
		let params={};
		params.po_no=po_no;
		params.pi_no=pi_no;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemisurqadditemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}*/
	calculate_add_qty(iteration,count)
	{
	 let qty=$('#invdyechemisurqitemaddmatrix input[name="qty['+iteration+']"]').val();
	 qty=qty*1;
	 let add_per=$('#invdyechemisurqitemaddmatrix input[name="add_per['+iteration+']"]').val();
	 add_per=add_per*1;
	 let add_qty=qty*(add_per/100).toFixed(4);
	 $('#invdyechemisurqitemaddmatrix input[name="add_qty['+iteration+']"]').val(add_qty);
	}
}
window.MsInvDyeChemIsuRqItemAdd=new MsInvDyeChemIsuRqItemAddController(new MsInvDyeChemIsuRqItemAddModel());
//MsInvDyeChemIsuRqItemAdd.itemSearchGrid([]);
//MsInvDyeChemIsuRqItemAdd.showGrid([]);