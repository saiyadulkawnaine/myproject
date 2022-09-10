let MsProdKnitDlvRollModel = require('./MsProdKnitDlvRollModel');
require('./../../datagrid-filter.js');
class MsProdKnitDlvRollController {
	constructor(MsProdKnitDlvRollModel)
	{
		this.MsProdKnitDlvRollModel = MsProdKnitDlvRollModel;
		this.formId='prodknitdlvrollFrm';
		this.dataTable='#prodknitdlvrollTbl';
		this.route=msApp.baseUrl()+"/prodknitdlvroll"
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
		let prod_knit_dlv_id = $('#prodknitdlvFrm  [name=id]').val();
		let formObj={};
		let rolls=MsProdKnitDlvRoll.getSelections();
		formObj.prod_knit_dlv_id=prod_knit_dlv_id;
		formObj.roll_id=rolls;
		this.MsProdKnitDlvRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitDlvRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitDlvRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let prod_knit_dlv_id = $('#prodknitdlvFrm  [name=id]').val();
		MsProdKnitDlvRoll.get(prod_knit_dlv_id)
		msApp.resetForm('prodknitdlvrollTbl');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdKnitDlvRollModel.get(index,row);
		carton.then(function(response){
			//$('#prodknitdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
	}
	get(prod_knit_dlv_id){
		let params={};
		params.prod_knit_dlv_id=prod_knit_dlv_id;
		let data= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodknitdlvrollTbl').datagrid('loadData', response.data).datagrid('enableFilter');
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
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnitDlvRoll.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getRoll(){
		let prod_knit_dlv_id=$('#prodknitdlvFrm [name=id]').val();
		let supplier_id=$('#supplier_id').val();
		let from_qc_date=$('#from_qc_date').val();
		let to_qc_date=$('#to_qc_date').val();
		let prod_ref_no=$('#prod_ref_no').val();
		let pord_date_from=$('#pord_date_from').val();
		let prod_date_to=$('#prod_date_to').val();
		let params={};
		params.prod_knit_dlv_id=prod_knit_dlv_id;
		params.supplier_id=supplier_id;
		params.from_qc_date=from_qc_date;
		params.to_qc_date=to_qc_date;
		params.prod_ref_no=prod_ref_no;
		params.pord_date_from=pord_date_from;
		params.prod_date_to=prod_date_to;

		if(!params.prod_knit_dlv_id){
			alert('Select Roll First');
			return;
		}

		let data= axios.get(this.route+"/importroll",{params})
		.then(function (response) {
			$('#prodknitdlvrollselectionTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	import(){
		$('#prodknitdlvrollWindow').window('open');
		//MsProdKnitDlvRoll.getRoll();

	}

	showGridRollSelection(data){
		let self=this;
		var dg=$('#prodknitdlvrollselectionTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#prodknitdlvrollselectedTbl').datagrid('appendRow',row);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridRollSelected(data){
		let self=this;
		var dg=$('#prodknitdlvrollselectedTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#prodknitdlvrollselectedTbl').datagrid('deleteRow', index);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		var roll = [];
		let i=1;
		$.each($('#prodknitdlvrollselectedTbl').datagrid('getRows'), function (idx, val) {
		roll.push(val.id);
		});
		MsProdKnitDlvRoll.showGridRollSelected([]);
		let rolls= roll.join(',');

        
		/*let rolls= roll.join(',');
		$('#prodknitqcFrm [name=other_roll_no]').val(rolls);
        $('#prodknitqcFrm [name=other_prod_knit_item_roll_id]').val(rolls);*/
        $('#prodknitdlvrollWindow').window('close');
        return rolls;
	}
}
window.MsProdKnitDlvRoll=new MsProdKnitDlvRollController(new MsProdKnitDlvRollModel());
MsProdKnitDlvRoll.showGrid([]);
MsProdKnitDlvRoll.showGridRollSelection([]);
MsProdKnitDlvRoll.showGridRollSelected([]);
 
