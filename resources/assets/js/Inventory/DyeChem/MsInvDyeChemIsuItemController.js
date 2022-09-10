let MsInvDyeChemIsuItemModel = require('./MsInvDyeChemIsuItemModel');

class MsInvDyeChemIsuItemController {
	constructor(MsInvDyeChemIsuItemModel)
	{
		this.MsInvDyeChemIsuItemModel = MsInvDyeChemIsuItemModel;
		this.formId='invdyechemisuitemFrm';	             
		this.dataTable='#invdyechemisuitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemisuitem"
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
		let inv_dye_chem_isu_id=$('#invdyechemisuFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_isu_id=inv_dye_chem_isu_id;

		if(formObj.id){
			this.MsInvDyeChemIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitMartix(i)
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
		let inv_isu_id=$('#invdyechemisuFrm [name=id]').val();
		
		let id=$('#invdyechemisuitemmatrixFrm input[name="id['+i+']"]').val();
		let inv_dye_chem_isu_rq_item_id=$('#invdyechemisuitemmatrixFrm input[name="inv_dye_chem_isu_rq_item_id['+i+']"]').val();
		let item_account_id=$('#invdyechemisuitemmatrixFrm input[name="item_account_id['+i+']"]').val();
		let store_id=$('#invdyechemisuitemmatrixFrm select[name="store_id['+i+']"]').val();
		let batch=$('#invdyechemisuitemmatrixFrm input[name="batch['+i+']"]').val();
		let qty=$('#invdyechemisuitemmatrixFrm input[name="qty['+i+']"]').val();
		let remarks=$('#invdyechemisuitemmatrixFrm input[name="remarks['+i+']"]').val();
		let room=$('#invdyechemisuitemmatrixFrm input[name="room['+i+']"]').val();
		let rack=$('#invdyechemisuitemmatrixFrm input[name="rack['+i+']"]').val();
		let shelf=$('#invdyechemisuitemmatrixFrm input[name="shelf['+i+']"]').val();
		let formObj={};
		formObj.inv_isu_id=inv_isu_id;
		formObj.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_item_id;
		formObj.item_account_id=item_account_id;
		formObj.store_id=store_id;
		formObj.batch=batch;
		formObj.qty=qty;
		formObj.room=room;
		formObj.rack=rack;
		formObj.shelf=shelf;
		formObj.remarks=remarks;
		formObj.id=id;
		formObj.seq=i;
		if(formObj.id){
			this.MsInvDyeChemIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}

	}
	
	

	resetForm ()
	{
		let sub_process_id = $('#invdyechemisuitemFrm [name=sub_process_id]').val();
		msApp.resetForm(this.formId);
		$('#invdyechemisuitemFrm [name=sub_process_id]').val(sub_process_id)
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuItem.resetForm();
		MsInvDyeChemIsuItem.get(d.inv_isu_id)
        $('#invdyechemisuitemmatrixFrm input[name="id['+d.seq+']"]').val(d.id);
        //let index=(d.seq*1)-1;
        let table = $("#invdyechemisuitemmatrixtbl");
        let row = table.find('tr').eq(d.seq);
        row.css('background-color', 'green')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemIsuItemModel.get(index,row);

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemisuitemTbl').datagrid('loadData',response.data);
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
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				
				var qty=0;
				var amount=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

				}
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemisuitemmatrix').html('');
		$('#invdyechemisuitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemisuitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisuitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisuitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisuitemFrm [name=specification]').val(row.specification);
				$('#invdyechemisuitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisuitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisuitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisuitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let rq_no=$('#invdyechemisuitemsearchFrm [name=rq_no]').val();
		let inv_isu_id=$('#invdyechemisuFrm [name=id]').val();
		let params={};
		params.rq_no=rq_no;
		params.inv_isu_id=inv_isu_id;
		if(params.rq_no==''){
			alert('Insert Requisition No');
			return;
		}
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			//$('#invdyechemisuitemsearchTbl').datagrid('loadData',response.data);
			$('#invdyechemisuitemmatrix').html(response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	

	calculate_qty_form()
	{
		
	}
	copyStore(i,count){
		var store_id=$('#invdyechemisuitemmatrixFrm select[name="store_id['+i+']"]').val();
		for(var j=1;j<=count;j++)
		{
			$('#invdyechemisuitemmatrixFrm select[name="store_id['+j+']"]').val(store_id)
		}

	}
}
window.MsInvDyeChemIsuItem=new MsInvDyeChemIsuItemController(new MsInvDyeChemIsuItemModel());
MsInvDyeChemIsuItem.itemSearchGrid([]);
MsInvDyeChemIsuItem.showGrid([]);