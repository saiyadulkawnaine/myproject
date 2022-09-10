let MsLocalExpLcProgressReportModel = require('./MsLocalExpLcProgressReportModel');
require('./../../datagrid-filter.js');

class MsLocalExpLcProgressReportController {
	constructor(MsLocalExpLcProgressReportModel)
	{
		this.MsLocalExpLcProgressReportModel = MsLocalExpLcProgressReportModel;
		this.formId='localexplcprogressreportFrm';
		this.dataTable='#localexplcprogressreportTbl';
		this.route=msApp.baseUrl()+"/localexplcprogressreport";
	}
	getParams()
	{
	    let params={};
	    params.date_from = $('#localexplcprogressreportFrm  [name=date_from]').val();
		params.date_to = $('#localexplcprogressreportFrm  [name=date_to]').val();
		params.beneficiary_id = $('#localexplcprogressreportFrm  [name=beneficiary_id]').val();
		params.buyer_id = $('#localexplcprogressreportFrm  [name=buyer_id]').val();
        params.production_area_id = $('#localexplcprogressreportFrm  [name=production_area_id]').val();
        params.local_lc_no = $('#localexplcprogressreportFrm  [name=local_lc_no]').val();
        params.available_doc_id = $('#localexplcprogressreportFrm  [name=available_doc_id]').val();
        params.status_id = $('#localexplcprogressreportFrm  [name=status_id]').val();
        params.maturity_date_from = $('#localexplcprogressreportFrm  [name=maturity_date_from]').val();
		params.maturity_date_to = $('#localexplcprogressreportFrm  [name=maturity_date_to]').val();
        
		return 	params;
	}
	
	get(){
        let params=this.getParams();
        if(!params.date_from && !params.date_to){
            alert('Select A Date Range');
            return;
        }
        // if(!params.production_area_id){
        //     alert('Select A Production Area');
        //     return;
        // }
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#localexplcprogressreportTbl').datagrid('loadData', response.data);
				
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
				var tLcValueValue=0;
				var tPurchaseAmount=0;
				var tRealizedAmount=0;
				var tTaggedLcQty=0;
				var tLocalInvoiceValue=0;

				for(var i=0; i<data.rows.length; i++){
					tLcValueValue+=data.rows[i]['tagged_lc_value'].replace(/,/g,'')*1;
					tPurchaseAmount+=data.rows[i]['purchase_amount'].replace(/,/g,'')*1;
					tRealizedAmount+=data.rows[i]['realized_amount'].replace(/,/g,'')*1;
					tTaggedLcQty+=data.rows[i]['tagged_lc_qty'].replace(/,/g,'')*1;
					tLocalInvoiceValue+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
				}
					$(this).datagrid('reloadFooter', [
				{
					tagged_lc_value: tLcValueValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_amount: tPurchaseAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					realized_amount: tRealizedAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					tagged_lc_qty: tTaggedLcQty.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_value: tLocalInvoiceValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
    
    expInvoiceDetail(local_exp_lc_id){
		let params=this.getParams();
		params.local_exp_lc_id=local_exp_lc_id;
        let d= axios.get(this.route+'/getlocalinvoice',{params})
		.then(function (response) {
            $('#localexpinvoiceWindow').window('open');
			$('#localexpinvoiceprogressTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    showGridExpInvoice(data){
		var exp = $('#localexpinvoiceprogressTbl');
		exp.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
            var tQty=0;
           // var tRate=0;
            var tAmount=0;
            for(var i=0; i<data.rows.length; i++){
                tQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
                //tRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
                tAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
            }
            tRate=tAmount/tQty;
                $(this).datagrid('reloadFooter', [{
				invoice_qty: tQty.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
               // rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			   	invoice_amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
            }]
            );
        }

		});
		exp.datagrid('enableFilter').datagrid('loadData', data);
	}

    formatInvoice(value,row)
    {
        if(row.invoice_value){
            return '<a href="javascript:void(0)" onClick="MsLocalExpLcProgressReport.expInvoiceDetail('+row.local_exp_lc_id+')">'+row.invoice_value+'</a>';
        }
        return 0;
	}
	
	   
    expTransection(local_exp_doc_sub_bank_id){
		let params=this.getParams();
		params.local_exp_doc_sub_bank_id=local_exp_doc_sub_bank_id;
        let d= axios.get(this.route+'/getlocaltransection',{params})
		.then(function (response) {
            $('#localtransectionWindow').window('open');
			$('#localexptransTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    showGridExpTrans(data){
			var exp = $('#localexptransTbl');
			exp.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		exp.datagrid('enableFilter').datagrid('loadData', data);
	}

    formatTrans(value,row)
    {
        if(row.purchase_amount){
            return '<a href="javascript:void(0)" onClick="MsLocalExpLcProgressReport.expTransection('+row.local_exp_doc_sub_bank_id+')">'+row.purchase_amount+'</a>';
        }
        return 0;
	}

	openlocalExpLceWindow(){
		$('#openlocallcwindow').window('open');
	}

	getLcParams(){
		let params = {};
		params.local_lc_no = $('#localexplcsearchFrm [name="local_lc_no"]').val();
		params.lc_date = $('#localexplcsearchFrm [name="lc_date"]').val();
		return params;
	}
	
	searchLocalExpLcGrid(){
		let params = this.getLcParams();
		let lc=axios.get(this.route+"/getlocallc",{params})
		.then(function(response){
			$('#localexplcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showlocalLcGrid(data){
		let self = this;
		$('#localexplcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#localexplcprogressreportFrm [name=local_exp_lc_id]').val(row.local_exp_lc_id);
				$('#localexplcprogressreportFrm [name=local_lc_no]').val(row.local_lc_no);
				$('#openlocallcwindow').window('close');
				$('#localexplcsearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsLocalExpLcProgressReport=new MsLocalExpLcProgressReportController(new MsLocalExpLcProgressReportModel());
MsLocalExpLcProgressReport.showGrid([]);
MsLocalExpLcProgressReport.showGridExpInvoice([]);
MsLocalExpLcProgressReport.showGridExpTrans([]);
MsLocalExpLcProgressReport.showlocalLcGrid([]);