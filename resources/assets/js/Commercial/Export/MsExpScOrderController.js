
let MsExpScOrderModel = require('./MsExpScOrderModel');
class MsExpScOrderController {
	constructor(MsExpScOrderModel)
	{
		this.MsExpScOrderModel = MsExpScOrderModel;
		this.formId='expscorderFrm';
		this.dataTable='#expscorderTbl';
		this.route=msApp.baseUrl()+"/expscorder"
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
		if(formObj.id)
		{
			this.MsExpScOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsExpScOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		
		let explcsc=msApp.get('explcscFrm');
		let formObj=msApp.get('expsctagorderFrm');
        formObj.exp_lc_sc_id=explcsc.id;
		formObj.company_id=explcsc.beneficiary_id;
		formObj.itemclass_id=41;
		formObj.buyer_id=explcsc.buyer_id;
		formObj.pi_date=explcsc.lc_sc_date;
		formObj.pay_term_id=explcsc.pay_term_id;
        formObj.incoterm_id=explcsc.incoterm_id;
        formObj.delivery_date=explcsc.last_delivery_date;

		this.MsExpScOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpScOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpScOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expscorderTbl').datagrid('reload');
		msApp.resetForm('expscorderFrm');
		$('#expscorderFrm  [name=exp_pi_id]').val($('#exppiFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpScOrderModel.get(index,row);	

	}

	showGrid(exp_lc_sc_id){
		let self=this;
		let data = {};
		data.exp_lc_sc_id=exp_lc_sc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			showFooter:true,
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
		return '<a href="javascript:void(0)"  onClick="MsExpScOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#expscorderFrm [name=qty]').val();
		rate=$('#expscorderFrm [name=rate]').val();
		let amount=qty*rate;
		$('#expscorderFrm [name=amount]').val(amount);
	}

	openExpScOrderWindow(){
		$('#expscorderwindow').window('open');
		MsExpScOrder.searchSaleOrderGrid();
	}


	searchOrder(){
		let style_ref=$('#expscordersearchFrm  [name=style_ref]').val();
		let job_no=$('#expscordersearchFrm  [name=job_no]').val();
		let sale_order_no=$('#expscordersearchFrm  [name=sale_order_no]').val();
		let explcscid=$('#explcscFrm  [name=id]').val();

		let data= axios.get(this.route+"/importorder"+"?style_ref="+style_ref+"&job_no="+job_no+"&sale_order_no="+sale_order_no+"&explcscid="+explcscid)
		.then(function (response) {
			$('#expscordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	searchSaleOrderGrid(data)
	{
		$('#expscordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
		}).datagrid('enableFilter').datagrid('loadData', data);

	}

	closeExpScOrderWindow(){

		/*let rows=$('#expscordersearchTbl').datagrid('getSelections');
		$('#expscordersearchTbl').datagrid({
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
		$('#expscordersearchTbl').datagrid('loadData', rows);*/

		let id=[];
		let name=[];
		let checked=$('#expscordersearchTbl').datagrid('getSelections');

		if(checked.lenght > 100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
		});
		id=id.join(',');
		$('#expscorderwindow').window('close');

		let data= axios.get(this.route+"/create"+"?sales_order_id="+id)
		.then(function (response) {
			$('#expsctagorderScs').html(response.data);
			$('#expsctagorderwindow').window('open');

			$('#expscordersearchTbl').datagrid('loadData', []);
			$('#expscorderwindow').window('close');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	calculate(iteration,count){
		let rate=$('#expsctagorderFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#expsctagorderFrm input[name="qty['+iteration+']"]').val();
        let amount=msApp.multiply(qty,rate);
		$('#expsctagorderFrm input[name="amount['+iteration+']"]').val(amount)
	}

}
window.MsExpScOrder = new MsExpScOrderController(new MsExpScOrderModel());

$('#expscordersearchTbl').datagrid();
MsExpScOrder.searchSaleOrderGrid({rows:{}})

