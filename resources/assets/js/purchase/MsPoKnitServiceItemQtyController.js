//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoKnitServiceItemQtyModel = require('./MsPoKnitServiceItemQtyModel');
class MsPoKnitServiceItemQtyController {
	constructor(MsPoKnitServiceItemQtyModel)
	{
		this.MsPoKnitServiceItemQtyModel = MsPoKnitServiceItemQtyModel;
		this.formId='poknitserviceitemqtyFrm';
		this.dataTable='#poknitserviceitemqtyTbl';
		this.route=msApp.baseUrl()+"/poknitserviceitemqty"
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
		let po_knit_service_id = $('#poknitserviceFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_knit_service_id=po_knit_service_id;
		if(formObj.id){
			this.MsPoKnitServiceItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoKnitServiceItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoKnitServiceItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoKnitServiceItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_knit_service_id=$('#poknitserviceFrm  [name=id]').val()
		MsPoKnitServiceItem.get(po_knit_service_id);
		MsPoKnitServiceItemQty.refreshQtyWindow(d.po_knit_service_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoKnitServiceItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#poknitserviceitemqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_knit_service_id){
		let data= axios.get(msApp.baseUrl()+"/poknitserviceitemqty/create?po_knit_service_item_id="+po_knit_service_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poknitserviceitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/poknitserviceitemqty/create?po_knit_service_item_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#poknitserviceitemqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#poknitserviceitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poknitserviceitemqtyFrm input[name="qty['+iteration+']"]').val();
		let pcs_qty=$('#poknitserviceitemqtyFrm input[name="pcs_qty['+iteration+']"]').val();
		let amount=0;
		if(pcs_qty){
			 amount=msApp.multiply(pcs_qty,rate);
		}else{
			 amount=msApp.multiply(qty,rate);
		}
		$('#poknitserviceitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
	}
	
}
window.MsPoKnitServiceItemQty=new MsPoKnitServiceItemQtyController(new MsPoKnitServiceItemQtyModel());


