require('./../../datagrid-filter.js');
let MsProdGmtDailyExFactoryReportModel = require('./MsProdGmtDailyExFactoryReportModel');

class MsProdGmtDailyExFactoryReportController {
	constructor(MsProdGmtDailyExFactoryReportModel)
	{
		this.MsProdGmtDailyExFactoryReportModel = MsProdGmtDailyExFactoryReportModel;
		this.formId='prodgmtexfactorydailyreportFrm';
		this.dataTable='#prodgmtexfactorydailyreportTbl';
		this.route=msApp.baseUrl()+"/prodgmtexfactorydailyreport";
	}

	get(){
		let params={};
		params.company_id = $('#prodgmtexfactorydailyreportFrm  [name=company_id]').val();
		params.buyer_id = $('#prodgmtexfactorydailyreportFrm  [name=buyer_id]').val();
		params.produced_company_id = $('#prodgmtexfactorydailyreportFrm  [name=produced_company_id]').val();
		params.style_ref = $('#prodgmtexfactorydailyreportFrm  [name=style_ref]').val();
		params.sale_order_no = $('#prodgmtexfactorydailyreportFrm  [name=sale_order_no]').val();
		params.date_from = $('#prodgmtexfactorydailyreportFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtexfactorydailyreportFrm  [name=date_to]').val();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodgmtexfactorydailyreportTbl').datagrid('loadData', response.data);
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
				var tExfactoryQty=0;
				var tExfactoryAmount=0;
				var tDelayDays=0;
				for(var i=0; i<data.rows.length; i++){
					tExfactoryQty+=data.rows[i]['exfactory_qty'].replace(/,/g,'')*1;
					tExfactoryAmount+=data.rows[i]['exfactory_amount'].replace(/,/g,'')*1;
					tDelayDays+=data.rows[i]['delayDays'].replace(/,/g,'')*1;
				}	
				$('#prodgmtexfactorydailyreportTbl').datagrid('reloadFooter', [
					{ 
						exfactory_qty: tExfactoryQty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						exfactory_amount: tExfactoryAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						delayDays: tDelayDays.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});

		var filter=[
			{
				field:'delayDays',
				type:'textbox',
				//options:{precision:2},
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			
		];

		dg.datagrid('enableFilter',filter).datagrid('loadData', data);
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
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsProdGmtDailyExFactoryReport.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	/* showGridDetail(data)
	{
		var dg = $('#prodgmtexfactorydailyreportdetailsTbl');
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
	formatDelays(value,row){
		if ( row.delayDays*1 < 0 ){
			return 'background-color:#ff000082;';
		}
	}
	pdf(id){
		window.open(msApp.baseUrl()+"/prodgmtexfactory/exfactorypdf?id="+id);
	}
	formatPdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDailyExFactoryReport.pdf('+row.id+')">'+row.challan_no+'</a>';
	}
}
window.MsProdGmtDailyExFactoryReport=new MsProdGmtDailyExFactoryReportController(new MsProdGmtDailyExFactoryReportModel());
MsProdGmtDailyExFactoryReport.showGrid([]);