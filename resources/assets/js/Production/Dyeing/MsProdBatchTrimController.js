let MsProdBatchTrimModel = require('./MsProdBatchTrimModel');
require('./../../datagrid-filter.js');
class MsProdBatchTrimController {
	constructor(MsProdBatchTrimModel)
	{
		this.MsProdBatchTrimModel = MsProdBatchTrimModel;
		this.formId='prodbatchtrimFrm';
		this.dataTable='#prodbatchtrimTbl';
		this.route=msApp.baseUrl()+"/prodbatchtrim"
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
			this.MsProdBatchTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchtrimsearchWindow').window('close');
		MsProdBatchTrim.get(d.prod_batch_id)
		msApp.resetForm('prodbatchtrimFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workReceive = this.MsProdBatchTrimModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchtrimTbl').datagrid('loadData', response.data);
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
				var qty=0;
				var wgt_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				wgt_qty+=data.rows[i]['wgt_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					wgt_qty: wgt_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	trimWindow(){
		MsProdBatchTrim.searchTrim();
		$('#prodbatchtrimWindow').window('open');

	}

	showprodbatchtrimGrid(data){
		let self = this;
		$('#prodbatchtrimsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchtrimFrm [name=itemclass_id]').val(row.id);
					$('#prodbatchtrimFrm [name=itemclass_name]').val(row.name);
					$('#prodbatchtrimFrm [name=uom_id]').val(row.costing_uom_id);
					$('#prodbatchtrimWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchTrim()
	{
		let params={};
		let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		params.prod_batch_id=prod_batch_id;
		let data= axios.get(this.route+"/gettrim",{params});
		data.then(function (response) {
			$('#prodbatchtrimsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate(){
	let qty=$('#prodbatchtrimFrm [name=qty]').val()
	let wgt_per_unit=$('#prodbatchtrimFrm [name=wgt_per_unit]').val();
	let wgt_qty=(qty*1)*(wgt_per_unit*1);
	$('#prodbatchtrimFrm [name=wgt_qty]').val(wgt_qty);

	}
}
window.MsProdBatchTrim=new MsProdBatchTrimController(new MsProdBatchTrimModel());
MsProdBatchTrim.showGrid([]);
MsProdBatchTrim.showprodbatchtrimGrid([]);

