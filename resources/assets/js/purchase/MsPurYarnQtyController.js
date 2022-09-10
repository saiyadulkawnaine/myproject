//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurYarnQtyModel = require('./MsPurYarnQtyModel');

class MsPurYarnQtyController {
	constructor(MsPurYarnQtyModel)
	{
		this.MsPurYarnQtyModel = MsPurYarnQtyModel;
		this.formId='puryarnqtyFrm';
		this.dataTable='#puryarnsqtyTbl';
		this.route=msApp.baseUrl()+"/puryarnqty"
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
			this.MsPurYarnQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurYarnQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurYarnQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurYarnQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#bulkyarnpurchaseTbl').datagrid('reload');
		//MsPuryarn.yarnSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderyarnFrm  [name=id]').val()
		MsPurYarn.get(purchase_order_id);
		MsPurYarnQty.refreshQtyWindow(d.pur_yarn_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurYarnQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#puryarnsqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurYarnQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(pur_yarn_id){
		let data= axios.get(msApp.baseUrl()+"/puryarnqty/create?pur_yarn_id="+pur_yarn_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#puryarnqtyWindow').window('open');
		})
	}
	
	openQtyWindow(pur_yarn_id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/puryarnqty/create?pur_yarn_id="+pur_yarn_id);
		let g=data.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#puryarnqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#puryarnqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#puryarnqtyFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#puryarnqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
	
}
window.MsPurYarnQty=new MsPurYarnQtyController(new MsPurYarnQtyModel());


