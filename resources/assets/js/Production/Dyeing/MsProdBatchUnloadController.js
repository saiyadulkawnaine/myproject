require('./../../datagrid-filter.js');
let MsProdBatchUnloadModel = require('./MsProdBatchUnloadModel');
class MsProdBatchUnloadController {
	constructor(MsProdBatchUnloadModel)
	{
		this.MsProdBatchUnloadModel = MsProdBatchUnloadModel;
		this.formId='prodbatchunloadFrm';
		this.dataTable='#prodbatchunloadTbl';
		this.route=msApp.baseUrl()+"/prodbatchunload"
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
			this.MsProdBatchUnloadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchUnloadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		//let unload_posting_date=$('#prodbatchunloadFrm  [name=unload_posting_date]').val();
		msApp.resetForm(this.formId);
		//$('#prodbatchunloadFrm  [name=unload_posting_date]').val(unload_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchUnloadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchUnloadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchunloadTbl').datagrid('reload');
		$('#prodbatchunloadFrm  [name=id]').val(d.id);
		MsProdBatchUnload.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchUnloadModel.get(index,row);
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
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var batch_wgt=0;
				var fabric_wgt=0;
				for(var i=0; i<data.rows.length; i++){
					batch_wgt+=data.rows[i]['batch_wgt'].replace(/,/g,'')*1;
					fabric_wgt+=data.rows[i]['fabric_wgt'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					batch_wgt: batch_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fabric_wgt: fabric_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchUnload.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodbatchunloadbatchWindow').window('open');
	}

	showprodbatchbatchGrid(data){
		let self = this;
		$('#prodbatchunloadbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchunloadFrm [name=id]').val(row.id);
					$('#prodbatchunloadFrm [name=batch_no]').val(row.batch_no);
					$('#prodbatchunloadFrm [name=batch_date]').val(row.batch_date);
					$('#prodbatchunloadFrm [name=company_id]').val(row.company_id);
					$('#prodbatchunloadFrm [name=location_id]').val(row.location_id);
					$('#prodbatchunloadFrm [id="fabric_color_id"]').val(row.fabric_color_id);
					$('#prodbatchunloadFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodbatchunloadFrm [name=batch_for]').val(row.batch_for);
                    $('#prodbatchunloadFrm [name=colorrange_id]').val(row.colorrange_id);
                    $('#prodbatchunloadFrm [name=lap_dip_no]').val(row.lap_dip_no);
                    $('#prodbatchunloadFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodbatchunloadFrm [name=batch_wgt]').val(row.batch_wgt);
                    $('#prodbatchunloadFrm [name=machine_no]').val(row.machine_no);
                    $('#prodbatchunloadFrm [name=brand]').val(row.brand);
                    $('#prodbatchunloadFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchunloadbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodbatchunloadbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodbatchunloadbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodbatchunloadbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodbatchunloadbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showRoll(prod_batch_id)
	{
		let data= axios.get(this.route+"/getroll?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchunloadrollTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showRollGrid(data)
	{
		let self=this;
		$('#prodbatchunloadrollTbl').datagrid({
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
		params.from_unload_date=$('#from_unload_date').val();
		params.to_unload_date=$('#to_unload_date').val();
		params.from_posting_date=$('#from_posting_date').val();
		params.to_posting_date=$('#to_posting_date').val();
		let data= axios.get(this.route+"/getlist",{params});
		data.then(function (response) {
			$('#prodbatchunloadTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsProdBatchUnload=new MsProdBatchUnloadController(new MsProdBatchUnloadModel());
MsProdBatchUnload.showGrid();
MsProdBatchUnload.showprodbatchbatchGrid([]);
MsProdBatchUnload.showRollGrid([]);

 $('#prodbatchunloadtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_id = $('#prodbatchunloadFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_id===''){
				$('#prodbatchunloadtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchUnload.showRoll(prod_batch_id);
		}
	}
}); 
