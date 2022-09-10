let MsInvGeneralTransOutItemModel = require('./MsInvGeneralTransOutItemModel');

class MsInvGeneralTransOutItemController {
	constructor(MsInvGeneralTransOutItemModel)
	{
		this.MsInvGeneralTransOutItemModel = MsInvGeneralTransOutItemModel;
		this.formId='invgeneraltransoutitemFrm';	             
		this.dataTable='#invgeneraltransoutitemTbl';
		this.route=msApp.baseUrl()+"/invgeneraltransoutitem"
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
		let inv_isu_id=$('#invgeneraltransoutFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;

		if(formObj.id){
			this.MsInvGeneralTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneraltransoutitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneraltransoutitemFrm [name=store_id]').val(store_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralTransOutItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralTransOutItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralTransOutItem.resetForm();
		MsInvGeneralTransOutItem.get(d.inv_isu_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvGeneralTransOutItemModel.get(index,row);
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
			$('#invgeneraltransoutitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralTransOutItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneraltransoutitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneraltransoutitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				self.getRate(row.item_account_id);
				$('#invgeneraltransoutitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneraltransoutitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneraltransoutitemFrm [name=item_desc]').val(row.item_desc);
				$('#invgeneraltransoutitemFrm [name=specification]').val(row.specification);
				$('#invgeneraltransoutitemFrm [name=item_category]').val(row.category_name);
				$('#invgeneraltransoutitemFrm [name=item_class]').val(row.class_name);
				$('#invgeneraltransoutitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneraltransoutitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invgeneraltransoutitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invgeneraltransoutitemsearchFrm [name=item_desc]').val();
		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneraltransoutitemsearchTbl').datagrid('loadData',response.data);
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
				$('#invgeneraltransoutitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}


	calculate_qty_form()
	{
		
		let qty=$('#invgeneraltransoutitemFrm input[name=qty]').val();
		let rate=$('#invgeneraltransoutitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invgeneraltransoutitemFrm input[name=amount]').val(amount);
	}

	

	
}
window.MsInvGeneralTransOutItem=new MsInvGeneralTransOutItemController(new MsInvGeneralTransOutItemModel());
MsInvGeneralTransOutItem.showGrid([]);
MsInvGeneralTransOutItem.itemSearchGrid([]);
