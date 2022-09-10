let MsTodayAccountModel = require('./MsTodayAccountModel');
require('./../../datagrid-filter.js');
class MsTodayAccountController {
	constructor(MsTodayAccountModel)
	{
		this.MsTodayAccountModel = MsTodayAccountModel;
		this.formId='todayAccountFrm';
		this.dataTable='#todayAccountTbl';
		this.route=msApp.baseUrl()+"/todayaccount"
	}
	
	get()
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#todayaccountdatamatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	todayinflow(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/todayinflow",{params})
		.then(function (response) {
			$('#todayinflowTbl').datagrid('loadData', response.data);
			$('#todayinflowWindow').window({ title: 'Inflow Details'});
		    $('#todayinflowWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	monthinflow(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/monthinflow",{params})
		.then(function (response) {
			$('#todayinflowTbl').datagrid('loadData', response.data);
			$('#todayinflowWindow').window({ title: 'Inflow Details'});
		    $('#todayinflowWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	todayoutflow(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/todayoutflow",{params})
		.then(function (response) {
			$('#todayinflowTbl').datagrid('loadData', response.data);
			$('#todayinflowWindow').window({ title: 'Outflow Details'});
		    $('#todayinflowWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	monthoutflow(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/monthoutflow",{params})
		.then(function (response) {
			$('#todayinflowTbl').datagrid('loadData', response.data);
			$('#todayinflowWindow').window({ title: 'Outflow Details'});
		    $('#todayinflowWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	todayinflowGrid(data)
	{
		var dg = $('#todayinflowTbl');
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

	todayrevenue(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/todayrevenue",{params})
		.then(function (response) {
			$('#todayrevenueTbl').datagrid('loadData', response.data);
			$('#todayrevenueWindow').window({ title: 'Revenue Details'});
		    $('#todayrevenueWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	monthrevenue(head_id,company_id)
	{
		let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/monthrevenue",{params})
		.then(function (response) {
			$('#todayrevenueTbl').datagrid('loadData', response.data);
			$('#todayrevenueWindow').window({ title: 'Revenue Details'});
		    $('#todayrevenueWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	todayrevenueGrid(data)
	{
		var dg = $('#todayrevenueTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			rowStyler:function(index,row){
				if (row.company_code==='Sub Total'){
				return 'background-color:pink;color:#000000;font-weight:bold;';
				}
		    },
			onLoadSuccess: function(data){
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['company_code'] !=='Sub Total')
					{
					tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
					}
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


	getreceiptpayments()
	{
		let params={};
		var trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.trans_date_from = trans_date;
		params.trans_date_to = trans_date;

		let d= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/getdatatoday',{params})
		.then(function (response) {
			$('#todayaccountdatamatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	receipts(head_id,company_id,is_multiple)
	{
		
		/*let params={};
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/receipt',{params})
		.then(function (response) {
			$('#todayreceiptTbl').datagrid('loadData', response.data);
			$('#todayreceiptWindow').window({ title: 'Receipts Details'});
		    $('#todayreceiptWindow').window('open');
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

		var trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.trans_date_from = trans_date;
		params.trans_date_to = trans_date;

		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/receipt',{params})
		.then(function (response) {
			$('#todayreceiptTbl').datagrid('loadData', response.data);
			$('#todayreceiptWindow').window({ title: 'Receipts Details'});
		    $('#todayreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	multipleHeadReceipts(head_id,company_id,is_multiple){
		let params={};
		var trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.trans_date_from = trans_date;
		params.trans_date_to = trans_date;
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/multipleheadreceipt',{params})
		.then(function (response) {
			$('#multipleheadtodayreceiptTbl').datagrid('loadData', response.data);
			$('#multipleheadtodayreceiptWindow').window({ title: 'Payment Details'});
		    $('#multipleheadtodayreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	receiptGrid(data)
	{
		var dg = $('#todayreceiptTbl');
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
		params.trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/payment',{params})
		.then(function (response) {
			$('#todayreceiptTbl').datagrid('loadData', response.data);
			$('#todayreceiptWindow').window({ title: 'Receipts Details'});
		    $('#todayreceiptWindow').window('open');
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
		var trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.trans_date_from = trans_date;
		params.trans_date_to = trans_date;
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple =0;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/payment',{params})
		.then(function (response) {
			$('#todayreceiptTbl').datagrid('loadData', response.data);
			$('#todayreceiptWindow').window({ title: 'Receipts Details'});
		    $('#todayreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});



	}

	multipleHeadPayment(head_id,company_id,is_multiple){
		let params={};
		var trans_date = $('#todayAccountFrm  [name=trans_date]').val();
		params.trans_date_from = trans_date;
		params.trans_date_to = trans_date;
		params.head_id = head_id;
		params.company_id = company_id;
		params.is_multiple = is_multiple;
		let data= axios.get(msApp.baseUrl()+'/receiptspaymentsaccount/multipleheadpayment',{params})
		.then(function (response) {
			$('#multipleheadtodayreceiptTbl').datagrid('loadData', response.data);
			$('#multipleheadtodayreceiptWindow').window({ title: 'Payment Details'});
		    $('#multipleheadtodayreceiptWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	multipleheadreceiptGrid(data)
	{
		var dg = $('#multipleheadtodayreceiptTbl');
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
		 return '<a href="javascript:void(0)"  onClick="MsTodayAccount.journalpdf('+row.acc_trans_prnt_id+',event)">'+row.trans_no+'</a> ';
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
}	
window.MsTodayAccount=new MsTodayAccountController(new MsTodayAccountModel());
MsTodayAccount.todayinflowGrid([]);
MsTodayAccount.todayrevenueGrid([]);
MsTodayAccount.receiptGrid([]);
MsTodayAccount.multipleheadreceiptGrid([]);

