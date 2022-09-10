let MsInvDyeChemTransOutItemModel = require('./MsInvDyeChemTransOutItemModel');

class MsInvDyeChemTransOutItemController {
	constructor(MsInvDyeChemTransOutItemModel)
	{
		this.MsInvDyeChemTransOutItemModel = MsInvDyeChemTransOutItemModel;
		this.formId='invdyechemtransoutitemFrm';	             
		this.dataTable='#invdyechemtransoutitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemtransoutitem"
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
		let inv_isu_id=$('#invdyechemtransoutFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;

		if(formObj.id){
			this.MsInvDyeChemTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invdyechemtransoutitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invdyechemtransoutitemFrm [name=store_id]').val(store_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemTransOutItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemTransOutItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemTransOutItem.resetForm();
		MsInvDyeChemTransOutItem.get(d.inv_isu_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvDyeChemTransOutItemModel.get(index,row);
		data.then(function(response){
		}).catch(function(error){
			console.log(error);
		})

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemtransoutitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemTransOutItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemtransoutitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemtransoutitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				self.getRate(row.item_account_id);
				$('#invdyechemtransoutitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemtransoutitemFrm [name=item_id]').val(row.item_account_id);
				$('#invdyechemtransoutitemFrm [name=item_desc]').val(row.item_desc);
				$('#invdyechemtransoutitemFrm [name=specification]').val(row.specification);
				$('#invdyechemtransoutitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemtransoutitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemtransoutitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemtransoutitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invdyechemtransoutitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invdyechemtransoutitemsearchFrm [name=item_desc]').val();
		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemtransoutitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	getRate(item_account_id){
		let self=this;
		let params={};
		params.item_account_id=item_account_id
		let d = axios.get(this.route+"/getrate",{params})
		.then(function(response){
				$('#invdyechemtransoutitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}


	calculate_qty_form()
	{
		
		let qty=$('#invdyechemtransoutitemFrm input[name=qty]').val();
		let rate=$('#invdyechemtransoutitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invdyechemtransoutitemFrm input[name=amount]').val(amount);
	}

	

	
}
window.MsInvDyeChemTransOutItem=new MsInvDyeChemTransOutItemController(new MsInvDyeChemTransOutItemModel());
MsInvDyeChemTransOutItem.showGrid([]);
MsInvDyeChemTransOutItem.itemSearchGrid([]);
