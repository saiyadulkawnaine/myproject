let MsProdBatchRdRollModel = require('./MsProdBatchRdRollModel');
require('./../../datagrid-filter.js');
class MsProdBatchRdRollController {
	constructor(MsProdBatchRdRollModel)
	{
		this.MsProdBatchRdRollModel = MsProdBatchRdRollModel;
		this.formId='prodbatchrdrollFrm';
		this.dataTable='#prodbatchrdrollTbl';
		this.route=msApp.baseUrl()+"/prodbatchrdroll"
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
		let prod_batch_id=$('#prodbatchrdFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_id=prod_batch_id;
		if(formObj.id){
			this.MsProdBatchRdRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchRdRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

        let prod_batch_id=$('#prodbatchrdFrm  [name=id]').val();
		let formObj=MsProdBatchRdRoll.getdata();
		formObj.prod_batch_id=prod_batch_id;

		if(formObj.id){
			this.MsProdBatchRdRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchRdRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchRdRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchRdRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchrdrollsearchWindow').window('close');
		MsProdBatchRdRoll.get(d.prod_batch_id)
		msApp.resetForm('prodbatchrdrollFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsProdBatchRdRollModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchrdrollTbl').datagrid('loadData', response.data);
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
				var batch_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
				batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchRdRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	rollgrid(data){
		let self = this;
		$('#prodbatchrdrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			onLoadSuccess: function(data){
				var batch_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
					}
					$(this).datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			},
			onSelect:function(index,row){
				let prodbatchrd_selected_roll_total=$("#prodbatchrd_selected_roll_total").html();
				let total=prodbatchrd_selected_roll_total*1+row.batch_qty.replace(/,/g,'')*1;
				$("#prodbatchrd_selected_roll_total").html(total);
			},
			onUnselect:function(index,row){
				let prodbatchrd_selected_roll_total=$("#prodbatchrd_selected_roll_total").html();
				let total=prodbatchrd_selected_roll_total*1-row.batch_qty.replace(/,/g,'')*1;
				$("#prodbatchrd_selected_roll_total").html(total);
			},
			onSelectAll:function(rows){
				let total=0;
				for(var i=0; i<rows.length; i++){
				total+=rows[i]['batch_qty'].replace(/,/g,'')*1;
				}				
				$("#prodbatchrd_selected_roll_total").html(total);
			},
			onUnselectAll:function(rows){
				let total=0;
				$("#prodbatchrd_selected_roll_total").html(total);
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		let params={};
		let prod_batch_id=$('#prodbatchrdFrm  [name=id]').val();
		let prod_root_batch_id=$('#prodbatchrdFrm  [name=root_batch_id]').val();
		params.prod_batch_id=prod_batch_id
		params.prod_root_batch_id=prod_root_batch_id
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#prodbatchrdrollsearchTbl').datagrid('loadData', response.data);
			$("#prodbatchrd_selected_roll_total").html(0)
			$('#prodbatchrdrollsearchWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*getSelections(){
		let so_dyeing_fabric_rcv_rol_id=[];
		let checked=$('#prodbatchrdrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			so_dyeing_fabric_rcv_rol_id.push(val.id)
		});
		so_dyeing_fabric_rcv_rol_id=so_dyeing_fabric_rcv_rol_id.join(',');
		$('#prodbatchrdrollsearchTbl').datagrid('clearSelections');
		MsProdBatchRdRoll.rollgrid([]);
		$('#prodbatchrdrollsearchWindow').window('close');
		return so_dyeing_fabric_rcv_rol_id;
	}*/

	getdata(){
		let checked=$('#prodbatchrdrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}

		let formObj={};
		let i=1;
		$.each(checked, function (idx, val) {
				formObj['root_batch_roll_id['+i+']']=val.id;
				formObj['so_dyeing_fabric_rcv_rol_id['+i+']']=val.so_dyeing_fabric_rcv_rol_id;
				formObj['qty['+i+']']=val.batch_qty.replace(/,/g,'')*1;
			i++;
		});
		return formObj;
	}

	selectAll()
	{
		$('#prodbatchrdrollsearchTbl').datagrid('selectAll');
	}
	unselectAll()
	{
		$('#prodbatchrdrollsearchTbl').datagrid('unselectAll');
	}

	
}
window.MsProdBatchRdRoll=new MsProdBatchRdRollController(new MsProdBatchRdRollModel());
MsProdBatchRdRoll.showGrid([]);
MsProdBatchRdRoll.rollgrid([]);
