//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoDyeingServiceItemQtyModel = require('./MsPoDyeingServiceItemQtyModel');
class MsPoDyeingServiceItemQtyController {
	constructor(MsPoDyeingServiceItemQtyModel)
	{
		this.MsPoDyeingServiceItemQtyModel = MsPoDyeingServiceItemQtyModel;
		this.formId='podyeingserviceitemqtyFrm';
		this.dataTable='#podyeingserviceitemqtyTbl';
		this.route=msApp.baseUrl()+"/podyeingserviceitemqty"
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
		let po_dyeing_service_id = $('#podyeingserviceFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_dyeing_service_id=po_dyeing_service_id;
		if(formObj.id){
			this.MsPoDyeingServiceItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoDyeingServiceItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoDyeingServiceItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoDyeingServiceItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_dyeing_service_id=$('#podyeingserviceFrm  [name=id]').val()
		MsPoDyeingServiceItem.get(po_dyeing_service_id);
		MsPoDyeingServiceItemQty.refreshQtyWindow(d.po_dyeing_service_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoDyeingServiceItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#podyeingserviceitemqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPoDyeingServiceItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_dyeing_service_id){
		let data= axios.get(msApp.baseUrl()+"/podyeingserviceitemqty/create?po_dyeing_service_item_id="+po_dyeing_service_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#podyeingserviceitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/podyeingserviceitemqty/create?po_dyeing_service_item_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#podyeingserviceitemqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#podyeingserviceitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#podyeingserviceitemqtyFrm input[name="qty['+iteration+']"]').val();
		let pcs_qty=$('#podyeingserviceitemqtyFrm input[name="pcs_qty['+iteration+']"]').val();
		let amount=0;
		if(pcs_qty){
			 amount=msApp.multiply(pcs_qty,rate);
		}else{
			 amount=msApp.multiply(qty,rate);
		}
		$('#podyeingserviceitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
	}
	
}
window.MsPoDyeingServiceItemQty=new MsPoDyeingServiceItemQtyController(new MsPoDyeingServiceItemQtyModel());


