
let MsExpLcOrderModel = require('./MsExpLcOrderModel');
class MsExpLcOrderController {
	constructor(MsExpLcOrderModel)
	{
		this.MsExpLcOrderModel = MsExpLcOrderModel;
		this.formId='explcorderFrm';
		this.dataTable='#explcorderTbl';
		this.route=msApp.baseUrl()+"/explcorder"
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
			this.MsExpLcOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		
		let explc=msApp.get('explcFrm');
		let formObj=msApp.get('explctagorderFrm');
        formObj.exp_lc_sc_id=explc.id;
		formObj.company_id=explc.beneficiary_id;
		formObj.itemclass_id=41;
		formObj.buyer_id=explc.buyer_id;
		formObj.pi_date=explc.lc_sc_date;
		formObj.pay_term_id=explc.pay_term_id;
        formObj.incoterm_id=explc.incoterm_id;
        formObj.delivery_date=explc.last_delivery_date;



		this.MsExpLcOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explcorderTbl').datagrid('reload');
		msApp.resetForm('explcorderFrm');
		$('#explcorderFrm  [name=exp_pi_id]').val($('#exppiFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpLcOrderModel.get(index,row);	

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
			fitColumns:true,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		}).datagrid('enableFilter');//.datagrid('loadData', data)
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpScOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateLcAmount(){
		let self=this;
		let qty=($('#explcorderFrm [name=qty]').val())*1;
		let rate=($('#explcorderFrm [name=rate]').val())*1;
		let amount=qty*rate;
		//alert(qty);
		$('#explcorderFrm [name=amount]').val(amount);
	}

	openExpLcOrderWindow(){
		$('#explsorderwindow').window('open');
		MsExpLcOrder.searchSaleOrderGrid();
	}


	searchOrder(){
		let style_ref=$('#explcordersearchFrm  [name=style_ref]').val();
		let job_no=$('#explcordersearchFrm  [name=job_no]').val();
		let sale_order_no=$('#explcordersearchFrm  [name=sale_order_no]').val();
		let explcid=$('#explcFrm  [name=id]').val();

		let data= axios.get(this.route+"/importorder"+"?style_ref="+style_ref+"&job_no="+job_no+"&sale_order_no="+sale_order_no+"&explcid="+explcid)
		.then(function (response) {
			$('#explcordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	searchSaleOrderGrid(data)
	{
		$('#explcordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
		}).datagrid('enableFilter').datagrid('loadData', data);

	}

	closeExpLcOrderWindow(){

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
		let checked=$('#explcordersearchTbl').datagrid('getSelections');

		if(checked.lenght > 100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
		});
		id=id.join(',');
		$('#explcorderwindow').window('close');

		let data= axios.get(this.route+"/create"+"?sales_order_id="+id)
		.then(function (response) {
			$('#explctagorderScs').html(response.data);
			$('#explctagorderwindow').window('open');
			$('#explcordersearchTbl').datagrid('loadData', []);
			$('#explsorderwindow').window('close');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	calculate(iteration,count){
		let rate=$('#explctagorderFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#explctagorderFrm input[name="qty['+iteration+']"]').val();
        let amount=msApp.multiply(qty,rate);
		$('#explctagorderFrm input[name="amount['+iteration+']"]').val(amount)
	}

}
window.MsExpLcOrder = new MsExpLcOrderController(new MsExpLcOrderModel());

$('#explcordersearchTbl').datagrid();
MsExpLcOrder.searchSaleOrderGrid({rows:{}})

