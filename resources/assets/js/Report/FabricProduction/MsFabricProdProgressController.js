let MsFabricProdProgressModel = require('./MsFabricProdProgressModel');
require('./../../datagrid-filter.js');

class MsFabricProdProgressController {
	constructor(MsFabricProdProgressModel)
	{
		this.MsFabricProdProgressModel = MsFabricProdProgressModel;
		this.formId='fabricprodprogressFrm';
		this.dataTable='#fabricprodprogressTbl';
		this.route=msApp.baseUrl()+"/fabricprodprogress"
	}
	getParams(){
		let params={};
		params.company_id = $('#fabricprodprogressFrm  [name=company_id]').val();
		params.produced_company_id = $('#fabricprodprogressFrm  [name=produced_company_id]').val();
		params.buyer_id = $('#fabricprodprogressFrm  [name=buyer_id]').val();
		params.style_ref = $('#fabricprodprogressFrm  [name=style_ref]').val();
		params.style_id = $('#fabricprodprogressFrm  [name=style_id]').val();
		params.factory_merchant_id = $('#fabricprodprogressFrm  [name=factory_merchant_id]').val();
		params.date_from = $('#fabricprodprogressFrm  [name=date_from]').val();
		params.date_to = $('#fabricprodprogressFrm  [name=date_to]').val();
		params.order_status = $('#fabricprodprogressFrm  [name=order_status]').val();
		return params;
	}
	
