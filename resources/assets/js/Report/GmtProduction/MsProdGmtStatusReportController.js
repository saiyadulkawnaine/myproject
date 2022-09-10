require('./../../datagrid-filter.js');
let MsProdGmtStatusReportModel = require('./MsProdGmtStatusReportModel');

class MsProdGmtStatusReportController {
	constructor(MsProdGmtStatusReportModel)
	{
		this.MsProdGmtStatusReportModel = MsProdGmtStatusReportModel;
		this.formId='prodgmtstatusreportFrm';
		this.dataTable='#prodgmtstatusreportTbl';
		this.route=msApp.baseUrl()+"/prodgmtstatusreport";
	}

	get(){
		let params={};
		params.style_id = $('#prodgmtstatusreportFrm  [name=style_id]').val();
		//params.style_ref = $('#prodgmtstatusreportFrm  [name=style_ref]').val();

		if(!params.style_id){
			alert('Select Style Reference First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
            $('#prodgmtstatusreportcontainer').html(response.data);
			//$('#prodgmtstatusreportTbl').datagrid('loadData', response.data);
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
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

    openGmtStyleWindow(){
		$('#gmtstylesearchWindow').window('open');
	}

	getGmtStyleParams(){
		let params={};
		params.buyer_id = $('#gmtstylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#gmtstylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#gmtstylesearchFrm  [name=style_description]').val();
		return params;
	}

	searchGmtStyle(){
		let params=this.getGmtStyleParams();
		let d= axios.get(this.route+'/gmtstylesearch',{params})
		.then(function(response){
			$('#gmtstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showGmtStyleGrid(data){
		let self=this;
		$('#gmtstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtstatusreportFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtstatusreportFrm [name=style_id]').val(row.id);
				$('#prodgmtstatusreportFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtstatusreportFrm [name=team_member_name]').val(row.team_member_name);
				$('#gmtstylesearchWindow').window('close');
				$('#gmtstylesearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

    pdf	(){
		let style_id = $('#prodgmtstatusreportFrm  [name=style_id]').val();
		//params.style_ref = $('#prodgmtstatusreportFrm  [name=style_ref]').val();

		if(!style_id){
			alert('Select Style Reference First');
			return;
		}
		window.open(msApp.baseUrl()+"/prodgmtstatusreport/getpdf?style_id="+style_id);
	}

	detailWindow(item_account_id,color_id){
		let params={};
		params.style_id = $('#prodgmtstatusreportFrm  [name=style_id]').val();
		//params.sales_order_id = sales_order_id;
		params.item_account_id = item_account_id;
        params.color_id=color_id;
		$('#sewingWindow').window('open');
		let d= axios.get(this.route+'/getsewingdetails',{params})
		.then(function (response) {
			$('#containerDocWindow').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsProdGmtStatusReport=new MsProdGmtStatusReportController(new MsProdGmtStatusReportModel());
MsProdGmtStatusReport.showGrid([]);
MsProdGmtStatusReport.showGmtStyleGrid([]);