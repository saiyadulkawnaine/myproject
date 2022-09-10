let MsProdAopBatchRollModel = require('./MsProdAopBatchRollModel');
require('./../../datagrid-filter.js');
class MsProdAopBatchRollController {
	constructor(MsProdAopBatchRollModel)
	{
		this.MsProdAopBatchRollModel = MsProdAopBatchRollModel;
		this.formId='prodaopbatchrollFrm';
		this.dataTable='#prodaopbatchrollTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchroll"
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
		let prod_aop_batch_id=$('#prodaopbatchFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_aop_batch_id=prod_aop_batch_id;
		if(formObj.id){
			this.MsProdAopBatchRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdAopBatchRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

        let prod_aop_batch_id=$('#prodaopbatchFrm  [name=id]').val();
		let formObj=MsProdAopBatchRoll.getdata();
		formObj.prod_aop_batch_id=prod_aop_batch_id;

		if(formObj.id){
			this.MsProdAopBatchRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopbatchrollsearchWindow').window('close');
		MsProdAopBatchRoll.get(d.prod_aop_batch_id)
		msApp.resetForm('prodaopbatchrollFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsProdAopBatchRollModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_aop_batch_id)
	{
		let data= axios.get(this.route+"?prod_aop_batch_id="+prod_aop_batch_id);
		data.then(function (response) {
			$('#prodaopbatchrollTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
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
				var rcv_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
				rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	rollgrid(data){
		let self = this;
		$('#prodaopbatchrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			onLoadSuccess: function(data){
				var rcv_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					}
					$(this).datagrid('reloadFooter', [
					{ 
						rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			},
			onSelect:function(index,row){
				let prodaopbatch_selected_roll_total=$("#prodaopbatch_selected_roll_total").html();
				let total=prodaopbatch_selected_roll_total*1+row.rcv_qty.replace(/,/g,'')*1;
				$("#prodaopbatch_selected_roll_total").html(total);
			},
			onUnselect:function(index,row){
				let prodaopbatch_selected_roll_total=$("#prodaopbatch_selected_roll_total").html();
				let total=prodaopbatch_selected_roll_total*1-row.rcv_qty.replace(/,/g,'')*1;
				$("#prodaopbatch_selected_roll_total").html(total);
			},
			onSelectAll:function(rows){
				let total=0;
				for(var i=0; i<rows.length; i++){
				total+=rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}				
				$("#prodaopbatch_selected_roll_total").html(total);
			},
			onUnselectAll:function(rows){
				let total=0;
				$("#prodaopbatch_selected_roll_total").html(total);
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		let params={};
		let prod_aop_batch_id=$('#prodaopbatchFrm  [name=id]').val();
		params.prod_aop_batch_id=prod_aop_batch_id
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#prodaopbatchrollsearchTbl').datagrid('loadData', response.data);
			$("#prodaopbatch_selected_roll_total").html(0);
			$('#prodaopbatchrollsearchWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}



	getdata(){
		let checked=$('#prodaopbatchrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		let formObj={};
		let i=1;
		$.each(checked, function (idx, val) {
				formObj['so_aop_fabric_isu_item_id['+i+']']=val.id;
				formObj['qty['+i+']']=val.rcv_qty.replace(/,/g,'')*1;
			i++;
		});
		return formObj;
	}

	selectAll()
	{
		$('#prodaopbatchrollsearchTbl').datagrid('selectAll');
	}
	unselectAll()
	{
		$('#prodaopbatchrollsearchTbl').datagrid('unselectAll');
	}

	
}
window.MsProdAopBatchRoll=new MsProdAopBatchRollController(new MsProdAopBatchRollModel());
MsProdAopBatchRoll.showGrid([]);
MsProdAopBatchRoll.rollgrid([]);
