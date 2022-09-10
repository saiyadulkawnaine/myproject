let MsTnaReportModel = require('./MsTnaReportModel');
require('./datagrid-filter.js');

class MsTnaReportController {
	constructor(MsTnaReportModel)
	{
		this.MsTnaReportModel = MsTnaReportModel;
		this.formId='tnareportFrm';
		this.dataTable='#tnareportTbl';
		this.route=msApp.baseUrl()+"/tnareport"
	}
	getParams(){
		let params={};
		params.company_id = $('#tnareportFrm  [name=company_id]').val();
		params.buyer_id = $('#tnareportFrm  [name=buyer_id]').val();
		params.date_from = $('#tnareportFrm  [name=date_from]').val();
		params.date_to = $('#tnareportFrm  [name=date_to]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		if( params.date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(params.date_to==''){
			alert('Please Select a date range');
			return;
		}

		let from=new Date(params.date_from);
		let to=new Date(params.date_to);

		var fromDate = new Date(
		from.getFullYear(),
		from.getMonth(),
		from.getDate(),
		from.getHours(),
		from.getMinutes(),
		from.getSeconds()
		);
		var fromyyyy = fromDate.getFullYear().toString();                                    
		var frommm = (fromDate.getMonth()+1).toString();//getMonth() is zero-based

		var toDate = new Date(
		to.getFullYear(),
		to.getMonth(),
		to.getDate(),
		to.getHours(),
		to.getMinutes(),
		to.getSeconds()
		);
		var toyyyy = toDate.getFullYear().toString();                                    
		var tomm = (toDate.getMonth()+1).toString();//getMonth() is zero-based

		if(fromyyyy != toyyyy){
			alert('Cross Year not allowed ');
			return;
		}

		if(frommm != tomm){
			alert('Cross month not allowed ');
			return;
		}

		//let comp=$( "#myselect option:selected" ).text();
		let comp=$('#tnareportFrm  [name=company_id] option:selected').text()

		let formatted_month =msApp.months[toDate.getMonth()] + "-" + toDate.getFullYear();
		var title='Ship Date Wise TNA Report :  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Shipment Month : '+formatted_month;
		var p = $('#tnareportlayout').layout('panel', 'center').panel('setTitle', title);
		

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#tnareportTbl').datagrid('loadData', response.data);
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
			fitColumns:false,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var po_qty=0;
				for(var i=0; i<data.rows.length; i++){
					po_qty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	po_qty: po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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


	dyedyarnaclstart(value,row,index){
		if(row.dyed_yarn_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyed_yarn_start_diff*1 <= 2 && row.min_dyed_yarn_receive_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	dyedyarnaclend(value,row,index){
		if(row.dyed_yarn_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyed_yarn_end_diff*1 <= 2 && row.max_dyed_yarn_receive_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	yarnaclstart(value,row,index){
		if(row.yarn_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.yarn_start_diff*1 <= 2 && row.min_yarn_receive_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	yarnaclend(value,row,index){
		if(row.yarn_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.yarn_end_diff*1 <= 2 && row.max_yarn_receive_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	yarnisuaclstart(value,row,index){
		if(row.yarn_isu_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.yarn_isu_start_diff*1 <= 2 && row.min_yarn_isu_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	yarnisuaclend(value,row,index){
		if(row.yarn_isu_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.yarn_isu_end_diff*1 <= 2 && row.max_yarn_isu_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	knitaclstart(value,row,index){
		if(row.knit_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.knit_start_diff*1 <= 2 && row.min_knit_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	knitaclend(value,row,index){
		if(row.knit_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.knit_end_diff*1 <= 2 && row.max_knit_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	dyeingaclstart(value,row,index){
		if(row.dyeing_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyeing_start_diff*1 <= 2 && row.min_dyeing_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	dyeingaclend(value,row,index){
		if(row.dyeing_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyeing_end_diff*1 <= 2 && row.max_dyeing_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	aopaclstart(value,row,index){
		if(row.aop_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.aop_start_diff*1 <= 2 && row.min_aop_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	aopaclend(value,row,index){
		if(row.aop_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.aop_end_diff*1 <= 2 && row.max_aop_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	dyeingfinishaclstart(value,row,index){
		if(row.dyeingfinish_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyeingfinish_start_diff*1 <= 2 && row.min_dyeingfinish_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	dyeingfinishaclend(value,row,index){
		if(row.dyeingfinish_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.dyeingfinish_end_diff*1 <= 2 && row.max_dyeingfinish_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	trimaclstart(value,row,index){
		if(row.trim_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.trim_start_diff*1 <= 2 && row.min_trim_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	trimaclend(value,row,index){
		if(row.trim_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.trim_end_diff*1 <= 2 && row.max_trim_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	ppaclstart(value,row,index){
		if(row.pp_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.pp_start_diff*1 <= 2 && row.min_pp_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	ppaclend(value,row,index){
		if(row.pp_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.pp_end_diff*1 <= 2 && row.max_pp_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	cutaclstart(value,row,index){
		if(row.cut_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.cut_start_diff*1 <= 2 && row.min_cut_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	cutaclend(value,row,index){
		if(row.cut_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.cut_end_diff*1 <= 2 && row.max_cut_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	embspaclstart(value,row,index){
		if(row.embsp_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.embsp_start_diff*1 <= 2 && row.min_rcv_scr_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	embspaclend(value,row,index){
		if(row.embsp_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.embsp_end_diff*1 <= 2 && row.max_rcv_scr_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	sewaclstart(value,row,index){
		if(row.sew_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.sew_start_diff*1 <= 2 && row.min_sew_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	sewaclend(value,row,index){
		if(row.sew_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.sew_end_diff*1 <= 2 && row.max_sew_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	caraclstart(value,row,index){
		if(row.car_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.car_start_diff*1 <= 2 && row.min_car_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	caraclend(value,row,index){
		if(row.car_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.car_end_diff*1 <= 2 && row.max_car_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	inspaclstart(value,row,index){
		if(row.insp_start_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.insp_start_diff*1 <= 2 && row.min_insp_date=='--'){
			return 'background-color:#ffff00';
		}
	}

	inspaclend(value,row,index){
		if(row.insp_end_diff*1 < 0 ){
		return 'background-color:#ff0000';
		}
		else if(row.insp_end_diff*1 <= 2 && row.max_insp_date.indexOf("%")>=0){
			return 'background-color:#ffff00';
		}
	}

	

	
}
window.MsTnaReport=new MsTnaReportController(new MsTnaReportModel());
MsTnaReport.showGrid([]);
