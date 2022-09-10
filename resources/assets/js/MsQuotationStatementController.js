//require('./jquery.easyui.min.js');
let MsQuotationStatementModel = require('./MsQuotationStatementModel');
require('./datagrid-filter.js');

class MsQuotationStatementController {
	constructor(MsQuotationStatementModel)
	{
		this.MsQuotationStatementModel = MsQuotationStatementModel;
		this.formId='quotationstatementFrm';
		this.dataTable='#quotationstatementTbl';
		this.route=msApp.baseUrl()+"/quotationstatement/getdata";
	}

	get(){
		let params={};
		//params.company_id = $('#quotationstatementFrm  [name=company_id]').val();
		params.buyer_id = $('#quotationstatementFrm  [name=buyer_id]').val();
		params.team_id = $('#quotationstatementFrm  [name=team_id]').val();
		params.teammember_id = $('#quotationstatementFrm  [name=teammember_id]').val();
		params.style_ref = $('#quotationstatementFrm  [name=style_ref]').val();
		//params.job_no = $('#quotationstatementFrm  [name=job_no]').val();
		params.date_from = $('#quotationstatementFrm  [name=date_from]').val();
		params.date_to = $('#quotationstatementFrm  [name=date_to]').val();
		params.confirm_from = $('#quotationstatementFrm  [name=confirm_from]').val();
		params.confirm_to = $('#quotationstatementFrm  [name=confirm_to]').val();
		params.costing_from = $('#quotationstatementFrm  [name=costing_from]').val();
		params.costing_to = $('#quotationstatementFrm  [name=costing_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#quotationstatementTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			//queryParams:data,
			showFooter:true,
			fit:true,
			//url:this.route,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['offer_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 offer_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	
	imageWindow(flie_src){
		var output = document.getElementById('quotationstatementImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+flie_src;
    	            output.src =  fp;
			$('#quotationstatementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsQuotationStatement.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsQuotationStatement.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	
	quotedprice(value,row,index)
	{
		
		
		if (row.cost_per_pcs*1 > value*1){
				return 'color:red;';
			}
	}

	styleformat(value,row,index)
	{
		
		
		
		if (row.status == 'Confirmed'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Refused'){
				return 'background-color:#E66775;';
		}
		if (row.status == 'Cancel'){
				return 'background-color:#E66775;';
		}
	}

	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}

	fileMktCostWindow(style_id){		
		let data= axios.get(msApp.baseUrl()+"/quotationstatement/getmktcostfilesrc?style_id="+style_id);
		data.then(function (response) {
			$('#mktcostfilesrcTbl').datagrid('loadData', response.data);
			$('#mktcostfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});		
	}
	showGridMktCostFileSrc(data)
	{
		$('#mktcostfilesrcTbl').datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
	
		});
	}
	formatMktCostFileSrc(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsQuotationStatement.fileMktCostWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatShowMktCostFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	showGridMktCostQuotePrice(data)
	{
		$('#mktcostquotepriceTbl').datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
	
		}).datagrid('loadData', data);
	}

	formatMktCostQuotePrice(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsQuotationStatement.MktCostQuotePriceWindow('+row.id+')">'+value+'</a>';	
	}

	MktCostQuotePriceWindow(mkt_cost_id){
	//$('#mktcostquotepricewindow').window('open');		
		let data= axios.get(msApp.baseUrl()+"/quotationstatement/getmktcostquoteprice?mkt_cost_id="+mkt_cost_id);
		data.then(function (response) {
			$('#mktcostquotepriceTbl').datagrid('loadData', response.data);
			$('#mktcostquotepricewindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});		
	}


}
window.MsQuotationStatement=new MsQuotationStatementController(new MsQuotationStatementModel());
MsQuotationStatement.showGrid([]);
MsQuotationStatement.showGridMktCostFileSrc({rows :{}});
MsQuotationStatement.showGridMktCostQuotePrice([]);