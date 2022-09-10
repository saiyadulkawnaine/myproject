//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoEmbServiceItemQtyModel = require('./MsPoEmbServiceItemQtyModel');
class MsPoEmbServiceItemQtyController {
	constructor(MsPoEmbServiceItemQtyModel)
	{
		this.MsPoEmbServiceItemQtyModel = MsPoEmbServiceItemQtyModel;
		this.formId='poembserviceitemqtyFrm';
		this.dataTable='#poembserviceitemqtyTbl';
		this.route=msApp.baseUrl()+"/poembserviceitemqty"
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
		let po_emb_service_id=$('#poembserviceFrm  [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.po_emb_service_id=po_emb_service_id
		if(formObj.id){
			this.MsPoEmbServiceItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoEmbServiceItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoEmbServiceItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoEmbServiceItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_emb_service_id=$('#poembserviceFrm  [name=id]').val()
		MsPoEmbServiceItem.get(po_emb_service_id);
		MsPoEmbServiceItemQty.refreshQtyWindow(d.po_emb_service_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoEmbServiceItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#poembserviceitemqtyTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			
		});
		dg.datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_emb_service_id){
		let data= axios.get(msApp.baseUrl()+"/poembserviceitemqty/create?po_emb_service_item_id="+po_emb_service_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poembserviceitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/poembserviceitemqty/create?po_emb_service_item_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poembserviceitemqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#poembserviceitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poembserviceitemqtyFrm input[name="qty['+iteration+']"]').val();
		let bom_ratio=$('#poembserviceitemqtyFrm input[name="bom_ratio['+iteration+']"]').val();
		bom_ratio=bom_ratio*1;
		let amount=0;
	    amount=msApp.multiply((qty/12),rate);
		$('#poembserviceitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
		// if(field==='qty'){
		// this.copyQty(qty,iteration,count);
		// }
		// else if(field==='rate'){
		// this.copyRate(rate,iteration,count);
		//}
	}


	// copyQty(qty,iteration,count)
	// {
	// 	for(var i=iteration;i<=count;i++)
	// 	{
 //            let costing_unit=12;
	// 		let rate=$('#poembserviceitemqtyFrm input[name="rate['+i+']"]').val();
	// 		let amount=msApp.multiply(qty,(rate/costing_unit));
	// 		$('#poembserviceitemqtyFrm input[name="qty['+i+']"]').val(qty)
	// 		$('#poembserviceitemqtyFrm input[name="amount['+i+']"]').val(amount)
	// 	}
	// }


	// copyRate(rate,iteration,count)
	// {
	// 	for(var i=iteration;i<=count;i++)
	// 	{
	// 		let costing_unit=12;
	// 		let qty=$('#poembserviceitemqtyFrm input[name="qty['+i+']"]').val();
	// 		let amount=msApp.multiply(qty,(rate/costing_unit));
	// 		$('#poembserviceitemqtyFrm input[name="rate['+i+']"]').val(rate)
	// 		$('#poembserviceitemqtyFrm input[name="amount['+i+']"]').val(amount)
	// 	}
	// }

	copyRemarks(iteration,count){
		let remarks=$('#poembserviceitemqtyFrm input[name="remarks['+iteration+']"]').val();
		for(var i=iteration;i<=count; i++){
			$('#poembserviceitemqtyFrm input[name="remarks['+i+']"]').val(remarks)
		}
	}
	
}
window.MsPoEmbServiceItemQty=new MsPoEmbServiceItemQtyController(new MsPoEmbServiceItemQtyModel());


