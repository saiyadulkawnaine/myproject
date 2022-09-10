let MsProdFinishQcBillItemModel = require('./MsProdFinishQcBillItemModel');
class MsProdFinishQcBillItemController {
	constructor(MsProdFinishQcBillItemModel)
	{
		this.MsProdFinishQcBillItemModel = MsProdFinishQcBillItemModel;
		this.formId='prodfinishqcbillitemFrm';
		this.dataTable='#prodfinishqcbillitemTbl';
		this.route=msApp.baseUrl()+"/prodfinishqcbillitem"
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
			this.MsProdFinishQcBillItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishQcBillItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodfinishqcbillitemFrm  [name=prod_finish_dlv_id]').val($('#prodfinishqcbillFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishQcBillItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishQcBillItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishqcbillitemTbl').datagrid('reload');
		msApp.resetForm(this.formId);
		$('#prodfinishqcbillitemFrm  [name=prod_finish_dlv_id]').val($('#prodfinishqcbillFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        this.MsProdFinishQcBillItemModel.get(index,row);

	}

	showGrid(prod_finish_dlv_id){

		let self=this;
		let data={};
		data.prod_finish_dlv_id=prod_finish_dlv_id
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			queryParams:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdFinishQcBillItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	prodfinishqcbillitemWindowOpen(){
		$('#prodfinishqcbillitemWindow').window('open');
	}

	searchItem()
	{
		//let prodfinishqcbillid=$('#prodfinishqcbillFrm  [name=id]').val();
		let sale_order_no=$('#prodfinishqcbillitemsearchFrm  [name=sale_order_no]').val();
		let receive_date=$('#prodfinishqcbillitemsearchFrm  [name=receive_date]').val();
		let buyer_id=$('#prodfinishqcbillitemsearchFrm  [name=buyer_id]').val();
		if (buyer_id=='') {
			alert("Select a customer");
			return;
		}
		let data= axios.get(this.route+"/getitem?sale_order_no="+sale_order_no+"&receive_date="+receive_date+"&buyer_id="+buyer_id);
		data.then(function (response) {
			$('#prodfinishqcbillitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSalesOrderFabric(data){
		$('#prodfinishqcbillitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodfinishqcbillitemFrm [name=so_dyeing_fabric_rcv_item_id]').val(row.id);
				$('#prodfinishqcbillitemFrm [name=so_dyeing_ref_id]').val(row.so_dyeing_ref_id);
				$('#prodfinishqcbillitemFrm  [name=fabrication]').val(row.fabrication);
				$('#prodfinishqcbillitemFrm  [name=dia]').val(row.dia);
				$('#prodfinishqcbillitemFrm  [name=gsm_weight]').val(row.gsm_weight);
				$('#prodfinishqcbillitemsearchTbl').datagrid('loadData', []);
				$('#prodfinishqcbillitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	prodbatchfinishqcWindowOpen(){
		$('#openprodbatchfinishqcbatchWindow').window('open');
	}

	showQcProdBatchGrid(data){
		let self = this;
		$('#qcbillprodbatchsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
				$('#prodfinishqcbillitemFrm [name=prod_batch_finish_qc_id]').val(row.id);
				//$('#prodfinishqcbillitemFrm [name=prod_batch_id]').val(row.id);
				$('#prodfinishqcbillitemFrm [name=batch_no]').val(row.batch_no);
				$('#prodfinishqcbillitemFrm [id="fabric_color_name"]').val(row.fabric_color_name);
				$('#openprodbatchfinishqcbatchWindow').window('close');
				$('#qcbillprodbatchsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#qcbillprodbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#qcbillprodbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#qcbillprodbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getqcprodbatch",{params});
		data.then(function (response) {
			$('#qcbillprodbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#prodfinishqcbillitemFrm [name=qty]').val();
		rate=$('#prodfinishqcbillitemFrm [name=rate]').val();
		let amount=qty*rate;
		$('#prodfinishqcbillitemFrm [name=amount]').val(amount);
	}
}

window.MsProdFinishQcBillItem = new MsProdFinishQcBillItemController(new MsProdFinishQcBillItemModel());
//MsProdFinishQcBillItem.showGrid([]);
MsProdFinishQcBillItem.showQcProdBatchGrid([]);
MsProdFinishQcBillItem.showGridSalesOrderFabric([]);