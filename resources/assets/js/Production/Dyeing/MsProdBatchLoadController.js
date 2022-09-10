require('./../../datagrid-filter.js');
let MsProdBatchLoadModel = require('./MsProdBatchLoadModel');
class MsProdBatchLoadController {
	constructor(MsProdBatchLoadModel)
	{
		this.MsProdBatchLoadModel = MsProdBatchLoadModel;
		this.formId='prodbatchloadFrm';
		this.dataTable='#prodbatchloadTbl';
		this.route=msApp.baseUrl()+"/prodbatchload"
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
			this.MsProdBatchLoadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchLoadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodbatchloadFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodbatchloadFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchLoadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchLoadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchloadTbl').datagrid('reload');
		$('#prodbatchloadFrm  [name=id]').val(d.id);
		MsProdBatchLoad.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchLoadModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid(){

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchLoad.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodbatchloadbatchWindow').window('open');
	}

	showprodbatchbatchGrid(data){
		let self = this;
		$('#prodbatchloadbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchloadFrm [name=id]').val(row.id);
					$('#prodbatchloadFrm [name=batch_no]').val(row.batch_no);
					$('#prodbatchloadFrm [name=batch_date]').val(row.batch_date);
					$('#prodbatchloadFrm [name=company_id]').val(row.company_id);
					$('#prodbatchloadFrm [name=location_id]').val(row.location_id);
					$('#prodbatchloadFrm [id="fabric_color_id"]').val(row.fabric_color_id);
					$('#prodbatchloadFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodbatchloadFrm [name=batch_for]').val(row.batch_for);
                    $('#prodbatchloadFrm [name=colorrange_id]').val(row.colorrange_id);
                    $('#prodbatchloadFrm [name=lap_dip_no]').val(row.lap_dip_no);
                    $('#prodbatchloadFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodbatchloadFrm [name=batch_wgt]').val(row.batch_wgt);
                    $('#prodbatchloadFrm [name=machine_no]').val(row.machine_no);
                    $('#prodbatchloadFrm [name=brand]').val(row.brand);
                    $('#prodbatchloadFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchloadbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodbatchloadbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodbatchloadbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodbatchloadbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodbatchloadbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showRoll(prod_batch_id)
	{
		let data= axios.get(this.route+"/getroll?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchloadrollTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showRollGrid(data)
	{
		let self=this;
		$('#prodbatchloadrollTbl').datagrid({
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



	searchList()
	{
		let params={};
		params.from_batch_date=$('#from_batch_date').val();
		params.to_batch_date=$('#to_batch_date').val();
		params.from_load_posting_date=$('#from_load_posting_date').val();
		params.to_load_posting_date=$('#to_load_posting_date').val();
		let data= axios.get(this.route+"/getlist",{params});
		data.then(function (response) {
			$('#prodbatchloadTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsProdBatchLoad=new MsProdBatchLoadController(new MsProdBatchLoadModel());
MsProdBatchLoad.showGrid();
MsProdBatchLoad.showprodbatchbatchGrid([]);
MsProdBatchLoad.showRollGrid([]);

 $('#prodbatchloadtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_id = $('#prodbatchloadFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_id===''){
				$('#prodbatchloadtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchLoad.showRoll(prod_batch_id);
		}
	}
}); 
