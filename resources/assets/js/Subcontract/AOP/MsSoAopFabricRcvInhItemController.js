let MsSoAopFabricRcvInhItemModel = require('./MsSoAopFabricRcvInhItemModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvInhItemController {
	constructor(MsSoAopFabricRcvInhItemModel)
	{
		this.MsSoAopFabricRcvInhItemModel = MsSoAopFabricRcvInhItemModel;
		this.formId='soaopfabricrcvinhitemFrm';
		this.dataTable='#soaopfabricrcvinhitemTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcvinhitem"
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
			this.MsSoAopFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvInhItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let so_aop_fabric_rcv_id=$('#soaopfabricrcvinhFrm [name=id]').val()
		let so_aop_ref_id=MsSoAopFabricRcvInhItem.getSelections();
		let formObj=[];
		formObj.so_aop_fabric_rcv_id=so_aop_fabric_rcv_id;
		formObj.so_aop_ref_id=so_aop_ref_id;
		if(formObj.id){
			this.MsSoAopFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopFabricRcvInhItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvInhItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricrcvinhitemWindow').window('close');
		MsSoAopFabricRcvInhItem.get(d.so_aop_fabric_rcv_id)
		msApp.resetForm('soaopfabricrcvinhitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvInhItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_fabric_rcv_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_rcv_id="+so_aop_fabric_rcv_id);
		data.then(function (response) {
			$('#soaopfabricrcvinhitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcvInhItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#soaopfabricrcvinhitemWindow').window('open');
	}*/
	soaopfabricrcvitemsearchGrid(data){
		let self = this;
		$('#soaopfabricrcvinhitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		let so_aop_fabric_rcv_id=$('#soaopfabricrcvinhFrm  [name=id]').val();
		let data= axios.get(this.route+"/create?so_aop_fabric_rcv_id="+so_aop_fabric_rcv_id);
		data.then(function (response) {
			//$('#soaopfabricrcvinhitemWindowscs').html(response.data);
			$('#soaopfabricrcvinhitemsearchTbl').datagrid('loadData', response.data);
			$('#soaopfabricrcvinhitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*calculate(iteration,count){
		let qty=$('#soaopfabricrcvinhitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#soaopfabricrcvinhitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soaopfabricrcvinhitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}
	calculate_form(){
		
		let qty=$('#soaopfabricrcvinhitemFrm  [name=qty]').val();
		let rate=$('#soaopfabricrcvinhitemFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soaopfabricrcvinhitemFrm  [name=amount]').val(amount);
	}*/
	getSelections(){
		let so_aop_ref_id=[];
		let checked=$('#soaopfabricrcvinhitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			so_aop_ref_id.push(val.so_aop_ref_id)
		});
		so_aop_ref_id=so_aop_ref_id.join(',');
		$('#soaopfabricrcvinhitemsearchTbl').datagrid('clearSelections');
		MsSoAopFabricRcvInhItem.soaopfabricrcvitemsearchGrid([]);
		$('#soaopfabricrcvinhitemWindow').window('close');
		return so_aop_ref_id;
	}
}
window.MsSoAopFabricRcvInhItem=new MsSoAopFabricRcvInhItemController(new MsSoAopFabricRcvInhItemModel());
MsSoAopFabricRcvInhItem.showGrid([]);
MsSoAopFabricRcvInhItem.soaopfabricrcvitemsearchGrid([]);
//MsSoAopFabricRcvInhItem.soaopfabricrcvinhitemsoGrid([]);