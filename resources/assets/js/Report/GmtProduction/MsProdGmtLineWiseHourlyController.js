require('./../../datagrid-filter.js');
let MsProdGmtLineWiseHourlyModel = require('./MsProdGmtLineWiseHourlyModel');

class MsProdGmtLineWiseHourlyController {
	constructor(MsProdGmtLineWiseHourlyModel)
	{
		this.MsProdGmtLineWiseHourlyModel = MsProdGmtLineWiseHourlyModel;
		this.formId='prodgmtlinewisehourlyFrm';
		this.dataTable='#prodgmtlinewisehourlyTbl';
		this.route=msApp.baseUrl()+"/prodgmtlinewisehourly";
	}

	get(){
		let params={};
		params.company_id = $('#prodgmtlinewisehourlyFrm  [name=company_id]').val();
		params.buyer_id = $('#prodgmtlinewisehourlyFrm  [name=buyer_id]').val();
		params.order_source_id = $('#prodgmtlinewisehourlyFrm  [name=order_source_id]').val();
		params.prod_source_id = $('#prodgmtlinewisehourlyFrm  [name=prod_source_id]').val();
		params.supplier_id = $('#prodgmtlinewisehourlyFrm  [name=supplier_id]').val();
		params.location_id = $('#prodgmtlinewisehourlyFrm  [name=location_id]').val();
		//params.date_from = $('#prodgmtlinewisehourlyFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtlinewisehourlyFrm  [name=date_to]').val();
		params.shiftname_id = $('#prodgmtlinewisehourlyFrm  [name=shiftname_id]').val();
		params.line_id = $('#prodgmtlinewisehourlyFrm  [name=line_id]').val();
		if(!params.company_id){
			alert('Select company first');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodgmtlinewisehourlyTbl').datagrid('loadData', response.data);
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
				var tOperator=0;
				var tHelper=0;
				var tManpower=0;
				var tTargetPerHour=0;
				var tCapQty=0;
				var tSewQty=0;
				var tprodused_fob=0;
				var tcm_earned_usd=0;

				var tTargetDay=0;
				var tTargetPerHourVar=0;
				var tTargetPerHourAch=0;

				var tCapacityDev=0;
				var tCapAch=0;
				var tProdMint=0;
				var tWH=0;
				var tUsedMint=0;
				var tEffiPer=0;
				var tsew7am_qty=0;
				var tsew8am_qty=0;
				var tsew9am_qty=0;
				var tsew10am_qty=0;
				var tsew11am_qty=0;
				var tsew12pm_qty=0;
				var tsew1pm_qty=0;
				var tsew2pm_qty=0;
				var tsew3pm_qty=0;
				var tsew4pm_qty=0;
				var tsew5pm_qty=0;
				var tsew6pm_qty=0;
				var tsew7pm_qty=0;
				var tsew8pm_qty=0;
				var tsew9pm_qty=0;
				var tsew10pm_qty=0;
				var tsew11pm_qty=0;
				var tsew12am_qty=0;

				for(var i=0; i<data.rows.length; i++){
					tOperator+=data.rows[i]['operator']*1;
					tHelper+=data.rows[i]['helper']*1;
					tManpower+=data.rows[i]['manpower']*1;
					tTargetPerHour+=data.rows[i]['target_per_hour']*1;
					tCapQty+=data.rows[i]['capacity_qty'].replace(/,/g,'')*1;
					tSewQty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					tprodused_fob+=data.rows[i]['prodused_fob'].replace(/,/g,'')*1;
					tcm_earned_usd+=data.rows[i]['cm_earned_usd'].replace(/,/g,'')*1;
					tTargetDay+=data.rows[i]['day_target'].replace(/,/g,'')*1;
					tTargetPerHourVar+=data.rows[i]['target_per_hour_var'].replace(/,/g,'')*1;
					tCapacityDev+=data.rows[i]['capacity_dev'].replace(/,/g,'')*1;
					tProdMint+=data.rows[i]['produced_mint'].replace(/,/g,'')*1;
					tWH+=data.rows[i]['wh']*1;
					tUsedMint+=data.rows[i]['used_mint'].replace(/,/g,'')*1;

					tsew7am_qty+=data.rows[i]['sew7am_qty']*1;
					tsew8am_qty+=data.rows[i]['sew8am_qty']*1;
					tsew9am_qty+=data.rows[i]['sew9am_qty']*1;
					tsew10am_qty+=data.rows[i]['sew10am_qty']*1;
					tsew11am_qty+=data.rows[i]['sew11am_qty']*1;
					tsew12pm_qty+=data.rows[i]['sew12pm_qty']*1;
					tsew1pm_qty+=data.rows[i]['sew1pm_qty']*1;
					tsew2pm_qty+=data.rows[i]['sew2pm_qty']*1;
					tsew3pm_qty+=data.rows[i]['sew3pm_qty']*1;
					tsew4pm_qty+=data.rows[i]['sew4pm_qty']*1;
					tsew5pm_qty+=data.rows[i]['sew5pm_qty']*1;
					tsew6pm_qty+=data.rows[i]['sew6pm_qty']*1;
					tsew7pm_qty+=data.rows[i]['sew7pm_qty']*1;
					tsew8pm_qty+=data.rows[i]['sew8pm_qty']*1;
					tsew9pm_qty+=data.rows[i]['sew9pm_qty']*1;
					tsew10pm_qty+=data.rows[i]['sew10pm_qty']*1;
					tsew11pm_qty+=data.rows[i]['sew11pm_qty']*1;
					tsew12am_qty+=data.rows[i]['sew12am_qty']*1;

				}
				tEffiPer=tProdMint/tUsedMint*100;
				tCapAch=tSewQty/tCapQty*100;
				tTargetPerHourAch=(tSewQty/tTargetDay)*100;
				
				$('#prodgmtlinewisehourlyTbl').datagrid('reloadFooter', [
					{ 
						operator: tOperator,
						helper: tHelper,
						manpower: tManpower,
						target_per_hour: tTargetPerHour,
						capacity_qty: tCapQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_qty: tSewQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						prodused_fob: tprodused_fob.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_earned_usd: tcm_earned_usd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						day_target: tTargetDay,
						target_per_hour_var: tTargetPerHourVar.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						target_per_hour_ach: tTargetPerHourAch.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						capacity_dev: tCapacityDev.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						capacity_ach: tCapAch.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						produced_mint: tProdMint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						wh: tWH,
						used_mint: tUsedMint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						effi_per: tEffiPer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew7am_qty: tsew7am_qty,
						sew8am_qty: tsew8am_qty,
						sew9am_qty: tsew9am_qty,
						sew10am_qty: tsew10am_qty,
						sew11am_qty: tsew11am_qty,
						sew12pm_qty: tsew12pm_qty,
						sew1pm_qty: tsew1pm_qty,
						sew2pm_qty: tsew2pm_qty,
						sew3pm_qty: tsew3pm_qty,
						sew4pm_qty: tsew4pm_qty,
						sew5pm_qty: tsew5pm_qty,
						sew6pm_qty: tsew6pm_qty,
						sew7pm_qty: tsew7pm_qty,
						sew8pm_qty: tsew8pm_qty,
						sew9pm_qty: tsew9pm_qty,
						sew10pm_qty: tsew10pm_qty,
						sew11pm_qty: tsew11pm_qty,
						sew12am_qty: tsew12am_qty
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

	imageWindow(file_src)
	{
		let imageArr=file_src.split(',');
		let image=''
		for(var i=0; i<imageArr.length; i++)
		{
			image +='<img  src="'+msApp.baseUrl()+'/images/'+imageArr[i]+'"/>';
		}
		$('#linewisehourlyimagegrid').html('');
		$('#linewisehourlyimagegrid').html(image);
		$('#linewisehourlyImageWindow').window('open');
	}


	formatimage(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.imageWindow('+'\''+row.flie_src+'\''+')">'+row.item_description+'</a>';
	}

	detailWindow(sew_hour,wstudy_line_setup_id,sew_date){
		let params={};
		//params.company_id = $('#prodgmtlinewisehourlyFrm  [name=company_id]').val();
		//params.buyer_id = $('#prodgmtlinewisehourlyFrm  [name=buyer_id]').val();
		//params.order_source_id = $('#prodgmtlinewisehourlyFrm  [name=order_source_id]').val();
		//params.prod_source_id = $('#prodgmtlinewisehourlyFrm  [name=prod_source_id]').val();
		//params.supplier_id = $('#prodgmtlinewisehourlyFrm  [name=supplier_id]').val();
		//params.location_id = $('#prodgmtlinewisehourlyFrm  [name=location_id]').val();
		//params.date_to = $('#prodgmtlinewisehourlyFrm  [name=date_to]').val();
		//params.shiftname_id = $('#prodgmtlinewisehourlyFrm  [name=shiftname_id]').val();
		params.company_id = $('#prodgmtlinewisehourlyFrm  [name=company_id]').val();
        params.sew_hour=sew_hour;
        params.wstudy_line_setup_id=wstudy_line_setup_id;
        params.sew_date=sew_date;
		if(!params.wstudy_line_setup_id){
			alert('No line found');
			return;
		}

		let d= axios.get(this.route+'/getdatadetails',{params})
		.then(function (response) {
			$('#prodgmtlinewisehourlydetailsTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

          $('#linewisehourlyDetailWindow').window('open');
	}

	showGridDetail(data)
	{
		var dg = $('#prodgmtlinewisehourlydetailsTbl');
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
	}
    formatdetail(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew_qty+'</a>';
	}
	formatdetail7am(value,row)
	{	
	    if(row.sew7am_qty){
	    	return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'7:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew7am_qty+'</a>';
	    }	
	    return 0;
	}
	formatdetail8am(value,row)
	{	
	    if(row.sew8am_qty){
	    	return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'8:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew8am_qty+'</a>';
	    }	
	    return 0;
	}
	formatdetail9am(value,row)
	{	
	    if(row.sew9am_qty){
	    	return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'9:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew9am_qty+'</a>';
	    }	
	    return 0;
	}
	formatdetail10am(value,row)
	{	
		if(row.sew10am_qty){	
			return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'10:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew10am_qty+'</a>';
		}
		return 0;
	}
	formatdetail11am(value,row)
	{	
	if(row.sew11am_qty){	
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'11:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew11am_qty+'</a>';
	}
	return 0;
	}

	formatdetail12pm(value,row)
	{	
	if(row.sew12pm_qty){	
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'12:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew12pm_qty+'</a>';
	}
	return 0;
	}
	formatdetail1pm(value,row)
	{
		if(row.sew1pm_qty){		
			return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'1:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew1pm_qty+'</a>';
		}
		return 0;
	}
	formatdetail2pm(value,row)
	{	
	if(row.sew2pm_qty){	
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'2:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew2pm_qty+'</a>';
	}
		return 0;
	}
	formatdetail3pm(value,row)
	{
	if(row.sew3pm_qty){			
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'3:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew3pm_qty+'</a>';
	}
		return 0;
	}
	formatdetail4pm(value,row)
	{	
	if(row.sew4pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'4:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew4pm_qty+'</a>';
	}
		return 0;
	}
	formatdetail5pm(value,row)
	{	
	if(row.sew5pm_qty){	
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'5:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew5pm_qty+'</a>';
	}
	return 0;
	}

	formatdetail6pm(value,row)
	{	
	if(row.sew6pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'6:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew6pm_qty+'</a>';
	}
	return 0;
	}
	formatdetail7pm(value,row)
	{	
	if(row.sew7pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'7:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew7pm_qty+'</a>';
	}
	return 0;
	}
	formatdetail8pm(value,row)
	{
	if(row.sew8pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'8:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew8pm_qty+'</a>';
	}
	return 0;
	}
	formatdetail9pm(value,row)
	{
	if(row.sew9pm_qty){			
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'9:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew9pm_qty+'</a>';
	}
	return 0;
	}
	formatdetail10pm(value,row)
	{	
	if(row.sew10pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'10:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew10pm_qty+'</a>';
	}
	return 0;
	}

	formatdetail11pm(value,row)
	{	
	if(row.sew11pm_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'11:00pm\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew11pm_qty+'</a>';
	}
	return 0;
	}

	formatdetail12am(value,row)
	{	
	if(row.sew12am_qty){		
		return '<a href="javascript:void(0)" onClick="MsProdGmtLineWiseHourly.detailWindow(\'12:00am\','+row.id+','+'\''+row.sew_qc_date+'\''+')">'+row.sew12am_qty+'</a>';
	}
	return 0;
	}

	targetprice(value,row,index){
		if(value !== null){
			if(row.target_per_hour*1 > value*1){
				return 'background-color:#ff00004d';
			}
		}
		/* else{
			return 'background-color:#fff';
		}
		 */
		
	}

	
}
window.MsProdGmtLineWiseHourly=new MsProdGmtLineWiseHourlyController(new MsProdGmtLineWiseHourlyModel());
MsProdGmtLineWiseHourly.showGrid([]);
MsProdGmtLineWiseHourly.showGridDetail([]);
setInterval(function(){ MsProdGmtLineWiseHourly.get(); }, 1800000);
