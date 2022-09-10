
let MsLocalExpPiOrderModel = require('./MsLocalExpPiOrderModel');
class MsLocalExpPiOrderController {
	constructor(MsLocalExpPiOrderModel)
	{
		this.MsLocalExpPiOrderModel = MsLocalExpPiOrderModel;
		this.formId='localexppiorderFrm';
		this.dataTable='#localexppiorderTbl';
		this.route=msApp.baseUrl()+"/localexppiorder"
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
			this.MsLocalExpPiOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpPiOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

		let formObj=msApp.get('localexppiqtymultiFrm');
		let local_exp_pi_id=$('#localexppiFrm  [name=id]').val();
		formObj.local_exp_pi_id=local_exp_pi_id;
		this.MsLocalExpPiOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		msApp.resetForm('localexppiqtymultiFrm');
		$('#localexppiqtymultiWindow').window('close');
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpPiOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpPiOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexppiorderTbl').datagrid('reload');
		msApp.resetForm('localexppiorderFrm');
		let local_exp_pi_id=$('#localexppiFrm  [name=id]').val()
		MsLocalExpPiOrder.get(local_exp_pi_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsLocalExpPiOrderModel.get(index,row);	

	}

	get(local_exp_pi_id)
	{
		let d = axios.get(this.route+"?local_exp_pi_id="+local_exp_pi_id)
		.then(function(response){
			$('#localexppiorderTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		// let data = {};
		// data.local_exp_pi_id=local_exp_pi_id;
		var local=$('#localexppiorderTbl');
		local=$('#localexppiorderTbl').datagrid({
			//method:'get',
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			showFooter:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
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
		});
		local.datagrid('enableFilter').datagrid('loadData', data);
		
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpPiOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#localexppiorderFrm [name=qty]').val();
		rate=$('#localexppiorderFrm [name=rate]').val();
		let amount=qty*rate;
		$('#localexppiorderFrm [name=amount]').val(amount);
	}



	calculate(iteration,count){
		let rate=$('#localexppiqtymultiFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#localexppiqtymultiFrm input[name="qty['+iteration+']"]').val();
        let amount=msApp.multiply(qty,rate);
		$('#localexppiqtymultiFrm input[name="amount['+iteration+']"]').val(amount);
	}

	netDiscountTotal(iteration,count){
		let discount_per=$('#localexppiqtymultiFrm input[name="discount_per['+iteration+']"]').val()*1;
		let amount=$('#localexppiqtymultiFrm input[name="amount['+iteration+']"]').val()*1;
        let net_amount=(amount+discount_per)*1;
		$('#localexppiqtymultiFrm input[name="net_amount['+iteration+']"]').val(net_amount);
	}

	netDiscountCalc(){
		let self=this;
		let discount_per=$('#localexppiorderFrm input[name="discount_per"]').val()*1;
		let amount=$('#localexppiorderFrm input[name="amount"]').val()*1;
        let net_amount=(amount+discount_per)*1;
		$('#localexppiorderFrm input[name="net_amount"]').val(net_amount);
	}

	openLocalsaleorder(){
		$('#subinbSaleOrderWindow').window('open');
	}

	getSaleOrderParam(){
		let params={};
		params.localexppiid=$('#localexppiFrm  [name=id]').val();
		params.sales_order_no=$('#localexppiordersearchFrm  [name=sales_order_no]').val();
		params.company_id=$('#localexppiordersearchFrm  [name=company_id]').val();
		params.buyer_id=$('#localexppiordersearchFrm  [name=buyer_id]').val();
		return params;
	}

	searchLocalExpPiSaleOrderGrid(){
		let params=this.getSaleOrderParam();
		let data= axios.get(this.route+"/importlocalorder",{params})
		.then(function (response) {
			$('#localexppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showSaleOrderGrid(data)
	{
		$('#localexppiordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			// onClickRow:function(index,row){
			// 	$('#localexppiordersearchTbl').datagrid('loadData',[]);
			// }
		}).datagrid('enableFilter').datagrid('loadData', data);

	}

	closeLocalSaleOrderWindow(){

		let local_exp_pi_id=$('#localexppiFrm  [name=id]').val();
		let sales_order_ref_id=[];
		let name=[];
		let checked=$('#localexppiordersearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			sales_order_ref_id.push(val.sales_order_ref_id)
		});
		sales_order_ref_id=sales_order_ref_id.join(',');
		$('#localexppiordersearchTbl').datagrid('clearSelections');
		$('#subinbSaleOrderWindow').window('close');

		let data= axios.get(this.route+"/create"+"?sales_order_ref_id="+sales_order_ref_id+'&local_exp_pi_id='+local_exp_pi_id)
		.then(function (response) {
			$('#localexppiqtymultiscs').html(response.data);
			$('#localexppiqtymultiWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

}
window.MsLocalExpPiOrder = new MsLocalExpPiOrderController(new MsLocalExpPiOrderModel());
MsLocalExpPiOrder.showGrid([]);
MsLocalExpPiOrder.showSaleOrderGrid([]);