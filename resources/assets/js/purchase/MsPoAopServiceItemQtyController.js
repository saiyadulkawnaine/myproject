//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoAopServiceItemQtyModel = require('./MsPoAopServiceItemQtyModel');
class MsPoAopServiceItemQtyController {
	constructor(MsPoAopServiceItemQtyModel)
	{
		this.MsPoAopServiceItemQtyModel = MsPoAopServiceItemQtyModel;
		this.formId='poaopserviceitemqtyFrm';
		this.dataTable='#poaopserviceitemqtyTbl';
		this.route=msApp.baseUrl()+"/poaopserviceitemqty"
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
		let po_aop_service_id = $('#poaopserviceFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_aop_service_id=po_aop_service_id;
		if(formObj.id){
			this.MsPoAopServiceItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoAopServiceItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoAopServiceItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoAopServiceItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_aop_service_id=$('#poaopserviceFrm  [name=id]').val()
		MsPoAopServiceItem.get(po_aop_service_id);
		MsPoAopServiceItemQty.refreshQtyWindow(d.po_aop_service_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoAopServiceItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#poaopserviceitemqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_aop_service_id){
		let data= axios.get(msApp.baseUrl()+"/poaopserviceitemqty/create?po_aop_service_item_id="+po_aop_service_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poaopserviceitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/poaopserviceitemqty/create?po_aop_service_item_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poaopserviceitemqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#poaopserviceitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poaopserviceitemqtyFrm input[name="qty['+iteration+']"]').val();
		//let pcs_qty=$('#poaopserviceitemqtyFrm input[name="pcs_qty['+iteration+']"]').val();
		 let amount=0;
		 if(qty){
			amount=msApp.multiply(qty,rate);
		 }
		// if(pcs_qty){
		// 	 amount=msApp.multiply(pcs_qty,rate);
		// }
		// else{
		// 	 amount=msApp.multiply(qty,rate);
		// }
		$('#poaopserviceitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
	}
	
}
window.MsPoAopServiceItemQty=new MsPoAopServiceItemQtyController(new MsPoAopServiceItemQtyModel());


