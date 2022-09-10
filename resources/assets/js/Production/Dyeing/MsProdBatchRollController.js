let MsProdBatchRollModel = require('./MsProdBatchRollModel');
require('./../../datagrid-filter.js');
class MsProdBatchRollController {
	constructor(MsProdBatchRollModel)
	{
		this.MsProdBatchRollModel = MsProdBatchRollModel;
		this.formId='prodbatchrollFrm';
		this.dataTable='#prodbatchrollTbl';
		this.route=msApp.baseUrl()+"/prodbatchroll"
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
		let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_id=prod_batch_id;
		if(formObj.id){
			this.MsProdBatchRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

        let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		let formObj=MsProdBatchRoll.getdata();
		formObj.prod_batch_id=prod_batch_id;

		if(formObj.id){
			this.MsProdBatchRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchrollsearchWindow').window('close');
		MsProdBatchRoll.get(d.prod_batch_id)
		msApp.resetForm('prodbatchrollFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsProdBatchRollModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchrollTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	
	rollgrid(data){
		let self = this;
		$('#prodbatchrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			onLoadSuccess: function(data){
				var batch_qty=0;
				var rcv_qty=0;
				var bal_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					bal_qty+=data.rows[i]['bal_qty'].replace(/,/g,'')*1;
					}
					$(this).datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						bal_qty: bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			},
			onSelect:function(index,row){
				let prodbatch_selected_roll_total=$("#prodbatch_selected_roll_total").html();
				let total=prodbatch_selected_roll_total*1+row.bal_qty.replace(/,/g,'')*1;
				$("#prodbatch_selected_roll_total").html(total);
			},
			onUnselect:function(index,row){
				let prodbatch_selected_roll_total=$("#prodbatch_selected_roll_total").html();
				let total=prodbatch_selected_roll_total*1-row.bal_qty.replace(/,/g,'')*1;
				$("#prodbatch_selected_roll_total").html(total);
			},
			onSelectAll:function(rows){
				let total=0;
				for(var i=0; i<rows.length; i++){
				total+=rows[i]['bal_qty'].replace(/,/g,'')*1;
				}				
				$("#prodbatch_selected_roll_total").html(total);
			},
			onUnselectAll:function(rows){
				let total=0;
				$("#prodbatch_selected_roll_total").html(total);
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		/*let params={};
		let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		params.prod_batch_id=prod_batch_id
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#prodbatchrollsearchTbl').datagrid('loadData', response.data);
			$("#prodbatch_selected_roll_total").html(0);
			$('#prodbatchrollsearchWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});*/
		$('#prodbatchrollsearchWindow').window('open');
	}
	search(){
		let params={};
		let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		let issue_no=$('#prodbatchrollsearchFrm  [name=issue_no]').val();
		let style_ref=$('#prodbatchrollsearchFrm  [name=style_ref]').val();
		let buyer_id=$('#prodbatchrollsearchFrm  [name=buyer_id]').val();
		params.prod_batch_id=prod_batch_id
		params.issue_no=issue_no
		params.style_ref=style_ref
		params.buyer_id=buyer_id
		let data= axios.get(this.route+"/create",{params});
		data.then(function (response) {
			$('#prodbatchrollsearchTbl').datagrid('loadData', response.data);
			$("#prodbatch_selected_roll_total").html(0);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	getdata(){
		let checked=$('#prodbatchrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}

		let formObj={};
		let i=1;
		$.each(checked, function (idx, val) {
				formObj['so_dyeing_fabric_rcv_rol_id['+i+']']=val.id;
				formObj['qty['+i+']']=val.bal_qty.replace(/,/g,'')*1;
			i++;
		});
		return formObj;
	}

	selectAll()
	{
		$('#prodbatchrollsearchTbl').datagrid('selectAll');
	}
	unselectAll()
	{
		$('#prodbatchrollsearchTbl').datagrid('unselectAll');
	}

	
}
window.MsProdBatchRoll=new MsProdBatchRollController(new MsProdBatchRollModel());
MsProdBatchRoll.showGrid([]);
MsProdBatchRoll.rollgrid([]);
