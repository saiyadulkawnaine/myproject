let MsProdKnitRcvByQcModel = require('./MsProdKnitRcvByQcModel');
require('./../../datagrid-filter.js');
class MsProdKnitRcvByQcController {
	constructor(MsProdKnitRcvByQcModel)
	{
		this.MsProdKnitRcvByQcModel = MsProdKnitRcvByQcModel;
		this.formId='prodknitrcvbyqcFrm';
		this.dataTable='#prodknitrcvbyqcTbl';
		this.route=msApp.baseUrl()+"/prodknitrcvbyqc"
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
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsProdKnitRcvByQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitRcvByQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitAndClose()
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
		let formObj=this.getSelections();
		if(formObj.id)
		{
			this.MsProdKnitRcvByQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsProdKnitRcvByQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm (){
		msApp.resetForm(this.formId);
	}

	remove(){
		let formObj=msApp.get(this.formId);
		this.MsProdKnitRcvByQcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id){
		event.stopPropagation()
		this.MsProdKnitRcvByQcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdKnitRcvByQc.create();
		$('#prodknitrcvbyqcTbl').datagrid('reload');
		msApp.resetForm('prodknitrcvbyqcFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
	    this.MsProdKnitRcvByQcModel.get(index,row);
	}

	showGrid(){

		let self=this;
		var dg = $(this.dataTable);
		dg.datagrid({
			method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	create(data)
	{
        //let data= msApp.getJson(this.route+"/create",{data})
        // let data= axios.get(this.route+"/create")
		// .then(function (response) {
		// 	//$('#expinvoiceordermatrix').html(response.data);
		// 	//$('#prodknitrcvbyqcGetTbl').datagrid('loadData', response.data);
		// 	MsProdKnitRcvByQc.showGridCreate()

		// })
		// .catch(function (error) {
		// 	console.log(error);
		// });
		MsProdKnitRcvByQc.getRoll()
	}

	getRoll(){
		let data= axios.get(this.route+"/importroll")
		.then(function (response) {
			$('#prodknitrcvbyqcGetTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridCreate(data){
		let self=this;
		var dg=$('#prodknitrcvbyqcGetTbl');
		dg.datagrid({
			//method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	/* get(prod_knit_item_roll_id)
	{
		let data= axios.get(this.route+"?prod_knit_item_roll_id="+prod_knit_item_roll_id);
		data.then(function (response) {
			$('#prodknititemrollTbl').datagrid('loadData', response.data);
			$('#poyarnitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	} */
	

	getSelections()
	{
		let formObj={};
		//formObj.po_trim_id=$('#potrimFrm  [name=id]').val();
		let i=1;
		$.each($('#prodknitrcvbyqcGetTbl').datagrid('getSelections'), function (idx, val) {
			formObj['prod_knit_item_roll_id['+i+']']=val.id
			i++;
		});
		$('#prodknitrcvbyqcGetTbl').datagrid('clearSelections');
		$('#prodknititemrollrcvWindow').window('close');
		return formObj;
	}

}
window.MsProdKnitRcvByQc=new MsProdKnitRcvByQcController(new MsProdKnitRcvByQcModel());
$('#prodknitrcvbyqcGetTbl').datagrid();
MsProdKnitRcvByQc.create();
//MsProdKnitRcvByQc.showGrid();
//MsProdKnitRcvByQc.showGridCreate([]);