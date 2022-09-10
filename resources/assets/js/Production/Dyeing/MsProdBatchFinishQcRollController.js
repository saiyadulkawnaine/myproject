require('./../../datagrid-filter.js');
let MsProdBatchFinishQcRollModel = require('./MsProdBatchFinishQcRollModel');
class MsProdBatchFinishQcRollController {
	constructor(MsProdBatchFinishQcRollModel)
	{
		this.MsProdBatchFinishQcRollModel = MsProdBatchFinishQcRollModel;
		this.formId='prodbatchfinishqcrollFrm';
		this.dataTable='#prodbatchfinishqcrollTbl';
		this.route=msApp.baseUrl()+"/prodbatchfinishqcroll"
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
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm  [name=id]').val();
		let formObj=msApp.get('prodbatchfinishqcrollFrm');
		formObj.prod_batch_finish_qc_id=prod_batch_finish_qc_id
		if(formObj.id){
			this.MsProdBatchFinishQcRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchFinishQcRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm  [name=id]').val();
		let formObj=msApp.get('prodbatchfinishqcrollmultiFrm');
		formObj.prod_batch_finish_qc_id=prod_batch_finish_qc_id
		this.MsProdBatchFinishQcRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		msApp.resetForm('prodbatchfinishqcrollmultiFrm');
		$('#prodbatchfinishqcrollmultiwindow').window('close');
	}
	

	resetForm ()
	{
		//msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchFinishQcRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchFinishQcRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm  [name=id]').val();
		MsProdBatchFinishQcRoll.resetForm();
		MsProdBatchFinishQcRoll.get(prod_batch_finish_qc_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchFinishQcRollModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_finish_qc_id)
	{
		let params={};
		params.prod_batch_finish_qc_id=prod_batch_finish_qc_id
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#prodbatchfinishqcrollTbl').datagrid('loadData', response.data);
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
				var batch_qty=0;
				var qc_pass_qty=0;
				var reject_qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
					qc_pass_qty+=data.rows[i]['qc_pass_qty'].replace(/,/g,'')*1;
					reject_qty+=data.rows[i]['reject_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						qc_pass_qty: qc_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						reject_qty: reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchFinishQcRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	openrollWindow()
	{
		$('#prodbatchfinishqcrollsearchwindow').window('open');
		MsProdBatchFinishQcRoll.serachRoll();

	}

	rollSearchGrid(data){
		let self=this;
		$('#prodbatchfinishqcrollsearchTbl').datagrid({
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
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm [name=id]').val();
		let params={};
		params.prod_batch_finish_qc_id=prod_batch_finish_qc_id;
		let d=axios.get(this.route+'/getroll',{params})
		.then(function(response){
			$('#prodbatchfinishqcrollsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	getSelection(){
		let checked=$('#prodbatchfinishqcrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >500 ){
			alert("More Than 100 checked not allowed");
			return;
		}

		let prod_batch_roll_id=[];
		let i=1;
		$.each(checked, function (idx, val) {
			prod_batch_roll_id.push(val.id)
			i++;
		});
		prod_batch_roll_id=prod_batch_roll_id.join(',');
		$('#prodbatchfinishqcrollsearchTbl').datagrid('clearSelections');
		//MsProdBatchFinishQcRoll.rollSearchGrid([]);
		$('#prodbatchfinishqcrollsearchTbl').datagrid('loadData',[]);
		$('#prodbatchfinishqcrollsearchwindow').window('close');
		return prod_batch_roll_id;

	}

	openForm(){
		let params={}
		$('#prodbatchfinishqcrollmultiwindow').window('open');
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm [name=id]').val();
		let prod_batch_roll_ids=MsProdBatchFinishQcRoll.getSelection();
		params.prod_batch_roll_ids=prod_batch_roll_ids;
		params.prod_batch_finish_qc_id=prod_batch_finish_qc_id;
		let d=axios.get(this.route+'/create',{params})
		.then(function(response){
			//$('#prodbatchfinishqcrollsearchTbl').datagrid('loadData',response.data);
			$('#prodbatchfinishqcrollmultiFrmContainer').html(response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_reject_multi(iteration,count){
		let batch_qty=$('#prodbatchfinishqcrollmultiFrm input[name="batch_qty['+iteration+']"]').val();
		let qty=$('#prodbatchfinishqcrollmultiFrm input[name="qty['+iteration+']"]').val();
		let reject=(batch_qty*1)-(qty*1);
		$('#prodbatchfinishqcrollmultiFrm input[name="reject_qty['+iteration+']"]').val(reject);

	}

	calculate_reject(){
		let batch_qty=$('#prodbatchfinishqcrollFrm [name=batch_qty]').val();
		let qty=$('#prodbatchfinishqcrollFrm [name=qty]').val();
		let reject=(batch_qty*1)-(qty*1);
		$('#prodbatchfinishqcrollFrm [name=reject_qty]').val(reject);
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
			$('#prodbatchfinishqcrollmultiFrm input[name="dia_width['+i+']"]').val(dia)
		}
	}

	copyGSM(gsm,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#prodbatchfinishqcrollmultiFrm input[name="gsm_weight['+i+']"]').val(gsm)
		}
	}
	copyGrade(grade,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#prodbatchfinishqcrollmultiFrm select[name="grade_id['+i+']"]').val(grade)
		}
	}

	import(){
		let prod_batch_finish_qc_id=$('#prodbatchfinishqcFrm  [name=id]').val();
		let file = document.getElementById('roll_file');

		let formData = new FormData();
		formData.append('prod_batch_finish_qc_id',prod_batch_finish_qc_id);
		formData.append('file_src',file.files[0]);
		this.MsProdBatchFinishQcRollModel.upload(this.route+'/import','POST',formData,this.response);

	}
}
window.MsProdBatchFinishQcRoll=new MsProdBatchFinishQcRollController(new MsProdBatchFinishQcRollModel());
MsProdBatchFinishQcRoll.showGrid([]);
MsProdBatchFinishQcRoll.rollSearchGrid([]);