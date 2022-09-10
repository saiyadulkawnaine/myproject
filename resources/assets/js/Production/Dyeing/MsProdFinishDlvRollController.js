let MsProdFinishDlvRollModel = require('./MsProdFinishDlvRollModel');
require('./../../datagrid-filter.js');
class MsProdFinishDlvRollController {
	constructor(MsProdFinishDlvRollModel)
	{
		this.MsProdFinishDlvRollModel = MsProdFinishDlvRollModel;
		this.formId='prodfinishdlvrollFrm';
		this.dataTable='#prodfinishdlvrollTbl';
		this.route=msApp.baseUrl()+"/prodfinishdlvroll"
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
		let prod_finish_dlv_id = $('#prodfinishdlvFrm  [name=id]').val();
		let formObj={};
		let rolls=MsProdFinishDlvRoll.getSelections();
		formObj.prod_finish_dlv_id=prod_finish_dlv_id;
		formObj.roll_id=rolls;
		this.MsProdFinishDlvRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishDlvRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishDlvRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_finish_dlv_id = $('#prodfinishdlvFrm  [name=id]').val();
		MsProdFinishDlvRoll.get(prod_finish_dlv_id)
		msApp.resetForm('prodfinishdlvrollTbl');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdFinishDlvRollModel.get(index,row);
		carton.then(function(response){
			//$('#prodfinishdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
	}
	get(prod_finish_dlv_id){
		let params={};
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		let data= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodfinishdlvrollTbl').datagrid('loadData', response.data).datagrid('enableFilter');
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
				$('#prodfinishdlvrollTbl').datagrid('reloadFooter', [
					{ 
						roll_weight: roll_weight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdFinishDlvRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getRoll(){
		let prod_finish_dlv_id=$('#prodfinishdlvFrm [name=id]').val();
		//let supplier_id=$('#supplier_id').val();
		let from_qc_date=$('#from_qc_date').val();
		let to_qc_date=$('#to_qc_date').val();
		let batch_no=$('#batch_no').val();
		let batch_date_from=$('#batch_date_from').val();
		let batch_date_to=$('#batch_date_to').val();
		let params={};
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		//params.supplier_id=supplier_id;
		params.from_qc_date=from_qc_date;
		params.to_qc_date=to_qc_date;
		params.batch_no=batch_no;
		params.batch_date_from=batch_date_from;
		params.batch_date_to=batch_date_to;

		if(!params.prod_finish_dlv_id){
			alert('Select Roll First');
			return;
		}

		let data= axios.get(this.route+"/importroll",{params})
		.then(function (response) {
			$('#prodfinishdlvrollselectionTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	import(){
		$('#prodfinishdlvrollWindow').window('open');
		//MsProdFinishDlvRoll.getRoll();

	}

	showGridRollSelection(data){
		let self=this;
		var dg=$('#prodfinishdlvrollselectionTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
				$('#prodfinishdlvrollselectedTbl').datagrid('appendRow',row);
				let total=MsProdFinishDlvRoll.getsum();
				MsProdFinishDlvRoll.reloadFooter(total);
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
				$('#prodfinishdlvrollselectionTbl').datagrid('reloadFooter', [
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
		var dg=$('#prodfinishdlvrollselectedTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			onClickRow: function(index,row){
				$('#prodfinishdlvrollselectedTbl').datagrid('deleteRow', index);
				let total=MsProdFinishDlvRoll.getsum();
				MsProdFinishDlvRoll.reloadFooter(total);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		var roll = [];
		let i=1;
		$.each($('#prodfinishdlvrollselectedTbl').datagrid('getRows'), function (idx, val) {
		roll.push(val.id);
		});
		MsProdFinishDlvRoll.showGridRollSelected([]);
		let rolls= roll.join(',');

        
		/*let rolls= roll.join(',');
		$('#prodfinishqcFrm [name=other_roll_no]').val(rolls);
        $('#prodfinishqcFrm [name=other_prod_finish_item_roll_id]').val(rolls);*/
        $('#prodfinishdlvrollWindow').window('close');
        return rolls;
	}

	getsum(){
		let data={};
		let total_qty=0;
		let total_qc_qty=0;
		let total_reject_qty=0;
		$.each($('#prodfinishdlvrollselectedTbl').datagrid('getRows'), function (idx, val) {
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
		$('#prodfinishdlvrollselectedTbl').datagrid('reloadFooter', [
		{ 
			batch_qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			qc_pass_qty: data.total_qc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			reject_qty: data.total_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		}
	]);
	}
}
window.MsProdFinishDlvRoll=new MsProdFinishDlvRollController(new MsProdFinishDlvRollModel());
MsProdFinishDlvRoll.showGrid([]);
MsProdFinishDlvRoll.showGridRollSelection([]);
MsProdFinishDlvRoll.showGridRollSelected([]);
 
