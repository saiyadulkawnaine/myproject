require('./../../datagrid-filter.js');
let MsProdGmtSewingProductionModel = require('./MsProdGmtSewingProductionModel');

class MsProdGmtSewingProductionController {
	constructor(MsProdGmtSewingProductionModel)
	{
		this.MsProdGmtSewingProductionModel = MsProdGmtSewingProductionModel;
		this.formId='prodgmtsewingproductionFrm';
		this.dataTable='#prodgmtsewingproductionTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewingproduction";
	}

	get(){
		let params={};
		params.production_area_id = $('#prodgmtsewingproductionFrm  [name=production_area_id]').val();
		params.supplier_id = $('#prodgmtsewingproductionFrm  [name=supplier_id]').val();
		params.date_from = $('#prodgmtsewingproductionFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtsewingproductionFrm  [name=date_to]').val();
		params.buyer_id = $('#prodgmtsewingproductionFrm  [name=buyer_id]').val();
		params.company_id = $('#prodgmtsewingproductionFrm  [name=company_id]').val();
		params.produced_company_id = $('#prodgmtsewingproductionFrm  [name=produced_company_id]').val();
		params.style_ref = $('#prodgmtsewingproductionFrm  [name=style_ref]').val();
		params.sale_order_no = $('#prodgmtsewingproductionFrm  [name=sale_order_no]').val();
		if(!params.production_area_id){
			alert('Select A Production Area First');
			return;
		}
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodgmtsewingproductionTbl').datagrid('loadData', response.data);
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
				var tQty=0;
				var tcmAmount=0;
				var tItemRatio=0;
				var tCmRate=0;

				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tcmAmount+=data.rows[i]['cm_amount'].replace(/,/g,'')*1;
					tItemRatio+=data.rows[i]['item_ratio'].replace(/,/g,'')*1;
				}
				if(tQty){
					tCmRate=(tcmAmount/tQty)*12*tItemRatio;
				}
				
