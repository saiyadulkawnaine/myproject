let MsSoAopFabricRcvInhRolModel = require('./MsSoAopFabricRcvInhRolModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvInhRolController {
	constructor(MsSoAopFabricRcvInhRolModel)
	{
		this.MsSoAopFabricRcvInhRolModel = MsSoAopFabricRcvInhRolModel;
		this.formId='soaopfabricrcvinhrolFrm';
		this.dataTable='#soaopfabricrcvinhrolTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcvinhrol"
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
			this.MsSoAopFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvInhRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

        //let so_aop_fabric_rcv_item_id=$('#soaopfabricrcvinhitemFrm  [name=id]').val();
        var row = $('#soaopfabricrcvinhitemTbl').datagrid('getSelected');
		let formObj=msApp.get('soaopfabricrcvinhrollmatrixFrm');
		formObj.so_aop_fabric_rcv_item_id=row.id;

		if(formObj.id){
			this.MsSoAopFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopFabricRcvInhRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvInhRolModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		///$('#soaopfabricrcvrollinhWindow').window('close');
		MsSoAopFabricRcvInhRol.get(d.so_aop_fabric_rcv_item_id)
		$('#soaopfabricrcvinhrollmultiformWindow').window('close');
		msApp.resetForm(this.formId);
		msApp.resetForm('soaopfabricrcvinhrollmatrixFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvInhRolModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_fabric_rcv_item_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_rcv_item_id="+so_aop_fabric_rcv_item_id);
		data.then(function (response) {
			$('#soaopfabricrcvinhrolTbl').datagrid('loadData', response.data);
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
				//var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				//tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					rcv_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcvInhRol.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	rollgrid(data){
		let self = this;
		$('#soaopfabricrcvrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			onLoadSuccess: function(data){
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['isu_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					isu_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			},
			onSelect:function(index,row){
				let soaop_fabric_rcvinh_selected_roll_total=$("#soaop_fabric_rcvinh_selected_roll_total").html();
				let total=soaop_fabric_rcvinh_selected_roll_total*1+row.isu_qty.replace(/,/g,'')*1;
				$("#soaop_fabric_rcvinh_selected_roll_total").html(total);
			},
			onUnselect:function(index,row){
				let soaop_fabric_rcvinh_selected_roll_total=$("#soaop_fabric_rcvinh_selected_roll_total").html();
				let total=soaop_fabric_rcvinh_selected_roll_total*1-row.isu_qty.replace(/,/g,'')*1;
				$("#soaop_fabric_rcvinh_selected_roll_total").html(total);
			},
			onSelectAll:function(rows){
				let total=0;
				for(var i=0; i<rows.length; i++){
				total+=rows[i]['isu_qty'].replace(/,/g,'')*1;
				}				
				$("#soaop_fabric_rcvinh_selected_roll_total").html(total);
			},
			onUnselectAll:function(rows){
				let total=0;
				$("#soaop_fabric_rcvinh_selected_roll_total").html(total);
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}


	import()
	{
		let params={};
		let so_aop_fabric_rcv_item_id=$('#soaopfabricrcvinhitemFrm  [name=id]').val();
		let so_aop_fabric_rcv_id=$('#soaopfabricrcvinhFrm  [name=id]').val();
		var row = $('#soaopfabricrcvinhitemTbl').datagrid('getSelected');

		params.so_aop_fabric_rcv_id=so_aop_fabric_rcv_id
		params.so_aop_fabric_rcv_item_id=so_aop_fabric_rcv_item_id
		params.autoyarn_id=row.autoyarn_id
		params.gmtspart_id=row.gmtspart_id
		params.fabric_look_id=row.fabric_look_id
		params.fabric_shape_id=row.fabric_shape_id
		params.sales_order_id=row.sales_order_id
		params.style_fabrication_id=row.style_fabrication_id
		params.budget_fabric_id=row.budget_fabric_id
		params.fabric_color_id=row.fabric_color_id
		
		let data= axios.get(this.route+"/getroll",{params});
		data.then(function (response) {
			$('#soaopfabricrcvrollsearchTbl').datagrid('loadData', response.data);
			$("#soaop_fabric_rcvinh_selected_roll_total").html(0);
			$('#soaopfabricrcvinhrollWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSelections(){
		let prod_finish_dlv_roll_id=[];
		let checked=$('#soaopfabricrcvrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			prod_finish_dlv_roll_id.push(val.id)
		});
		prod_finish_dlv_roll_id=prod_finish_dlv_roll_id.join(',');
		$('#soaopfabricrcvrollsearchTbl').datagrid('clearSelections');
		MsSoAopFabricRcvInhItem.soaopfabricrcvitemsearchGrid([]);
		$('#soaopfabricrcvinhrollWindow').window('close');
		return prod_finish_dlv_roll_id;
	}
	openMultiForm(){
		let prod_finish_dlv_roll_id=MsSoAopFabricRcvInhRol.getSelections();
		let params={};
		params.prod_finish_dlv_roll_id=prod_finish_dlv_roll_id;
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#soaopfabricrcvinhrollmultiformWindowscs').html(response.data);
			$('#soaopfabricrcvinhrollmultiformWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	selectAll()
	{
		$('#soaopfabricrcvrollsearchTbl').datagrid('selectAll');
	}
	unselectAll()
	{
		$('#soaopfabricrcvrollsearchTbl').datagrid('unselectAll');
	}

	copyRoom(room,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#soaopfabricrcvinhrollmatrixFrm input[name="room['+i+']"]').val(room)
	}
	}
	copyRack(rack,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#soaopfabricrcvinhrollmatrixFrm input[name="rack['+i+']"]').val(rack)
	}
	}

	copyShelf(shelf,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#soaopfabricrcvinhrollmatrixFrm input[name="shelf['+i+']"]').val(shelf)
	}
	}

	
}
window.MsSoAopFabricRcvInhRol=new MsSoAopFabricRcvInhRolController(new MsSoAopFabricRcvInhRolModel());
MsSoAopFabricRcvInhRol.showGrid([]);
MsSoAopFabricRcvInhRol.rollgrid([]);
