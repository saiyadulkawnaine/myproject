let MsInvGeneralIsuRqItemModel = require('./MsInvGeneralIsuRqItemModel');

class MsInvGeneralIsuRqItemController {
	constructor(MsInvGeneralIsuRqItemModel)
	{
		this.MsInvGeneralIsuRqItemModel = MsInvGeneralIsuRqItemModel;
		this.formId='invgeneralisurqitemFrm';	             
		this.dataTable='#invgeneralisurqitemTbl';
		this.route=msApp.baseUrl()+"/invgeneralisurqitem"
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
		let inv_general_isu_rq_id=$('#invgeneralisurqFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_general_isu_rq_id=inv_general_isu_rq_id;

		if(formObj.id){
			this.MsInvGeneralIsuRqItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuRqItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneralisurqitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneralisurqitemFrm [name=store_id]').val(store_id);
		$('#invgeneralisurqitemFrm [id="department_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuRqItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuRqItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralIsuRqItem.resetForm();
		MsInvGeneralIsuRqItem.get(d.inv_general_isu_rq_id)
		$('#invgeneralisurqitemFrm [id="department_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvGeneralIsuRqItemModel.get(index,row);
		data.then(function(response){
			$('#invgeneralisurqitemFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
		}).catch(function(error){
			console.log(error);
		})

	}
	get(inv_general_isu_rq_id){
		let params={};
		params.inv_general_isu_rq_id=inv_general_isu_rq_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgeneralisurqitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsuRqItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneralisurqitemsearchwindow').window('open');
		$('#invgeneralisurqitemsearchTbl').datagrid('loadData',[]);
	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneralisurqitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisurqitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneralisurqitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneralisurqitemFrm [name=item_desc]').val(row.item_desc);
				$('#invgeneralisurqitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneralisurqitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invgeneralisurqitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invgeneralisurqitemsearchFrm [name=item_desc]').val();
		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneralisurqitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	openorderWindow()
	{
		$('#invgeneralisurqordersearchwindow').window('open');

	}

	orderSearchGrid(data){
		let self=this;
		$('#invgeneralisurqordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisurqitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgeneralisurqitemFrm [name=sale_order_id]').val(row.sale_order_id);
				$('#invgeneralisurqordersearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachOrder(){
		let order_no=$('#invgeneralisurqordersearchFrm [name=order_no]').val();
		let style_ref=$('#invgeneralisurqordersearchFrm [name=style_ref]').val();
		let inv_general_isu_rq_id=$('#invgeneralisurqFrm [name=id]').val();
		let params={};
		params.order_no=order_no;
		params.style_ref=style_ref;
		params.inv_general_isu_rq_id=inv_general_isu_rq_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invgeneralisurqordersearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	openmachineWindow()
	{
		$('#invgeneralisurqmachinesearchwindow').window('open');
	}


	machineSearchGrid(data){
		let self=this;
		$('#invgeneralisurqmachinesearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisurqitemFrm [name=custom_no]').val(row.custom_no);
				$('#invgeneralisurqitemFrm [name=asset_quantity_cost_id]').val(row.id);
				$('#invgeneralisurqmachinesearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachMachine(){
		let dia_width=$('#invgeneralisurqmachinesearchFrm [name=dia_width]').val();
		let no_of_feeder=$('#invgeneralisurqmachinesearchFrm [name=no_of_feeder]').val();
		let inv_general_isu_rq_id=$('#invgeneralisurqFrm [name=id]').val();
		let params={};
		params.dia_width=dia_width;
		params.no_of_feeder=no_of_feeder;
		params.inv_general_isu_rq_id=inv_general_isu_rq_id;
		let d=axios.get(this.route+'/getmachine',{params})
		.then(function(response){
			$('#invgeneralisurqmachinesearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
}
window.MsInvGeneralIsuRqItem=new MsInvGeneralIsuRqItemController(new MsInvGeneralIsuRqItemModel());
MsInvGeneralIsuRqItem.itemSearchGrid([]);
MsInvGeneralIsuRqItem.orderSearchGrid([]);
MsInvGeneralIsuRqItem.machineSearchGrid([]);
MsInvGeneralIsuRqItem.showGrid([]);