				$('#prodgmtsewingproductionTbl').datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						cm_amount: tcmAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						cm_rate: tCmRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
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
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsProdGmtSewingProduction.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	buyerWindow(buyer_id){
		let data= axios.get(msApp.baseUrl()+"/prodgmtsewingproduction/getbuyer?buyer_id="+buyer_id);
		data.then(function (response) {
			$('#prodbuyerTbl').datagrid('loadData', response.data);
			$('#prodbuyerwindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridBuyer(data)
	{
		var pb = $('#prodbuyerTbl');
		pb.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		pb.datagrid('loadData', data);
	}

	formatbuyer(value,row){
		if(row.buyer_id){
			return '<a href="javascript:void(0)" onClick="MsProdGmtSewingProduction.buyerWindow('+row.buyer_id+')">'+row.buyer_name+'</a>';
		}else{
			return;
		}
		
	}

	serviceproviderWindow(supplier_id){
		let data= axios.get(msApp.baseUrl()+"/prodgmtsewingproduction/getserviceprovider?supplier_id="+supplier_id);
		data.then(function (response) {
			$('#serviceproviderTbl').datagrid('loadData', response.data);
			$('#serviceproviderwindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridServiceProvider(data)
	{
		var sp = $('#serviceproviderTbl');
		sp.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		sp.datagrid('loadData', data);
	}

	formatserviceprovider(value,row){
		if(row.supplier_id){
			return '<a href="javascript:void(0)" onClick="MsProdGmtSewingProduction.serviceproviderWindow('+row.supplier_id+')">'+row.supplier_name+'</a>';
		}
		else{
			return '';
		}
		
	}

	prodGmtDlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/prodgmtsewingproduction/prodgmtdlmerchant?user_id="+user_id);
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
		if(row.user_id){
			return '<a href="javascript:void(0)" onClick="MsProdGmtSewingProduction.prodGmtDlmerchantWindow('+row.user_id+')">'+row.dl_marchent+'</a>';
		}
		
	}
	prodgmtfileWindow(style_id){

		let data= axios.get(msApp.baseUrl()+"/prodgmtsewingproduction/getprodgmtfile?style_id="+style_id);
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
		var sf = $('#prodgmtfilesrcTbl');
		sf.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		sf.datagrid('loadData', data);
	}	
	formatprodgmtfile(value,row)
	{
		/* if(row.file_name){
			return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_name + '">'+row.style_ref+'</a>';
		}else{ return row.style_ref; } */
		return '<a href="javascript:void(0)" onClick="MsProdGmtSewingProduction.prodgmtfileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatProdGmtShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	openStyleWindow(){
		$('#styleWindow').window('open');
	}
	getStyleParams(){
		let params={};
		params.buyer_id = $('#stylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#stylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#stylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchStyleGrid(){
		let params=this.getStyleParams();
		let st= axios.get(this.route+'/getstyle',{params})
		.then(function(response){
			$('#stylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showStyleGrid(data){
		let self=this;
		$('#stylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewingproductionFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtsewingproductionFrm [name=style_id]').val(row.id);
				$('#styleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openOrderWindow(){
		$('#salesorderWindow').window('open');
	}
	getOrderParams(){
		let params={};
		params.sale_order_no = $('#salesordersearchFrm  [name=sale_order_no]').val();
		params.style_ref = $('#salesordersearchFrm  [name=style_ref]').val();
		params.job_no = $('#salesordersearchFrm  [name=job_no]').val();
		return params;
	}
	searchOrderGrid(){
		let params=this.getOrderParams();
		let sd= axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#ordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrderGrid(data){
		let self=this;
		$('#ordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewingproductionFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtsewingproductionFrm [name=sales_order_id]').val(row.id);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getpdf(){
		let production_area_id = $('#prodgmtsewingproductionFrm  [name=production_area_id]').val();
		let supplier_id = $('#prodgmtsewingproductionFrm  [name=supplier_id]').val();
		let date_from = $('#prodgmtsewingproductionFrm  [name=date_from]').val();
		let date_to = $('#prodgmtsewingproductionFrm  [name=date_to]').val();
		let buyer_id = $('#prodgmtsewingproductionFrm  [name=buyer_id]').val();
		let company_id = $('#prodgmtsewingproductionFrm  [name=company_id]').val();
		let produced_company_id = $('#prodgmtsewingproductionFrm  [name=produced_company_id]').val();
		let style_ref = $('#prodgmtsewingproductionFrm  [name=style_ref]').val();
		let sale_order_no = $('#prodgmtsewingproductionFrm  [name=sale_order_no]').val();
		if(!production_area_id){
			alert('Select A Production Area First');
			return;
		}
		if(!date_from && !date_to){
			alert('Select Date Range First');
			return;
		}

		window.open(this.route+"/report?production_area_id="+production_area_id+"&company_id="+company_id+"&supplier_id="+supplier_id+"&date_from="+date_from+"&date_to="+date_to+"&buyer_id="+buyer_id+"&produced_company_id="+produced_company_id+"&style_ref="+style_ref+"&sale_order_no="+sale_order_no);
	}
}
window.MsProdGmtSewingProduction=new MsProdGmtSewingProductionController(new MsProdGmtSewingProductionModel());
MsProdGmtSewingProduction.showGrid([]);
MsProdGmtSewingProduction.showGridProdGmtDlmct({rows :{}});
MsProdGmtSewingProduction.showGridProdGmtFileSrc({rows :{}});
MsProdGmtSewingProduction.showStyleGrid([]);
MsProdGmtSewingProduction.showOrderGrid([]);
MsProdGmtSewingProduction.showGridBuyer({rows :{}});
MsProdGmtSewingProduction.showGridProdGmtFileSrc({rows :{}});
MsProdGmtSewingProduction.showGridServiceProvider({rows :{}});
