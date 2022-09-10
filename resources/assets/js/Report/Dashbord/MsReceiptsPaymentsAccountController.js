let MsReceiptsPaymentsAccountModel = require('./MsReceiptsPaymentsAccountModel');
require('./../../datagrid-filter.js');
class MsReceiptsPaymentsAccountController {
	constructor(MsReceiptsPaymentsAccountModel)
	{
		this.MsReceiptsPaymentsAccountModel = MsReceiptsPaymentsAccountModel;
		this.formId='receiptspaymentsAccountFrm';
		this.dataTable='#receiptspaymentsAccountTbl';
		this.route=msApp.baseUrl()+"/receiptspaymentsaccount"
	}
	
	get()
	{
		let params={};
		params.trans_date_from = $('#receiptspaymentsAccountFrm  [name=trans_date_from]').val();
		params.trans_date_to = $('#receiptspaymentsAccountFrm  [name=trans_date_to]').val();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#receiptspaymentsaccountdatamatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	receipts(head_id,company_id,is_multiple)
	{
		
		/*let params={};
		params.trans_date = $('#receiptspaymentsAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/receipt',{params})
		.then(function (response) {
			$('#receiptTbl').datagrid('loadData', response.data);
			$('#receiptWindow').window({ title: 'Receipts Details'});
		    $('#receiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/
		if(is_multiple){
			this.multipleHeadReceipts(head_id,company_id,is_multiple);
		}
		else{
			this.singleHeadReceipts(head_id,company_id,is_multiple);
		}
	}
	singleHeadReceipts(head_id,company_id,is_multiple){
		let params={};

		params.trans_date_from = $('#receiptspaymentsAccountFrm  [name=trans_date_from]').val();
		params.trans_date_to = $('#receiptspaymentsAccountFrm  [name=trans_date_to]').val();

		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/receipt',{params})
		.then(function (response) {
			$('#receiptTbl').datagrid('loadData', response.data);
			$('#receiptWindow').window({ title: 'Receipts Details'});
		    $('#receiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	multipleHeadReceipts(head_id,company_id,is_multiple){
		let params={};
		params.trans_date_from = $('#receiptspaymentsAccountFrm  [name=trans_date_from]').val();
		params.trans_date_to = $('#receiptspaymentsAccountFrm  [name=trans_date_to]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/multipleheadreceipt',{params})
		.then(function (response) {
			$('#multipleheadreceiptTbl').datagrid('loadData', response.data);
			$('#multipleheadreceiptWindow').window({ title: 'Payment Details'});
		    $('#multipleheadreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	receiptGrid(data)
	{
		var dg = $('#receiptTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
					tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						amount: Math.round(tAmout).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
					}
				]);
				
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	payments(head_id,company_id,is_multiple)
	{
		/*let params={};
		params.trans_date = $('#receiptspaymentsAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/payment',{params})
		.then(function (response) {
			$('#receiptTbl').datagrid('loadData', response.data);
			$('#receiptWindow').window({ title: 'Receipts Details'});
		    $('#receiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/

		if(is_multiple){
			this.multipleHeadPayment(head_id,company_id,is_multiple);
		}else{
			this.singleHeadPayment(head_id,company_id,is_multiple);
		}
	}

	singleHeadPayment(head_id,company_id,is_multiple){
		let params={};
		params.trans_date_from = $('#receiptspaymentsAccountFrm  [name=trans_date_from]').val();
		params.trans_date_to = $('#receiptspaymentsAccountFrm  [name=trans_date_to]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple =0;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/payment',{params})
		.then(function (response) {
			$('#receiptTbl').datagrid('loadData', response.data);
			$('#receiptWindow').window({ title: 'Receipts Details'});
		    $('#receiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});



	}

	multipleHeadPayment(head_id,company_id,is_multiple){
		let params={};
		params.trans_date_from = $('#receiptspaymentsAccountFrm  [name=trans_date_from]').val();
		params.trans_date_to = $('#receiptspaymentsAccountFrm  [name=trans_date_to]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/multipleheadpayment',{params})
		.then(function (response) {
			$('#multipleheadreceiptTbl').datagrid('loadData', response.data);
			$('#multipleheadreceiptWindow').window({ title: 'Payment Details'});
		    $('#multipleheadreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	multipleheadreceiptGrid(data)
	{
		var dg = $('#multipleheadreceiptTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			rowStyler:function(index,row){
				if (row.party_name==='Sub Total'){
				return 'background-color:pink;color:#000000;font-weight:bold;';
				}
		    },
			onLoadSuccess: function(data){
				var tdebit_amount=0;
				var tcredit_amount=0;
				var tpay_amount=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['party_name'] !=='Sub Total')
					{
					tdebit_amount+=data.rows[i]['debit_amount'].replace(/,/g,'')*1;
					tcredit_amount+=data.rows[i]['credit_amount'].replace(/,/g,'')*1;
					tpay_amount+=data.rows[i]['pay_amount'].replace(/,/g,'')*1;
				    }
				}
				$(this).datagrid('reloadFooter', [
					{ 
						debit_amount: Math.round(tdebit_amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
						credit_amount: Math.round(tcredit_amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
						pay_amount: Math.round(tpay_amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
					}
				]);
				
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatjournalpdf(value,row)
	{
		if(row.acc_trans_prnt_id){
		 return '<a href="javascript:void(0)"  onClick="MsReceiptsPaymentsAccount.journalpdf('+row.acc_trans_prnt_id+',event)">'+row.trans_no+'</a> ';
		}
		else{
			return '';
		}
       
	}
	journalpdf(id,e)
	{
		if(id==""){
			alert("Select a Journal");
			return;
		}
		if (!e) var e = window.event;                // Get the window event
		e.cancelBubble = true;                       // IE Stop propagation
		if (e.stopPropagation) e.stopPropagation();
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsReceiptsPaymentsAccount=new MsReceiptsPaymentsAccountController(new MsReceiptsPaymentsAccountModel());
MsReceiptsPaymentsAccount.receiptGrid([]);
MsReceiptsPaymentsAccount.multipleheadreceiptGrid([]);


