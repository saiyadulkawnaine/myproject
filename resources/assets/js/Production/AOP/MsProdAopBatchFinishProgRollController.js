require('./../../datagrid-filter.js');
let MsProdAopBatchFinishProgRollModel = require('./MsProdAopBatchFinishProgRollModel');
class MsProdAopBatchFinishProgRollController {
	constructor(MsProdAopBatchFinishProgRollModel)
	{
		this.MsProdAopBatchFinishProgRollModel = MsProdAopBatchFinishProgRollModel;
		this.formId='prodaopbatchfinishprogrollFrm';
		this.dataTable='#prodaopbatchfinishprogrollTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchfinishprogroll"
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
		let prod_batch_finish_prog_id=$('#prodaopbatchfinishprogFrm  [name=id]').val();
		//let prod_batch_id=$('#prodaopbatchFrm  [name=id]').val();
		let formObj=MsProdAopBatchFinishProgRoll.getSelection();
		formObj.prod_batch_finish_prog_id=prod_batch_finish_prog_id
		if(formObj.id){
			this.MsProdAopBatchFinishProgRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchFinishProgRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		//msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchFinishProgRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchFinishProgRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_batch_finish_prog_id=$('#prodaopbatchfinishprogFrm  [name=id]').val();
		MsProdAopBatchFinishProgRoll.resetForm();
		MsProdAopBatchFinishProgRoll.get(prod_batch_finish_prog_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchFinishProgRollModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_finish_prog_id)
	{
		let params={};
		params.prod_batch_finish_prog_id=prod_batch_finish_prog_id
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#prodaopbatchfinishprogrollTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchFinishProgRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	openrollWindow()
	{
		$('#prodaopbatchfinishprogrollsearchwindow').window('open');
		MsProdAopBatchFinishProgRoll.serachRoll();
	}

	rollSearchGrid(data){
		let self=this;
		$('#prodaopbatchfinishprogrollsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
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
		let prod_batch_finish_prog_id=$('#prodaopbatchfinishprogFrm [name=id]').val();
		let params={};
		params.prod_batch_finish_prog_id=prod_batch_finish_prog_id;
		let d=axios.get(this.route+'/getroll',{params})
		.then(function(response){
			$('#prodaopbatchfinishprogrollsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	
	getSelection(){
		let checked=$('#prodaopbatchfinishprogrollsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}

		let formObj={};
		let i=1;
		$.each(checked, function (idx, val) {
			formObj['prod_aop_batch_roll_id['+i+']']=val.id;
			i++;
		});
		$('#prodaopbatchfinishprogrollsearchwindow').window('close');
		return formObj;

	}
}
window.MsProdAopBatchFinishProgRoll=new MsProdAopBatchFinishProgRollController(new MsProdAopBatchFinishProgRollModel());
MsProdAopBatchFinishProgRoll.showGrid([]);
MsProdAopBatchFinishProgRoll.rollSearchGrid([]);