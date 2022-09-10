require('./../../datagrid-filter.js');
let MsProdAopBatchFinishQcRollModel = require('./MsProdAopBatchFinishQcRollModel');
class MsProdAopBatchFinishQcRollController {
	constructor(MsProdAopBatchFinishQcRollModel)
	{
		this.MsProdAopBatchFinishQcRollModel = MsProdAopBatchFinishQcRollModel;
		this.formId='prodaopbatchfinishqcrollFrm';
		this.dataTable='#prodaopbatchfinishqcrollTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchfinishqcroll"
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
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm  [name=id]').val();
		let formObj=msApp.get('prodaopbatchfinishqcrollFrm');
		formObj.prod_aop_batch_finish_qc_id=prod_aop_batch_finish_qc_id
		if(formObj.id){
			this.MsProdAopBatchFinishQcRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchFinishQcRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm  [name=id]').val();
		let formObj=msApp.get('prodaopbatchfinishqcrollmultiFrm');
		formObj.prod_aop_batch_finish_qc_id=prod_aop_batch_finish_qc_id
		this.MsProdAopBatchFinishQcRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		msApp.resetForm('prodaopbatchfinishqcrollmultiFrm');
		$('#prodaopbatchfinishqcrollmultiwindow').window('close');
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchFinishQcRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchFinishQcRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm  [name=id]').val();
		MsProdAopBatchFinishQcRoll.resetForm();
		MsProdAopBatchFinishQcRoll.get(prod_aop_batch_finish_qc_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchFinishQcRollModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_aop_batch_finish_qc_id)
	{
		let params={};
		params.prod_aop_batch_finish_qc_id=prod_aop_batch_finish_qc_id
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#prodaopbatchfinishqcrollTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var rcv_qty=0;
				var qc_pass_qty=0;
				var reject_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					qc_pass_qty+=data.rows[i]['qc_pass_qty'].replace(/,/g,'')*1;
					reject_qty+=data.rows[i]['reject_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						qc_pass_qty: qc_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						reject_qty: reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchFinishQcRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	openrollWindow()
	{
		$('#prodaopbatchfinishqcrollsearchwindow').window('open');
		MsProdAopBatchFinishQcRoll.serachRoll();

	}

	rollSearchGrid(data){
		let self=this;
		$('#prodaopbatchfinishqcrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
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
	serachRoll(){
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm [name=id]').val();
		let params={};
		params.prod_aop_batch_finish_qc_id=prod_aop_batch_finish_qc_id;
		let d=axios.get(this.route+'/getroll',{params})
		.then(function(response){
			$('#prodaopbatchfinishqcrollsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	getSelection(){
		let checked=$('#prodaopbatchfinishqcrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >500 ){
			alert("More Than 100 checked not allowed");
			return;
		}

		let prod_aop_batch_roll_id=[];
		let i=1;
		$.each(checked, function (idx, val) {
			prod_aop_batch_roll_id.push(val.id)
			i++;
		});
		prod_aop_batch_roll_id=prod_aop_batch_roll_id.join(',');
		$('#prodaopbatchfinishqcrollsearchTbl').datagrid('clearSelections');
		//MsProdAopBatchFinishQcRoll.rollSearchGrid([]);
		$('#prodaopbatchfinishqcrollsearchTbl').datagrid('loadData',[]);
		$('#prodaopbatchfinishqcrollsearchwindow').window('close');
		return prod_aop_batch_roll_id;

	}

	openForm(){
		let params={}
		$('#prodaopbatchfinishqcrollmultiwindow').window('open');
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm [name=id]').val();
		let prod_aop_batch_roll_ids=MsProdAopBatchFinishQcRoll.getSelection();
		params.prod_aop_batch_roll_ids=prod_aop_batch_roll_ids;
		params.prod_aop_batch_finish_qc_id=prod_aop_batch_finish_qc_id;
		let d=axios.get(this.route+'/create',{params})
		.then(function(response){
			//$('#prodaopbatchfinishqcrollsearchTbl').datagrid('loadData',response.data);
			$('#prodaopbatchfinishqcrollmultiFrmContainer').html(response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_reject_multi(iteration,count){
		let batch_qty=$('#prodaopbatchfinishqcrollmultiFrm input[name="batch_qty['+iteration+']"]').val();
		let qty=$('#prodaopbatchfinishqcrollmultiFrm input[name="qty['+iteration+']"]').val();
		let reject=(batch_qty*1)-(qty*1);
		$('#prodaopbatchfinishqcrollmultiFrm input[name="reject_qty['+iteration+']"]').val(reject);

	}

	calculate_reject(){
		let batch_qty=$('#prodaopbatchfinishqcrollFrm [name=batch_qty]').val();
		let qty=$('#prodaopbatchfinishqcrollFrm [name=qty]').val();
		let reject=(batch_qty*1)-(qty*1);
		$('#prodaopbatchfinishqcrollFrm [name=reject_qty]').val(reject);
	}

	selectAll(table)
	{
		$(table).datagrid('selectAll');
	}
	unselectAll(table)
	{
		$(table).datagrid('unselectAll');
	}

	copyDia(dia,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#prodaopbatchfinishqcrollmultiFrm input[name="dia_width['+i+']"]').val(dia)
		}
	}

	copyGSM(gsm,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#prodaopbatchfinishqcrollmultiFrm input[name="gsm_weight['+i+']"]').val(gsm)
		}
	}
	copyGrade(grade,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#prodaopbatchfinishqcrollmultiFrm select[name="grade_id['+i+']"]').val(grade)
		}
	}

	import(){
		let prod_aop_batch_finish_qc_id=$('#prodaopbatchfinishqcFrm  [name=id]').val();
		let file = document.getElementById('roll_file');

		let formData = new FormData();
		formData.append('prod_aop_batch_finish_qc_id',prod_aop_batch_finish_qc_id);
		formData.append('file_src',file.files[0]);
		this.MsProdAopBatchFinishQcRollModel.upload(this.route+'/import','POST',formData,this.response);

	}
}
window.MsProdAopBatchFinishQcRoll=new MsProdAopBatchFinishQcRollController(new MsProdAopBatchFinishQcRollModel());
MsProdAopBatchFinishQcRoll.showGrid([]);
MsProdAopBatchFinishQcRoll.rollSearchGrid([]);