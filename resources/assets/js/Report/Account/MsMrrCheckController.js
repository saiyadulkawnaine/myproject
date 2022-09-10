let MsMrrCheckModel = require('./MsMrrCheckModel');
require('./../../datagrid-filter.js');
class MsMrrCheckController {
	constructor(MsMrrCheckModel)
	{
		this.MsMrrCheckModel = MsMrrCheckModel;
		this.formId='mrrcheckFrm';
		this.dataTable='#mrrcheckTbl';
		this.route=msApp.baseUrl()+"/mrrcheck";
	}

	get(){
		let params={};
		params.mrr_no = $('#mrrcheckFrm  [name=mrr_no]').val();
		if(params.mrr_no==''){
			alert('Please Enter MRR No');
			return;
		}
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			//$('#mrrcheckcontainer').html(response.data);
            $('#mrrcheckTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			alert('vvvv')
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
			nowrap:false,
			emptyMsg:'No Record Found',
			/* onLoadSuccess: function(data){
				var AdvInvoiceQty=0;
			
				
				for(var i=0; i<data.rows.length; i++){
					AdvInvoiceQty+=data.rows[i]['adv_invoice_qty'].replace(/,/g,'')*1;
					
					
				}
				$('#mrrcheckTbl').datagrid('reloadFooter', [
					{
						adv_invoice_qty: AdvInvoiceQty.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
						
					}
				]);
			} */
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatjournalpdf(value,row)
	{
		let bt= '<a href="javascript:void(0)"  onClick="MsMrrCheck.journalpdf('+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>JV</span></a> ';
        return bt;
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
		$('#acctransprntsearchWindow').window('close');
	}

}
window.MsMrrCheck=new MsMrrCheckController(new MsMrrCheckModel());
MsMrrCheck.showGrid([]);