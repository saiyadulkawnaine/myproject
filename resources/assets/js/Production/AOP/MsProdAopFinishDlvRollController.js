let MsProdAopFinishDlvRollModel = require('./MsProdAopFinishDlvRollModel');
require('./../../datagrid-filter.js');
class MsProdAopFinishDlvRollController {
	constructor(MsProdAopFinishDlvRollModel)
	{
		this.MsProdAopFinishDlvRollModel = MsProdAopFinishDlvRollModel;
		this.formId='prodaopfinishdlvrollFrm';
		this.dataTable='#prodaopfinishdlvrollTbl';
		this.route=msApp.baseUrl()+"/prodaopfinishdlvroll"
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
		let prod_finish_dlv_id = $('#prodaopfinishdlvFrm  [name=id]').val();
		let formObj={};
		let rolls=MsProdAopFinishDlvRoll.getSelections();
		formObj.prod_finish_dlv_id=prod_finish_dlv_id;
		formObj.roll_id=rolls;
		this.MsProdAopFinishDlvRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopFinishDlvRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopFinishDlvRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_finish_dlv_id = $('#prodaopfinishdlvFrm  [name=id]').val();
		MsProdAopFinishDlvRoll.get(prod_finish_dlv_id)
		msApp.resetForm('prodaopfinishdlvrollTbl');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdAopFinishDlvRollModel.get(index,row);
		carton.then(function(response){
			//$('#prodaopfinishdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
	}
	get(prod_finish_dlv_id){
		let params={};
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		let data= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodaopfinishdlvrollTbl').datagrid('loadData', response.data).datagrid('enableFilter');
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
			showFooter: true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function (data) { 
				var rcv_qty = 0;
				var qc_pass_qty = 0;
				for (var i = 0; i < data.rows.length; i++){
					rcv_qty += data.rows[i]['rcv_qty'].replace(/,/g, '') * 1;
					qc_pass_qty += data.rows[i]['qc_pass_qty'].replace(/,/g, '') * 1;
				} $('#prodaopfinishdlvrollTbl').datagrid('reloadFooter', [{
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					qc_pass_qty: qc_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopFinishDlvRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getRoll(){
		let prod_finish_dlv_id=$('#prodaopfinishdlvFrm [name=id]').val();
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
			$('#prodaopfinishdlvrollselectionTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	import(){
		$('#prodaopfinishdlvrollWindow').window('open');
		//MsProdAopFinishDlvRoll.getRoll();

	}

	showGridRollSelection(data){
		let self=this;
		var dg=$('#prodaopfinishdlvrollselectionTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter: true,
			onClickRow: function(index,row){
				$('#prodaopfinishdlvrollselectedTbl').datagrid('appendRow',row);
				let total = MsProdAopFinishDlvRoll.getsum();
				MsProdAopFinishDlvRoll.reloadFooter(total);
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
				$('#prodaopfinishdlvrollselectionTbl').datagrid('reloadFooter', [
					{ 
						rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
		var dg=$('#prodaopfinishdlvrollselectedTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			showFooter: true,
			onClickRow: function(index,row){
				$('#prodaopfinishdlvrollselectedTbl').datagrid('deleteRow', index);
				let total = MsProdAopFinishDlvRoll.getsum();
				MsProdAopFinishDlvRoll.reloadFooter(total);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		var roll = [];
		let i=1;
		$.each($('#prodaopfinishdlvrollselectedTbl').datagrid('getRows'), function (idx, val) {
		roll.push(val.id);
		});
		MsProdAopFinishDlvRoll.showGridRollSelected([]);
		let rolls= roll.join(',');

        
		/*let rolls= roll.join(',');
		$('#prodaopfinishqcFrm [name=other_roll_no]').val(rolls);
        $('#prodaopfinishqcFrm [name=other_prod_finish_item_roll_id]').val(rolls);*/
        $('#prodaopfinishdlvrollWindow').window('close');
        return rolls;
	}

	getsum() {
		let data = {};
		let total_qty = 0;
		let total_qc_qty = 0;
		let total_reject_qty = 0;
		$.each($
			('#prodaopfinishdlvrollselectedTbl').datagrid('getRows'),
			function (idx, val) {
				if (val.rcv_qty) {
					total_qty += val.rcv_qty * 1;
				}
				if (val.qc_pass_qty) {
					total_qc_qty += val.qc_pass_qty * 1;
				}
				if (val.reject_qty) {
					total_reject_qty += val.reject_qty * 1;
				}
			}
		);
		data.total_qty = total_qty;
		data.total_qc_qty = total_qc_qty;
		data.total_reject_qty = total_reject_qty;
		return data;
	}

	reloadFooter(data) {
		$('#prodaopfinishdlvrollselectedTbl').datagrid('reloadFooter', [
			{
				rcv_qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				qc_pass_qty: data.total_qc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				reject_qty: data.total_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			}
		]);
	}
}
window.MsProdAopFinishDlvRoll=new MsProdAopFinishDlvRollController(new MsProdAopFinishDlvRollModel());
MsProdAopFinishDlvRoll.showGrid([]);
MsProdAopFinishDlvRoll.showGridRollSelection([]);
MsProdAopFinishDlvRoll.showGridRollSelected([]);
 