	get()
	{
		//$('#fabricprogressTab').tabs('select',0);
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#fabricprodprogressTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var grey_fab=0;
				var yisu_qty=0;
				var fin_fab=0;
				var kwo_qty=0;
				var dwo_qty=0;
				var yisu_bal=0;
				var prod_knit_qty=0;
				var knit_qty=0;
				var knit_wip=0;
				var knit_bal=0;
				var knit_dlv_to_st_qty=0;
				var knit_rcv_by_st_qty=0;
				var rcv_by_batch_qty=0;
				var rcv_by_batch_bal=0;
				var batch_qty=0;
				var batch_wip=0;
				var batch_bal=0;
				var load_qty=0;
				var load_wip=0;
				var load_bal=0;
				var dyeing_qty=0;
				var dyeing_wip=0;
				var dyeing_bal=0;
				var aop_fab=0;
				var finish_qty=0;
				var finish_wip=0;
				var finish_bal=0;
				var finish_dlv_to_store_qty=0;
				var finish_rcv_to_store_qty=0;
				var finish_isu_to_cut_qty=0;
				var finish_isu_to_cut_wip=0;
				var finish_isu_to_cut_bal=0;
				

				for(var i=0; i<data.rows.length; i++){
                    grey_fab+=data.rows[i]['grey_fab'].replace(/,/g,'')*1;
                    yisu_qty+=data.rows[i]['yisu_qty'].replace(/,/g,'')*1;
                    fin_fab+=data.rows[i]['fin_fab'].replace(/,/g,'')*1;
                    kwo_qty+=data.rows[i]['kwo_qty'].replace(/,/g,'')*1;
                    dwo_qty+=data.rows[i]['dwo_qty'].replace(/,/g,'')*1;
                    yisu_bal+=data.rows[i]['yisu_bal'].replace(/,/g,'')*1;
                    knit_qty+=data.rows[i]['knit_qty'].replace(/,/g,'')*1;
                    prod_knit_qty+=data.rows[i]['prod_knit_qty'].replace(/,/g,'')*1;
                    knit_wip+=data.rows[i]['knit_wip'].replace(/,/g,'')*1;
                    knit_bal+=data.rows[i]['knit_bal'].replace(/,/g,'')*1;
                    knit_dlv_to_st_qty+=data.rows[i]['knit_dlv_to_st_qty'].replace(/,/g,'')*1;
                    knit_rcv_by_st_qty+=data.rows[i]['knit_rcv_by_st_qty'].replace(/,/g,'')*1;
                    rcv_by_batch_qty+=data.rows[i]['rcv_by_batch_qty'].replace(/,/g,'')*1;
                    rcv_by_batch_bal+=data.rows[i]['rcv_by_batch_bal'].replace(/,/g,'')*1;
                    batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
                    batch_wip+=data.rows[i]['batch_wip'].replace(/,/g,'')*1;
                    batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
                    load_bal+=data.rows[i]['load_bal'].replace(/,/g,'')*1;
                    load_qty+=data.rows[i]['load_qty'].replace(/,/g,'')*1;
                    load_wip+=data.rows[i]['load_wip'].replace(/,/g,'')*1;
                    dyeing_qty+=data.rows[i]['dyeing_qty'].replace(/,/g,'')*1;
                    dyeing_wip+=data.rows[i]['dyeing_wip'].replace(/,/g,'')*1;
                    dyeing_bal+=data.rows[i]['dyeing_bal'].replace(/,/g,'')*1;
                    aop_fab+=data.rows[i]['aop_fab'].replace(/,/g,'')*1;
                    finish_qty+=data.rows[i]['finish_qty'].replace(/,/g,'')*1;
                    finish_wip+=data.rows[i]['finish_wip'].replace(/,/g,'')*1;
                    finish_bal+=data.rows[i]['finish_bal'].replace(/,/g,'')*1;
                    
                    finish_dlv_to_store_qty+=data.rows[i]['finish_dlv_to_store_qty'].replace(/,/g,'')*1;
                    finish_rcv_to_store_qty+=data.rows[i]['finish_rcv_to_store_qty'].replace(/,/g,'')*1;
                    finish_isu_to_cut_qty+=data.rows[i]['finish_isu_to_cut_qty'].replace(/,/g,'')*1;
                    finish_isu_to_cut_wip+=data.rows[i]['finish_isu_to_cut_wip'].replace(/,/g,'')*1;
                    finish_isu_to_cut_bal+=data.rows[i]['finish_isu_to_cut_bal'].replace(/,/g,'')*1;

				}
				$(this).datagrid('reloadFooter', [
				{ 
					grey_fab: grey_fab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yisu_qty: yisu_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_fab:fin_fab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					kwo_qty: kwo_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dwo_qty: dwo_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yisu_bal: yisu_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_qty: knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_knit_qty: prod_knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_wip: knit_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_bal: knit_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_dlv_to_st_qty: knit_dlv_to_st_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_rcv_by_st_qty: knit_rcv_by_st_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_by_batch_qty: rcv_by_batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_by_batch_bal: rcv_by_batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_wip: batch_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					load_bal: load_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					load_qty: load_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					load_wip: load_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_qty: dyeing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_wip: dyeing_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_bal: dyeing_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_fab: aop_fab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_qty: finish_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_wip: finish_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_bal: finish_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_dlv_to_store_qty: finish_dlv_to_store_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_rcv_to_store_qty: finish_rcv_to_store_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_isu_to_cut_qty: finish_isu_to_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_isu_to_cut_wip: finish_isu_to_cut_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_isu_to_cut_bal: finish_isu_to_cut_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
				
				}
				]);
			}
		});

		var filter=[
			{
				field:'kwo_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'dwo_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'grey_fab',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'yisu_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'yisu_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'prod_knit_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'knit_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'knit_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'knit_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'knit_dlv_to_st_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'knit_rcv_by_st_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'rcv_by_batch_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'rcv_by_batch_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'batch_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'batch_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'batch_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'load_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'load_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'load_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'dyeing_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'dyeing_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'dyeing_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'aop_fab',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'fin_fab',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			
			{
				field:'finish_dlv_to_store_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_rcv_to_store_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_isu_to_cut_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_isu_to_cut_wip',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field:'finish_isu_to_cut_bal',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		]
		dg.datagrid('enableFilter',filter).datagrid('loadData', data);
	}

	
	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#fabricprodprogressTbl').datagrid('loadData', response.data);
			$('#fabricprodprogressTbl').datagrid('toExcel','Fabric Production Progress Report.xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
    }
    
    openOrdStyleWindow(){
		$('#ordstyleWindow').window('open');
	}
	getOrdStyleParams(){
		let params={};
		params.buyer_id = $('#ordstylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#ordstylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#ordstylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchOrdStyleGrid(){
		let params=this.getOrdStyleParams();
		let d= axios.get(this.route+'/ordpstyle',{params})
		.then(function(response){
			$('#ordstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrdStyleGrid(data){
		let self=this;
		$('#ordstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#fabricprodprogressFrm [name=style_ref]').val(row.style_ref);
				$('#fabricprodprogressFrm [name=style_id]').val(row.id);
				$('#ordstyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openTeammemberDlmWindow(){
		$('#teammemberDlmWindow').window('open');
	}
	getTdlmParams(){
		let params={};
		params.team_id = $('#teammemberdlmFrm  [name=team_id]').val();
		return params;
	}
	searchTeammemberDlmGrid(){
		let params=this.getTdlmParams();
		let dlm= axios.get(this.route+'/ordteammemberdlm',{params})
		.then(function(response){
			$('#teammemberdlmTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showTeammemberDlmGrid(data){
		let self=this;
		$('#teammemberdlmTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#fabricprodprogressFrm [name=factory_merchant_id]').val(row.factory_merchant_id);
				$('#fabricprodprogressFrm [name=team_member_name]').val(row.dlm_name);
				$('#teammemberDlmWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	getsummery(){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/fabricprodprogress/companybuyersummery",{params})
		.then(function (response) {
			$('#companybuyersummeryWindow').window('open');
			$('#companybuyersummeryContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	

}
window.MsFabricProdProgress=new MsFabricProdProgressController(new MsFabricProdProgressModel());
MsFabricProdProgress.showGrid([]);
MsFabricProdProgress.showTeammemberDlmGrid([]);
MsFabricProdProgress.showOrdStyleGrid([]);