require('./../../datagrid-filter.js');
let MsProdGmtDailyReportModel = require('./MsProdGmtDailyReportModel');

class MsProdGmtDailyReportController {
	constructor(MsProdGmtDailyReportModel)
	{
		this.MsProdGmtDailyReportModel = MsProdGmtDailyReportModel;
		this.formId='prodgmtdailyreportFrm';
		this.dataTable='#prodgmtdailyreportTbl';
		this.route=msApp.baseUrl()+"/prodgmtdailyreport";
	}

	get(){
		let params={};
		params.company_id = $('#prodgmtdailyreportFrm  [name=company_id]').val();
		params.buyer_id = $('#prodgmtdailyreportFrm  [name=buyer_id]').val();
		params.produced_company_id = $('#prodgmtdailyreportFrm  [name=produced_company_id]').val();
		params.production_area_id = $('#prodgmtdailyreportFrm  [name=production_area_id]').val();
		params.style_ref = $('#prodgmtdailyreportFrm  [name=style_ref]').val();
		params.sale_order_no = $('#prodgmtdailyreportFrm  [name=sale_order_no]').val();
		params.date_from = $('#prodgmtdailyreportFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtdailyreportFrm  [name=date_to]').val();
		params.prod_source_id = $('#prodgmtdailyreportFrm  [name=prod_source_id]').val();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}
		if(!params.production_area_id){
			alert('Select A Production Area ');
			return;
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodgmtdailyreportTbl').datagrid('loadData', response.data);
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
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCut_qty=0;
				var tPrint_qty=0;
				var tEmb_qty=0;
				var tSew_qty=0;
				var tIron_qty=0;
				var tPoly_qty=0;
				var tCarton_qty=0;
				var tCartonAmount=0;
				var tCmAmount=0;
				//var tRate=0;
				var tCmRate=0;
				var tMktCmAmount=0;
				var tMktCmRate=0;
				var tProdHour=0;
				
				for(var i=0; i<data.rows.length; i++){
					tCut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
					tPrint_qty+=data.rows[i]['print_qty'].replace(/,/g,'')*1;
					tEmb_qty+=data.rows[i]['emb_qty'].replace(/,/g,'')*1;
					tSew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					tIron_qty+=data.rows[i]['iron_qty'].replace(/,/g,'')*1;
					tPoly_qty+=data.rows[i]['poly_qty'].replace(/,/g,'')*1;
					tCarton_qty+=data.rows[i]['finishing_qty'].replace(/,/g,'')*1;
					tCartonAmount+=data.rows[i]['finishing_amount'].replace(/,/g,'')*1;
					tCmAmount+=data.rows[i]['cm_amount'].replace(/,/g,'')*1;
					tMktCmAmount+=data.rows[i]['mkt_cm_amount'].replace(/,/g,'')*1;
					tProdHour+=data.rows[i]['prod_hour'].replace(/,/g,'')*1;
				}
				//tCartonAmount=tCarton_qty*tRate;
				/*if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#prodgmtdailyreportTbl').datagrid('reloadFooter', [
					{ 
						cut_qty: tCut_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						print_qty: tPrint_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						emb_qty: tEmb_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						sew_qty: tSew_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						iron_qty: tIron_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						poly_qty: tPoly_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						finishing_qty: tCarton_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						finishing_amount: tCartonAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						cm_amount: tCmAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						cm_rate: tCmRate.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						mkt_cm_amount: tMktCmAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						mkt_cm_rate: tMktCmRate.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_hour: tProdHour.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
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
		var output = document.getElementById('dailyreportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dailyreportImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
		//alert ('Image not found');
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsProdGmtDailyReport.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	/* showGridDetail(data)
	{
		var dg = $('#prodgmtdailyreportdetailsTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['sew_qty']*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					sew_qty: tQty,
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	} */
	/* targetprice(value,row,index){
		if(value !== null){
			if(row.target_per_hour*1 > value*1){
				return 'background-color:#ff00004d';
			}
		}
    else{
			return 'background-color:#fff';
		}		
	} */
	prodGmtDlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/prodgmtdailyreport/prodgmtdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#prodgmtdealmctinfoTbl').datagrid('loadData', response.data);
			$('#prodgmtdlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridProdGmtDlmct(data)
	{
		var dg = $('#prodgmtdealmctinfoTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}
	formatprodgmtdlmerchant(value,row){
		return '<a href="javascript:void(0)" onClick="MsProdGmtDailyReport.prodGmtDlmerchantWindow('+row.user_id+')">'+row.dl_marchent+'</a>';
	}
	prodgmtfileWindow(style_id){

		let data= axios.get(msApp.baseUrl()+"/prodgmtdailyreport/getprodgmtfile?style_id="+style_id);
		data.then(function (response) {
			$('#prodgmtfilesrcTbl').datagrid('loadData', response.data);
			$('#prodgmtfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridProdGmtFileSrc(data)
	{
		var dg = $('#prodgmtfilesrcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}	
	formatprodgmtfile(value,row)
	{
		/* if(row.file_name){
			return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_name + '">'+row.style_ref+'</a>';
		}else{ return row.style_ref; } */
		return '<a href="javascript:void(0)" onClick="MsProdGmtDailyReport.prodgmtfileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatProdGmtShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}
}
window.MsProdGmtDailyReport=new MsProdGmtDailyReportController(new MsProdGmtDailyReportModel());
MsProdGmtDailyReport.showGrid([]);
MsProdGmtDailyReport.showGridProdGmtDlmct({rows :{}});
MsProdGmtDailyReport.showGridProdGmtFileSrc({rows :{}});