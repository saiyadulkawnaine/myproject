require('./../../datagrid-filter.js');
let MsProdFinishQcBatchCostingModel = require('./MsProdFinishQcBatchCostingModel');

class MsProdFinishQcBatchCostingController {
	constructor(MsProdFinishQcBatchCostingModel)
	{
		this.MsProdFinishQcBatchCostingModel = MsProdFinishQcBatchCostingModel;
		this.formId='prodfinishqcbatchcostingFrm';
		this.dataTable='#prodfinishqcbatchcostingTbl';
		this.route=msApp.baseUrl()+"/prodfinishqcbatchcosting";
	}

	getParams(){
		let params={};
		params.date_from = $('#prodfinishqcbatchcostingFrm  [name=date_from]').val();
		params.date_to = $('#prodfinishqcbatchcostingFrm  [name=date_to]').val();
		params.company_id = $('#prodfinishqcbatchcostingFrm  [name=company_id]').val();
		params.batch_for = $('#prodfinishqcbatchcostingFrm  [name=batch_for]').val();
		params.batch_no = $('#prodfinishqcbatchcostingFrm  [name=batch_no]').val();
		return params;
	}

	get(){
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}


		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodfinishqcbatchcostingTbl').datagrid('loadData', response.data);
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
				var revenue=0;
				var revenue_per=0;
				var dyes_cost_amount=0;
				var chem_cost_amount=0;
				var overhead=0;
				var profit=0;
				var profit_per=0;
				var batch_qty=0;
				var utilize_per=0;
				var prod_capacity=0;
				var qc_pass_qty=0;
				var process_loss=0;
				var process_loss_per=0;

				for(var i=0; i<data.rows.length; i++){
					revenue+=data.rows[i]['revenue'].replace(/,/g,'')*1;	
					dyes_cost_amount+=data.rows[i]['dyes_cost_amount'].replace(/,/g,'')*1;	
					chem_cost_amount+=data.rows[i]['chem_cost_amount'].replace(/,/g,'')*1;	
					overhead+=data.rows[i]['overhead'].replace(/,/g,'')*1;	
					profit+=data.rows[i]['profit'].replace(/,/g,'')*1;	
					prod_capacity+=data.rows[i]['prod_capacity'].replace(/,/g,'')*1;	
					qc_pass_qty+=data.rows[i]['qc_pass_qty'].replace(/,/g,'')*1;	
					process_loss+=data.rows[i]['process_loss'].replace(/,/g,'')*1;	
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;	
				}
				if (revenue) {
					revenue_per=((dyes_cost_amount+chem_cost_amount)/revenue)*100;
				}
				if (revenue) {
					profit_per=(profit/revenue)*100;
				}
				if (prod_capacity) {
					utilize_per=(batch_qty/prod_capacity)*100;
				}
				if (batch_qty) {
					process_loss_per=(process_loss/batch_qty)*100;
				}

				$('#prodfinishqcbatchcostingTbl').datagrid('reloadFooter', [
					{ 
						revenue: revenue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						revenue_per: revenue_per.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						dyes_cost_amount: dyes_cost_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						chem_cost_amount: chem_cost_amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						overhead: overhead.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						profit: profit.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						profit_per: profit_per.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						utilize_per: utilize_per.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_capacity: prod_capacity.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						qc_pass_qty: qc_pass_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						process_loss: process_loss.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						process_loss_per: process_loss_per.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						batch_qty: batch_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

    openqcbatchcostingbatchWindow(){
		$('#qcbatchcostingbatchsearchwindow').window('open');
		$('#prodbatchsearchTbl').datagrid('loadData',[]);
	}

	batchSearchGrid(data){
		let self=this;
		$('#prodbatchsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#prodfinishqcbatchcostingFrm [name=prod_batch_id]').val(row.id);
				$('#prodfinishqcbatchcostingFrm [name=batch_no]').val(row.batch_no);
				$('#qcbatchcostingbatchsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.batch_date_from=$('#prodbatchsearch  [name=batch_date_from]').val();
		params.batch_date_to=$('#prodbatchsearch  [name=batch_date_to]').val();
		let data= axios.get(this.route+"/searchbatch",{params});
		data.then(function (response) {
			$('#prodbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	sodyeingdtlWindow(prod_batch_id){
		let params=this.getParams();
		params.prod_batch_id=prod_batch_id;
		let data= axios.get(msApp.baseUrl()+"/prodfinishqcbatchcosting/getsodyeingdtl",{params});
		let ic=data.then(function (response) {
			$('#sodyeingdtlWindow').window('open');
			$('#sodyeingdtlTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		//return ic;
	}

	showGridSoDyeingDtl(data){
		let self=this
		var dq = $("#sodyeingdtlTbl");
		dq.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var rate=0;
				var amount=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;	
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}	
				if (qty) {
					rate=amount/qty;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rate: rate.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
	
					}
				]);
			}
		});
		dq.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatsodyeingdtl(value,row){
		if(row.prod_batch_id){
			return '<a href="javascript:void(0)" onClick="MsProdFinishQcBatchCosting.sodyeingdtlWindow('+row.prod_batch_id+')">'+row.revenue+'</a>';
		}
		return;
	}

	pdf(prod_batch_id){
		window.open(msApp.baseUrl()+"/prodbatch/report?id="+prod_batch_id);
	}

	formatpdf(value,row){
		if(row.prod_batch_id){
			return '<a href="javascript:void(0)" onClick="MsProdFinishQcBatchCosting.pdf('+row.prod_batch_id+')">'+row.batch_no+'</a>';
		}
		return;
	}

	costsheetpdf(prod_batch_id){
		window.open(msApp.baseUrl()+"/prodfinishqcbatchcosting/getcostsheet?prod_batch_id="+prod_batch_id);
	}

	formatcostsheetpdf(value,row){
		if(row.prod_batch_id){
			return '<a href="javascript:void(0)" onClick="MsProdFinishQcBatchCosting.costsheetpdf('+row.prod_batch_id+')">'+value+'</a>';
		}
		return;
	}

	formatAdditionalHr(value,row,index)
	{
		if (row.hour_used*1 > row.tgt_hour*1){
				return 'color:red;';
		}
	}

}
window.MsProdFinishQcBatchCosting=new MsProdFinishQcBatchCostingController(new MsProdFinishQcBatchCostingModel());
MsProdFinishQcBatchCosting.showGrid([]);
MsProdFinishQcBatchCosting.batchSearchGrid([]);
MsProdFinishQcBatchCosting.showGridSoDyeingDtl([]);