let MsLocalExpPiReportModel = require('./MsLocalExpPiReportModel');
require('./../../datagrid-filter.js');

class MsLocalExpPiReportController {
	constructor(MsLocalExpPiReportModel)
	{
		this.MsLocalExpPiReportModel = MsLocalExpPiReportModel;
		this.formId='localexppireportFrm';
		this.dataTable='#localexppireportTbl';
		this.route=msApp.baseUrl()+"/localexppireport";
	}
	getParams()
	{
	    let params={};
	    params.date_from = $('#localexppireportFrm  [name=date_from]').val();
		params.date_to = $('#localexppireportFrm  [name=date_to]').val();
		params.company_id = $('#localexppireportFrm  [name=company_id]').val();
		params.buyer_id = $('#localexppireportFrm  [name=buyer_id]').val();
        params.production_area_id = $('#localexppireportFrm  [name=production_area_id]').val();
        
		return 	params;
	}
	
	get(){
        let params=this.getParams();
        if(!params.production_area_id){
            alert('Select A Production Area');
            return;
        }
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#localexppireportTbl').datagrid('loadData', response.data);
				
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				var tGrossAmount=0;
				var tLcValue=0;

				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['total_qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['total_value'].replace(/,/g,'')*1;
					tGrossAmount+=data.rows[i]['net_value'].replace(/,/g,'')*1;
					tLcValue+=data.rows[i]['lc_value'].replace(/,/g,'')*1;
				}
					$(this).datagrid('reloadFooter', [
				{
					total_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_value: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_value: tGrossAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_value: tLcValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
    
    exportItemDetail(local_exp_pi_id){
        let params=this.getParams();
        params.local_exp_pi_id=local_exp_pi_id;
        let d= axios.get(this.route+'/getlocalexportitem',{params})
		.then(function (response) {
            $('#exppiItemWindow').window('open');
			$('#piItemReportTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    showGridExpPiItem(data){
		var exp = $('#piItemReportTbl');
		exp.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
            var tQty=0;
            var tRate=0;
            var tAmount=0;
            var tNetAmount=0;
            for(var i=0; i<data.rows.length; i++){
                tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
                //tRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
                tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
                tNetAmount+=data.rows[i]['net_amount'].replace(/,/g,'')*1;
            }
            tRate=tAmount/tQty;
                $(this).datagrid('reloadFooter', [{
                qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                net_amount: tNetAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
            }]
            );
        }

		});
		exp.datagrid('enableFilter').datagrid('loadData', data);
	}

    formatitemdtl(value,row)
    {
        if(row.total_value){
            return '<a href="javascript:void(0)" onClick="MsLocalExpPiReport.exportItemDetail('+row.local_exp_pi_id+')">'+row.total_value+'</a>';
        }
        return 0;
	}
	
	exportPiDetail(local_exp_pi_id){
		window.open(msApp.baseUrl()+"/localexppi/getlocalpireport?id="+local_exp_pi_id);
	}

    formatpipdf(value,row)
    {
        if(row.pi_no){
            return '<a href="javascript:void(0)" onClick="MsLocalExpPiReport.exportPiDetail('+row.local_exp_pi_id+')">'+row.pi_no+'</a>';
        }
        return 0;
    }
}
window.MsLocalExpPiReport=new MsLocalExpPiReportController(new MsLocalExpPiReportModel());
MsLocalExpPiReport.showGrid([]);
MsLocalExpPiReport.showGridExpPiItem([]);