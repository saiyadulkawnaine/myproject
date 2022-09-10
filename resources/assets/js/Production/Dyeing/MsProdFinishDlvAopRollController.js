let MsProdFinishDlvAopRollModel = require('./MsProdFinishDlvAopRollModel');
require('./../../datagrid-filter.js');
class MsProdFinishDlvAopRollController {
	constructor(MsProdFinishDlvAopRollModel)
	{
		this.MsProdFinishDlvAopRollModel = MsProdFinishDlvAopRollModel;
		this.formId='prodfinishdlvaoprollFrm';
		this.dataTable='#prodfinishdlvaoprollTbl';
		this.route=msApp.baseUrl()+"/prodfinishdlvaoproll"
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
		let prod_finish_dlv_id = $('#prodfinishdlvaopFrm  [name=id]').val();
		let formObj={};
		let rolls=MsProdFinishDlvAopRoll.getSelections();
		formObj.prod_finish_dlv_id=prod_finish_dlv_id;
		formObj.roll_id=rolls;
		this.MsProdFinishDlvAopRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishDlvAopRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishDlvAopRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_finish_dlv_id = $('#prodfinishdlvaopFrm  [name=id]').val();
		MsProdFinishDlvAopRoll.get(prod_finish_dlv_id)
		msApp.resetForm('prodfinishdlvaoprollTbl');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdFinishDlvAopRollModel.get(index,row);
		carton.then(function(response){
			//$('#prodfinishdlvaopFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
	}
	get(prod_finish_dlv_id){
		let params={};
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		let data= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodfinishdlvaoprollTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	showGrid(data){

		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var roll_weight=0;
				var batch_qty=0;
				for(var i=0; i<data.rows.length; i++){
					roll_weight+=data.rows[i]['roll_weight'].replace(/,/g,'')*1;
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				}
				$('#prodfinishdlvaoprollTbl').datagrid('reloadFooter', [
					{ 
						roll_weight: roll_weight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdFinishDlvAopRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Del</span></a>';
	}

	getRoll(){
		let prod_finish_dlv_id=$('#prodfinishdlvaopFrm [name=id]').val();
		let aop_from_qc_date=$('#aop_from_qc_date').val();
		let aop_to_qc_date=$('#aop_to_qc_date').val();
		let aop_batch_no=$('#aop_batch_no').val();
		let aop_batch_date_from=$('#aop_batch_date_from').val();
		let aop_batch_date_to=$('#aop_batch_date_to').val();
		let params={};
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		params.aop_from_qc_date=aop_from_qc_date;
		params.aop_to_qc_date=aop_to_qc_date;
		params.aop_batch_no=aop_batch_no;
		params.aop_batch_date_from=aop_batch_date_from;
		params.aop_batch_date_to=aop_batch_date_to;

		if(!params.prod_finish_dlv_id){
			alert('Select Roll First');
			return;
		}

		let data= axios.get(this.route+"/importaoproll",{params})
		.then(function (response) {
			$('#prodfinishdlvaoprollselectionTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	import(){
		$('#prodfinishdlvaoprollWindow').window('open');
		//MsProdFinishDlvAopRoll.getRoll();

	}

	showGridRollSelection(data){
		let self=this;
		var dg=$('#prodfinishdlvaoprollselectionTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
				$('#prodfinishdlvaoprollselectedTbl').datagrid('appendRow',row);
				let total=MsProdFinishDlvAopRoll.getsum();
				MsProdFinishDlvAopRoll.reloadFooter(total);
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
				$('#prodfinishdlvaoprollselectionTbl').datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						qc_pass_qty: qc_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						reject_qty: reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridRollSelected(data){
		let self=this;
		var dg=$('#prodfinishdlvaoprollselectedTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
				$('#prodfinishdlvaoprollselectedTbl').datagrid('deleteRow', index);
				let total=MsProdFinishDlvAopRoll.getsum();
				MsProdFinishDlvAopRoll.reloadFooter(total);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		var roll = [];
		let i=1;
		$.each($('#prodfinishdlvaoprollselectedTbl').datagrid('getRows'), function (idx, val) {
		roll.push(val.id);
		});
		MsProdFinishDlvAopRoll.showGridRollSelected([]);
		let rolls= roll.join(',');

        
		/*let rolls= roll.join(',');
		$('#prodfinishqcFrm [name=other_roll_no]').val(rolls);
        $('#prodfinishqcFrm [name=other_prod_finish_item_roll_id]').val(rolls);*/
        $('#prodfinishdlvaoprollWindow').window('close');
        return rolls;
	}

	getsum(){
		let data={};
		let total_qty=0;
		let total_qc_qty=0;
		let total_reject_qty=0;
		$.each($('#prodfinishdlvaoprollselectedTbl').datagrid('getRows'), function (idx, val) {
			if(val.batch_qty){
				total_qty+=val.batch_qty*1;
			}
			if(val.qc_pass_qty){
				total_qc_qty+=val.qc_pass_qty*1;
			}
			if(val.reject_qty){
				total_reject_qty+=val.reject_qty*1;
			}
		});

		data.total_qty=total_qty;
		data.total_qc_qty=total_qc_qty;
		data.total_reject_qty=total_reject_qty;
		return data;
	}

	reloadFooter(data)
	{
		$('#prodfinishdlvaoprollselectedTbl').datagrid('reloadFooter', [
		{ 
			batch_qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			qc_pass_qty: data.total_qc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			reject_qty: data.total_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		}
	]);
	}
}
window.MsProdFinishDlvAopRoll=new MsProdFinishDlvAopRollController(new MsProdFinishDlvAopRollModel());
MsProdFinishDlvAopRoll.showGrid([]);
MsProdFinishDlvAopRoll.showGridRollSelection([]);
MsProdFinishDlvAopRoll.showGridRollSelected([]);
 
