require('./../datagrid-filter.js');
let MsBuyerDevelopmentOrderQtyModel = require('./MsBuyerDevelopmentOrderQtyModel');
class MsBuyerDevelopmentOrderQtyController {
	constructor(MsBuyerDevelopmentOrderQtyModel)
	{
		this.MsBuyerDevelopmentOrderQtyModel = MsBuyerDevelopmentOrderQtyModel;
		this.formId='buyerdevelopmentorderqtyFrm';
		this.dataTable='#buyerdevelopmentorderqtyTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopmentorderqty"
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
			this.MsBuyerDevelopmentOrderQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerDevelopmentOrderQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj) ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//MsBuyerDevelopmentOrder.resetForm();
		$('#buyerdevelopmentorderqtyFrm  [name=buyer_development_order_id]').val($('#buyerdevelopmentorderFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerDevelopmentOrderQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerDevelopmentOrderQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyerdevelopmentorderqtyTbl').datagrid('reload');
		MsBuyerDevelopmentOrderQty.resetForm();	
		$('#buyerdevelopmentorderqtyFrm  [name=buyer_development_order_id]').val($('#buyerdevelopmentorderFrm  [name=id]').val());	
		MsBuyerDevelopmentOrderQty.get($('#buyerdevelopmentorderFrm  [name=id]').val());
		//$('#buyerdevelopmentorderqtyFrm  [name=id]').val(d.id);

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerDevelopmentOrderQtyModel.get(index,row);
	}

	// showGrid(buyer_development_order_id)
	// {
	// 	var data={};
	// 	data.buyer_development_order_id=buyer_development_order_id;
	// 	let self=this;
	// 	$(this.dataTable).datagrid({
	// 		method:'get',
	// 		border:false,
	// 		singleSelect:true,
	// 		queryParams:data,
	// 		url:this.route,
	// 		fit:true,
	// 		onClickRow: function(index,row){
	// 			self.edit(index,row);
	// 		}
	// 	}).datagrid('enableFilter');
	// }

	showGrid(data)
	{
		//var data={};
		//data.buyer_development_order_id=buyer_development_order_id;
		let self=this;
		$(this.dataTable).datagrid({
		//	method:'get',
			border:false,
			singleSelect:true,
		//	queryParams:data,
		//	url:this.route,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentOrderQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	get(buyer_development_order_id){
		let data= axios.get(this.route+"?buyer_development_order_id="+buyer_development_order_id);
		data.then(function (response) {
			$('#buyerdevelopmentorderqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#buyerdevelopmentorderqtyFrm [name=qty]').val();
		rate=$('#buyerdevelopmentorderqtyFrm [name=rate]').val();
		let amount=qty*rate;
		$('#buyerdevelopmentorderqtyFrm [name=amount]').val(amount);
	}

	calculateRcvAmount(){
		let rcvqty;
		let rcvrate;
		rcvqty=$('#buyerdevelopmentorderqtyFrm [name=rcv_qty]').val();
		rcvrate=$('#buyerdevelopmentorderqtyFrm [name=rcv_rate]').val();
		let rcvamount=rcvqty*rcvrate;
		$('#buyerdevelopmentorderqtyFrm [name=rcv_amount]').val(rcvamount);
	}
}


window.MsBuyerDevelopmentOrderQty=new MsBuyerDevelopmentOrderQtyController(new MsBuyerDevelopmentOrderQtyModel());
MsBuyerDevelopmentOrderQty.showGrid([]);