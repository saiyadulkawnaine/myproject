
let MsExpPiOrderModel = require('./MsExpPiOrderModel');
class MsExpPiOrderController {
	constructor(MsExpPiOrderModel)
	{
		this.MsExpPiOrderModel = MsExpPiOrderModel;
		this.formId='exppiorderFrm';
		this.dataTable='#exppiorderTbl';
		this.route=msApp.baseUrl()+"/exppiorder"
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
			this.MsExpPiOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpPiOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitBatch()
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

		let exp_pi_id=$('#exppiFrm  [name=id]').val();
		let formObj=msApp.get('exptagorderFrm');
		formObj.exp_pi_id=exp_pi_id;
		this.MsExpPiOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpPiOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpPiOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#exppiorderTbl').datagrid('reload');
		msApp.resetForm('exppiorderFrm');
		$('#exppiorderFrm  [name=exp_pi_id]').val($('#exppiFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpPiOrderModel.get(index,row);	

	}

	showGrid(exp_pi_id){
		let self=this;
		let data = {};
		data.exp_pi_id=exp_pi_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
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
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
		
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpPiOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#exppiorderFrm [name=qty]').val();
		rate=$('#exppiorderFrm [name=rate]').val();
		let amount=qty*rate;
		$('#exppiorderFrm [name=amount]').val(amount);
	}

	openExpPiOrderWindow(){
		$('#exporderwindow').window('open');
		MsExpPiOrder.searchSaleOrderGrid();
	}


	searchOrder(){
		let style_ref=$('#exppiordersearchFrm  [name=style_ref]').val();
		let job_no=$('#exppiordersearchFrm  [name=job_no]').val();
		let exppiid=$('#exppiFrm  [name=id]').val();

		let data= axios.get(this.route+"/importorder"+"?style_ref="+style_ref+"&job_no="+job_no+"&exppiid="+exppiid)
		.then(function (response) {
			$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	searchSaleOrderGrid(data)
	{
		$('#exppiordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
		}).datagrid('enableFilter').datagrid('loadData', data);

	}

	closeExpPiOrderWindow(){

		/*let rows=$('#exppiordersearchTbl').datagrid('getSelections');
		$('#exppiordersearchTbl').datagrid({
		singleSelect:false,
		columns:[[
		{field:'id',title:'ID',width:80},
		{field:'style_ref',title:'Style Ref',width:80},
		{field:'job_no',title:'Jon No',width:80,align:'right'},
		{field:'sale_order_no',title:'Order No',width:80,align:'right'},
		{field:'item_description',title:'Item',width:100},
		{field:'uom_name',title:'UOM',width:60},
		{field:'qty',title:'Ord. Qty',width:60},
		{field:'tag_qty',title:'Tag Qty',width:60,editor:'numberbox'},
		{field:'rate',title:'rate',width:60},
		{field:'amount',title:'Amount',width:60}
		]]
		}).datagrid('enableCellEditing');
		$('#exppiordersearchTbl').datagrid('loadData', rows);*/

		let id=[];
		let name=[];
		let checked=$('#exppiordersearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
		});
		id=id.join(',');
		$('#exporderwindow').window('close');

		let data= axios.get(this.route+"/create"+"?sales_order_id="+id)
		.then(function (response) {
			$('#exptagorderScs').html(response.data);
			$('#exptagorderwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	calculate(iteration,count){
		let rate=$('#exptagorderFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#exptagorderFrm input[name="qty['+iteration+']"]').val();
        let amount=msApp.multiply(qty,rate);
		$('#exptagorderFrm input[name="amount['+iteration+']"]').val(amount)
	}

}
window.MsExpPiOrder = new MsExpPiOrderController(new MsExpPiOrderModel());

$('#exppiordersearchTbl').datagrid();
MsExpPiOrder.searchSaleOrderGrid({rows:{}})

