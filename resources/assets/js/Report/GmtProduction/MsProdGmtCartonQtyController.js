require('./../../datagrid-filter.js');
let MsProdGmtCartonQtyModel = require('./MsProdGmtCartonQtyModel');

class MsProdGmtCartonQtyController {
	constructor(MsProdGmtCartonQtyModel)
	{
		this.MsProdGmtCartonQtyModel = MsProdGmtCartonQtyModel;
		this.formId='prodgmtcartonqtyFrm';
		this.dataTable='#prodgmtcartonqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtcartonqty/getdata";
	}

	get(){
		let params={};
		params.company_id = $('#prodgmtcartonqtyFrm  [name=company_id]').val();
		params.buyer_id = $('#prodgmtcartonqtyFrm  [name=buyer_id]').val();
		params.order_source_id = $('#prodgmtcartonqtyFrm  [name=order_source_id]').val();
		params.prod_source_id = $('#prodgmtcartonqtyFrm  [name=prod_source_id]').val();
		params.supplier_id = $('#prodgmtcartonqtyFrm  [name=supplier_id]').val();
		params.location_id = $('#prodgmtcartonqtyFrm  [name=location_id]').val();
		params.date_from = $('#prodgmtcartonqtyFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtcartonqtyFrm  [name=date_to]').val();
		params.shiftname_id = $('#prodgmtcartonqtyFrm  [name=shiftname_id]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProdGmtCartonQty.showGrid(response.data.datad)
			$('#prodgmtcartonqtyTbl').datagrid('loadData', response.data);
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
			nowrap:true,
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

	
	/* formatter(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCartonQty.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	} */



}
window.MsProdGmtCartonQty=new MsProdGmtCartonQtyController(new MsProdGmtCartonQtyModel());
MsProdGmtCartonQty.showGrid([]);
