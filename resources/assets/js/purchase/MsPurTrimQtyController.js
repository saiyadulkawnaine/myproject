//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurTrimQtyModel = require('./MsPurTrimQtyModel');
class MsPurTrimQtyController {
	constructor(MsPurTrimQtyModel)
	{
		this.MsPurTrimQtyModel = MsPurTrimQtyModel;
		this.formId='purtrimqtyFrm';
		this.dataTable='#purtrimsqtyTbl';
		this.route=msApp.baseUrl()+"/purtrimqty"
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
			this.MsPurTrimQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurTrimQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurTrimQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurTrimQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let purchase_order_id=$('#purordertrimFrm  [name=id]').val()
		MsPurTrim.get(purchase_order_id);
		MsPurTrimQty.refreshQtyWindow(d.pur_trim_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurTrimQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#purtrimsqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurTrimQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(pur_trim_id){
		let data= axios.get(msApp.baseUrl()+"/purtrimqty/create?pur_trim_id="+pur_trim_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#purtrimqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id)
	{
		if(!id)
		{
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/purtrimqty/create?pur_trim_id="+id);
		let g=data.then(function (response) {
			for(var key in response.data.dropDown)
			{
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#purtrimqtyWindow').window('open');
		})
			
	}

	calculateAmount(iteration,count,field){
		let rate=$('#purtrimqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#purtrimqtyFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#purtrimqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
}
window.MsPurTrimQty=new MsPurTrimQtyController(new MsPurTrimQtyModel());