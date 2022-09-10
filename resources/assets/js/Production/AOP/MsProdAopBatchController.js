require('./../../datagrid-filter.js');
let MsProdAopBatchModel = require('./MsProdAopBatchModel');
class MsProdAopBatchController {
	constructor(MsProdAopBatchModel)
	{
		this.MsProdAopBatchModel = MsProdAopBatchModel;
		this.formId='prodaopbatchFrm';
		this.dataTable='#prodaopbatchTbl';
		this.route=msApp.baseUrl()+"/prodaopbatch"
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
			this.MsProdAopBatchModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodaopbatchFrm [id="batch_color_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopbatchTbl').datagrid('reload');
		$('#prodaopbatchFrm  [name=id]').val(d.id);
		MsProdAopBatch.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchModel.get(index,row);
        workReceive.then(function(response){
			$('#prodaopbatchFrm [id="batch_color_id"]').combobox('setValue', response.data.fromData.batch_color_id);
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
			showFooter:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var fabric_wgt=0;
				
				for(var i=0; i<data.rows.length; i++){
					fabric_wgt+=data.rows[i]['fabric_wgt'].replace(/,/g,'')*1;
				}
				$('#prodaopbatchTbl').datagrid('reloadFooter', [
				{ 
					fabric_wgt: fabric_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatch.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	



	soWindow(){
		$('#prodaopbatchsoWindow').window('open');
	}
	soGrid(data){
		let self = this;
		$('#prodaopbatchsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#prodaopbatchFrm [name=so_aop_id]').val(row.id);
				$('#prodaopbatchFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#prodaopbatchFrm [name=company_id]').val(row.company_id);
				$('#prodaopbatchFrm [name=buyer_id]').val(row.buyer_id);
				$('#prodaopbatchsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getaoporder()
	{
		let so_no=$('#prodaopbatchsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#soaopfabricisuFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#prodaopbatchsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	searchBatch()
	{
		let params={};
		params.from_batch_date=$('#from_batch_date').val();
		params.to_batch_date=$('#to_batch_date').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodaopbatchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	
	pdf(){
		var id= $('#prodaopbatchFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	pdfRoll(){
		var id= $('#prodaopbatchFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(this.route+"/reportroll?id="+id);
	}

}
window.MsProdAopBatch=new MsProdAopBatchController(new MsProdAopBatchModel());
MsProdAopBatch.showGrid();
MsProdAopBatch.soGrid([]);

 $('#prodaopbatchtabs').tabs({
	onSelect:function(title,index){
		let prod_aop_batch_id = $('#prodaopbatchFrm  [name=id]').val();
		if(index==1){
			if(prod_aop_batch_id===''){
				$('#prodaopbatchtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdAopBatchRoll.resetForm();
			MsProdAopBatchRoll.get(prod_aop_batch_id);
		}
		
		if(index==2){
			if(prod_aop_batch_id===''){
				$('#prodaopbatchtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdAopBatchProcess.resetForm();
			MsProdAopBatchProcess.get(prod_aop_batch_id);
		}
	}
}); 
