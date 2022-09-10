let MsProdKnitItemModel = require('./MsProdKnitItemModel');
require('./../../datagrid-filter.js');
class MsProdKnitItemController {
	constructor(MsProdKnitItemModel)
	{
		this.MsProdKnitItemModel = MsProdKnitItemModel;
		this.formId='prodknititemFrm';
		this.dataTable='#prodknititemTbl';
		this.route=msApp.baseUrl()+"/prodknititem"
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
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdKnitItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodknititemFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#prodknititemTbl').datagrid('reload');

		msApp.resetForm('prodknititemFrm');
		let prod_knit_id = $('#prodknitFrm  [name=id]').val();
		$('#prodknititemFrm  [name=prod_knit_id]').val(prod_knit_id);
		MsProdKnitItem.get(prod_knit_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let item=this.MsProdKnitItemModel.get(index,row);
		
	}
	get(prod_knit_id)
	{
		let data= axios.get(this.route+"?prod_knit_id="+prod_knit_id);
		data.then(function (response) {
			$('#prodknititemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//url:this.route+'?prod_knit_id='+prod_knit_id,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnitItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodknititemWindowOpen(){
		$('#prodknititemWindow').window('open');
	}

	searchItem() 
	{
		let buyer_id=$('#prodknititemsearchFrm  [name=buyer_id]').val();
		let pl_no=$('#prodknititemsearchFrm  [name=pl_no]').val();
		let po_no=$('#prodknititemsearchFrm  [name=po_no]').val();
		let dia=$('#prodknititemsearchFrm  [name=dia]').val();
		let gsm=$('#prodknititemsearchFrm  [name=gsm]').val();
		let prod_id=$('#prodknitFrm  [name=id]').val();
		let data= axios.get(this.route+"/getitem?prod_id="+prod_id+"&buyer_id="+buyer_id+"&pl_no="+pl_no+"&po_no="+po_no+"&dia="+dia+"&gsm="+gsm);
		data.then(function (response) {
			$('#prodknititemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showprodknititemGrid(data){
		$('#prodknititemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#prodknititemFrm [name=pl_knit_item_id]').val(row.pl_knit_item_id);
				$('#prodknititemFrm [name=po_knit_service_item_qty_id]').val(row.po_knit_service_item_qty_id);
				$('#prodknititemFrm  [name=fabrication]').val(row.fabrication);
				$('#prodknititemFrm  [name=gsm_weight]').val(row.gsm_weight);
				$('#prodknititemFrm  [name=dia]').val(row.dia);
				$('#prodknititemFrm  [name=stitch_length]').val(row.stitch_length);
				$('#prodknititemFrm  [name=fabric_look_id]').val(row.fabric_look_id);
				$('#prodknititemFrm  [name=fabric_shape_id]').val(row.fabric_shape_id);
				$('#prodknititemFrm  [name=style_ref]').val(row.style_ref);
				$('#prodknititemFrm  [name=order_no]').val(row.order_no);

				$('#prodknititemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}



	prodknitmachineWindowOpen(){
		$('#prodknitmachineWindow').window('open');
	}
	showprodknitmachineGrid(data){
		let self = this;
		$('#prodknitmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodknititemFrm [name=asset_quantity_cost_id]').val(row.id);
					$('#prodknititemFrm [name=custom_no]').val(row.custom_no);
					
					$('#prodknititemFrm [name=machine_gg]').val(row.gauge);
					$('#prodknititemFrm [name=machine_dia]').val(row.dia_width);
					$('#prodknitmachineWindow').window('close');
					self.getOperator(row.id);
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.dia_width=$('#prodknititemsearchFrm  [name=dia_width]').val();
		params.no_of_feeder=$('#prodknititemsearchFrm  [name=no_of_feeder]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodknitmachinesearchTbl').datagrid('loadData', response.data);

		})
		.catch(function (error) {
			console.log(error);
		});

	}

	getOperator(asset_quantity_cost_id)
	{
		let params={};
		params.asset_quantity_cost_id=asset_quantity_cost_id;
		params.prod_date=$('#prodknitFrm  [name=prod_date]').val();
		let data= axios.get(this.route+"/getoperator",{params});
		data.then(function (response) {
			$('#prodknitmachineoperatorsearchTbl').datagrid('loadData', response.data);
			$('#prodknitmachineoperatorWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	prodknitmachineoperatorWindowOpen(){
		this.getOperator($('#prodknititemFrm [name=asset_quantity_cost_id]').val());
	}

	showprodknitmachineoperatorGrid(data){
		let self = this;
		$('#prodknitmachineoperatorsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodknititemFrm [name=operator_name]').val(row.name);
					$('#prodknititemFrm [name=operator_id]').val(row.id);
					$('#prodknitmachineoperatorWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	


	
}
window.MsProdKnitItem=new MsProdKnitItemController(new MsProdKnitItemModel());
MsProdKnitItem.showGrid([]);
MsProdKnitItem.showprodknititemGrid([]);
MsProdKnitItem.showprodknitmachineGrid([]);
MsProdKnitItem.showprodknitmachineoperatorGrid([]);