//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoTrimItemQtyModel = require('./MsPoTrimItemQtyModel');
class MsPoTrimItemQtyController {
	constructor(MsPoTrimItemQtyModel)
	{
		this.MsPoTrimItemQtyModel = MsPoTrimItemQtyModel;
		this.formId='potrimitemqtyFrm';
		this.dataTable='#potrimitemqtyTbl';
		this.route=msApp.baseUrl()+"/potrimitemqty"
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
		let po_trim_id = $('#potrimFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_trim_id=po_trim_id;
		if(formObj.id){
			this.MsPoTrimItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoTrimItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoTrimItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoTrimItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_trim_id=$('#potrimFrm  [name=id]').val()
		MsPoTrimItem.get(po_trim_id);
		MsPoTrimItemQty.refreshQtyWindow(d.po_trim_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoTrimItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#potrimitemqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoTrimItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_trim_item_id){
		let data= axios.get(msApp.baseUrl()+"/potrimitemqty/create?po_trim_item_id="+po_trim_item_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#potrimitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id,title)
	{
		if(!id)
		{
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/potrimitemqty/create?po_trim_item_id="+id);
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
			$('#potrimitemqtyWindow').window({ title: 'Item: '+title});
			$('#potrimitemqtyWindow').window('open');
		})
			
	}

	calculateAmount(iteration,count,field){
		let rate=$('#potrimitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#potrimitemqtyFrm input[name="qty['+iteration+']"]').val();
		let balqty=$('#potrimitemqtyFrm input[name="balqty['+iteration+']"]').val();
		if(qty*1 > balqty*1){
			alert("Greater than balance qty not allowed");
			$('#potrimitemqtyFrm input[name="qty['+iteration+']"]').val('');
			return;
		}
		
		let amount=msApp.multiply(qty,rate);
		$('#potrimitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
	copyDescription(iteration,count){
		var description=$('#potrimitemqtyFrm input[name="description['+iteration+']"]').val();

		for(var i=iteration;i<=count;i++)
		{
			$('#potrimitemqtyFrm input[name="description['+i+']"]').val(description)
		}
	}
}
window.MsPoTrimItemQty=new MsPoTrimItemQtyController(new MsPoTrimItemQtyModel());