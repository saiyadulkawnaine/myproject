let MsProdBatchRdTrimModel = require('./MsProdBatchRdTrimModel');
require('./../../datagrid-filter.js');
class MsProdBatchRdTrimController {
	constructor(MsProdBatchRdTrimModel)
	{
		this.MsProdBatchRdTrimModel = MsProdBatchRdTrimModel;
		this.formId='prodbatchrdtrimFrm';
		this.dataTable='#prodbatchrdtrimTbl';
		this.route=msApp.baseUrl()+"/prodbatchrdtrim"
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
			this.MsProdBatchRdTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchRdTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchRdTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchRdTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchrdtrimsearchWindow').window('close');
		MsProdBatchRdTrim.get(d.prod_batch_id)
		msApp.resetForm('prodbatchrdtrimFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workReceive = this.MsProdBatchRdTrimModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchrdtrimTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchRdTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	rdtrimWindow(){
		MsProdBatchRdTrim.searchRdTrim();
		$('#prodbatchrdtrimWindow').window('open');

	}

	showprodbatchrdtrimGrid(data){
		let self = this;
		$('#prodbatchrdtrimsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchrdtrimFrm [name=itemclass_id]').val(row.id);
					$('#prodbatchrdtrimFrm [name=itemclass_name]').val(row.name);
					$('#prodbatchrdtrimFrm [name=uom_id]').val(row.costing_uom_id);
					$('#prodbatchrdtrimFrm [name=root_batch_trim_id]').val(row.root_batch_trim_id);
					$('#prodbatchrdtrimWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchRdTrim()
	{
		let prod_batch_id=$('#prodbatchrdFrm  [name=id]').val();
		let root_batch_id=$('#prodbatchrdFrm  [name=root_batch_id]').val();
		let params={};
		params.prod_batch_id=prod_batch_id;
		params.root_batch_id=root_batch_id;
		let data= axios.get(this.route+"/gettrim",{params});
		data.then(function (response) {
			$('#prodbatchrdtrimsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate(){
	let qty=$('#prodbatchrdtrimFrm [name=qty]').val()
	let wgt_per_unit=$('#prodbatchrdtrimFrm [name=wgt_per_unit]').val();
	let wgt_qty=(qty*1)*(wgt_per_unit*1);
	$('#prodbatchrdtrimFrm [name=wgt_qty]').val(wgt_qty);

	}
}
window.MsProdBatchRdTrim=new MsProdBatchRdTrimController(new MsProdBatchRdTrimModel());
MsProdBatchRdTrim.showGrid([]);
MsProdBatchRdTrim.showprodbatchrdtrimGrid([]);

