let MsSoDyeingFabricRcvInhRolModel = require('./MsSoDyeingFabricRcvInhRolModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvInhRolController {
	constructor(MsSoDyeingFabricRcvInhRolModel)
	{
		this.MsSoDyeingFabricRcvInhRolModel = MsSoDyeingFabricRcvInhRolModel;
		this.formId='sodyeingfabricrcvinhrolFrm';
		this.dataTable='#sodyeingfabricrcvinhrolTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcvinhrol"
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
			this.MsSoDyeingFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvInhRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

        let so_dyeing_fabric_rcv_item_id=$('#sodyeingfabricrcvinhitemFrm  [name=id]').val();
		//var row = $('#sodyeingfabricrcvinhitemTbl').datagrid('getSelected');
		let inv_grey_fab_isu_item_id=MsSoDyeingFabricRcvInhRol.getSelections();
		let formObj={};
		formObj.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_item_id;
		formObj.inv_grey_fab_isu_item_id=inv_grey_fab_isu_item_id;

		if(formObj.id){
			this.MsSoDyeingFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingFabricRcvInhRolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvInhRolModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvInhRolModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		///$('#sodyeingfabricrcvrollinhWindow').window('close');
		MsSoDyeingFabricRcvInhRol.get(d.so_dyeing_fabric_rcv_item_id)
		//msApp.resetForm('sodyeingfabricrcvinhrolFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvInhRolModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_fabric_rcv_item_id)
	{
		let data= axios.get(this.route+"?so_dyeing_fabric_rcv_item_id="+so_dyeing_fabric_rcv_item_id);
		data.then(function (response) {
			$('#sodyeingfabricrcvinhrolTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcvInhRol.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	rollgrid(data){
		let self = this;
		$('#sodyeingfabricrcvrollsearchTbl').datagrid({
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
				let sodyeing_fabric_rcvinh_selected_roll_total=$("#sodyeing_fabric_rcvinh_selected_roll_total").html();
				let total=sodyeing_fabric_rcvinh_selected_roll_total*1+row.isu_qty.replace(/,/g,'')*1;
				$("#sodyeing_fabric_rcvinh_selected_roll_total").html(total);
			},
			onUnselect:function(index,row){
				let sodyeing_fabric_rcvinh_selected_roll_total=$("#sodyeing_fabric_rcvinh_selected_roll_total").html();
				let total=sodyeing_fabric_rcvinh_selected_roll_total*1-row.isu_qty.replace(/,/g,'')*1;
				$("#sodyeing_fabric_rcvinh_selected_roll_total").html(total);
			},
			onSelectAll:function(rows){
				let total=0;
				for(var i=0; i<rows.length; i++){
				total+=rows[i]['isu_qty'].replace(/,/g,'')*1;
				}				
				$("#sodyeing_fabric_rcvinh_selected_roll_total").html(total);
			},
			onUnselectAll:function(rows){
				let total=0;
				$("#sodyeing_fabric_rcvinh_selected_roll_total").html(total);
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}


	import()
	{
		let params={};
		let so_dyeing_fabric_rcv_item_id=$('#sodyeingfabricrcvinhitemFrm  [name=id]').val();
		var row = $('#sodyeingfabricrcvinhitemTbl').datagrid('getSelected');

		params.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_item_id
		params.autoyarn_id=row.autoyarn_id
		params.gmtspart_id=row.gmtspart_id
		params.fabric_look_id=row.fabric_look_id
		params.fabric_shape_id=row.fabric_shape_id
		params.sales_order_id=row.sales_order_id
		params.style_fabrication_id=row.style_fabrication_id
		params.budget_fabric_id=row.budget_fabric_id
		params.fabric_color_id=row.fabric_color_id
		
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#sodyeingfabricrcvrollsearchTbl').datagrid('loadData', response.data);
			$("#sodyeing_fabric_rcvinh_selected_roll_total").html(0);
			$('#sodyeingfabricrcvrollinhWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSelections(){
		let inv_grey_fab_isu_item_id=[];
		let checked=$('#sodyeingfabricrcvrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			inv_grey_fab_isu_item_id.push(val.id)
		});
		inv_grey_fab_isu_item_id=inv_grey_fab_isu_item_id.join(',');
		$('#sodyeingfabricrcvrollsearchTbl').datagrid('clearSelections');
		MsSoDyeingFabricRcvInhItem.sodyeingfabricrcvitemsearchGrid([]);
		$('#sodyeingfabricrcvrollinhWindow').window('close');
		return inv_grey_fab_isu_item_id;
	}

	selectAll()
	{
		$('#sodyeingfabricrcvrollsearchTbl').datagrid('selectAll');
	}
	unselectAll()
	{
		$('#sodyeingfabricrcvrollsearchTbl').datagrid('unselectAll');
	}

	
}
window.MsSoDyeingFabricRcvInhRol=new MsSoDyeingFabricRcvInhRolController(new MsSoDyeingFabricRcvInhRolModel());
MsSoDyeingFabricRcvInhRol.showGrid([]);
MsSoDyeingFabricRcvInhRol.rollgrid([]);
