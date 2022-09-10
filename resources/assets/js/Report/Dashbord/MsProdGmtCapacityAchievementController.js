let MsProdGmtCapacityAchievementModel = require('./MsProdGmtCapacityAchievementModel');
require('./../../datagrid-filter.js');
class MsProdGmtCapacityAchievementController {
	constructor(MsProdGmtCapacityAchievementModel)
	{
		this.MsProdGmtCapacityAchievementModel = MsProdGmtCapacityAchievementModel;
		this.formId='prodgmtcapacityachievementFrm';
		this.dataTable='#prodgmtcapacityachievementTbl';
		this.route=msApp.baseUrl()+"/prodgmtcapacityachievement/getdata"
	}
	
	get(){
		let params={};
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#pcacolorsizematrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	showGrid(data)
	{
		var dg = $('#capacitydetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tOperator=0;
				var tHelper=0;
				var tManpower=0;
				var tCapQty=0;
				var tSewQty=0;
				var tCapacityDev=0;
				var tCapAch=0;
				var tProdMint=0;
				var tWH=0;
				var tUsedMint=0;
				var tEffiPer=0;
				var tCmEarnedUsd=0;
				var tCmEarnedTk=0;
				var tProdFob=0;
				var tProdFobTk=0;
				for(var i=0; i<data.rows.length; i++){
					tOperator+=data.rows[i]['operator']*1;
					tHelper+=data.rows[i]['helper']*1;
					tManpower+=data.rows[i]['manpower']*1;
					tCapQty+=data.rows[i]['capacity_qty'].replace(/,/g,'')*1;
					tSewQty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					tCapacityDev+=data.rows[i]['capacity_dev'].replace(/,/g,'')*1;
					//tCapAch+=data.rows[i]['capacity_ach'].replace(/,/g,'')*1;
					tProdMint+=data.rows[i]['produced_mint'].replace(/,/g,'')*1;
					tWH+=data.rows[i]['wh']*1;
					tUsedMint+=data.rows[i]['used_mint'].replace(/,/g,'')*1;
					tCmEarnedUsd+=data.rows[i]['cm_earned_usd'].replace(/,/g,'')*1;
					tCmEarnedTk+=data.rows[i]['cm_earned_tk'].replace(/,/g,'')*1;
					tProdFob+=data.rows[i]['prodused_fob'].replace(/,/g,'')*1;
					tProdFobTk+=data.rows[i]['prodused_fob_tk'].replace(/,/g,'')*1;
				}
				tEffiPer=tProdMint/tUsedMint*100;
				tCapAch=tSewQty/tCapQty*100;
				
				$('#capacitydetailTbl').datagrid('reloadFooter', [
					{ 
						operator: tOperator.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						helper: tHelper.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						manpower: tManpower.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						capacity_qty: tCapQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_qty: tSewQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						capacity_dev: tCapacityDev.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						capacity_ach: tCapAch.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						produced_mint: tProdMint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						wh: tWH.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						used_mint: tUsedMint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						effi_per: tEffiPer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_earned_usd: tCmEarnedUsd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_earned_tk: tCmEarnedTk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						prodused_fob: tProdFob.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						prodused_fob_tk:tProdFobTk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	imageWindow(flie_src){
		var output = document.getElementById('dashbordReportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dashbordReportImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsProdGmtCapacityAchievement.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	/*prodgmtcapacityachievementWindow(){
		    this.get();
			$('#prodgmtcapacityachievementWindow').window('open');
    }*/

    formatSaleOrder(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.detailsSaleOrderWindow('+row.id+','+row.month_from+','+row.month_to+','+'\''+row.company_id+'\''+')"> '+row.qty+'</a>';
	}

	capacityDevformat(value,row,index)
	{
		
		
		
		if (value.replace(/,/g,'')*1 < 0){
				return 'background-color:#ff6666;';
		}
	}

	cmDznformat(value,row,index)
	{
		if (value < 0)
		{
			return 'background-color:#ff6666;';
		}
	}
    
    detailWindow(company_id)
	{
		

		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getsewing",{params});
		data.then(function (response) {
			//$('#owbscs').html(response.data);
			var dg = $('#capacitydetailTbl');
			dg.datagrid('loadData', response.data);
			$('#capacitydetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*detailWindowTwo(company_id)
	{
		

		let params={};
		params.company_id=company_id;
		$('#prodgmtlinewisehourlyFrm  [name=company_id]').val(company_id);
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtlinewisehourly/getdata",{params});
		data.then(function (response) {
			var dg = $('#prodgmtlinewisehourlyTbl');
			dg.datagrid('loadData', response.data);
			$('#capacitydetailWindowTwo').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}*/

	detailWindowTwo(company_id)
	{
		$('#capacitydetailWindowTwo').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodgmtlinewisehourly")
		.then(function (response) {
			$('#capacitydetailWindowTwoContainer').html(response.data);
			$.parser.parse('#capacitydetailWindowTwoContainer');
			$('#prodgmtlinewisehourlyFrm  [name=company_id]').val(company_id);
			$('#prodgmtlinewisehourlyFrm  [name=date_to]').val($('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val());
			//MsProdGmtLineWiseHourly.get();
		})
		.catch(function (error) {
			console.log(error);
		});

		d.then(function (response) {
			MsProdGmtLineWiseHourly.get();
		})
		.catch(function (error) {
			console.log(error);
		});


		
	}

	bepWindow (company_id)
	{
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getbep",{params});
		data.then(function (response) {
			$('#capacitybepmatrix').html(response.data);
			$('#capacitybepWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

		
	}
	cmWindow (company_id)
	{
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getcm",{params});
		data.then(function (response) {
			$('#capacitycmmatrix').html(response.data);
			$('#capacitycmWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

		
	}

	cartonWindow(company_id)
	{
		
        $('#capacitycartonWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getcarton",{params});
		data.then(function (response) {
			var dg = $('#capacitycartonTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCarton(data)
	{
		var dg = $('#capacitycartonTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;

				var trequired_carton=0;
				var tno_of_carton=0;
				var tno_of_carton_yester_day=0;
				var ttotal_carton=0;
				var tyet_to_no_of_carton=0;

				var tfinishing_qty=0;
				var tfinishingyesterday_qty=0;
				var ttotal_finishing=0;
				var tyet_to_finishing=0;
				var tfinishing_amount=0;

				var tcm_mnuf=0;
				var tcm_mkt=0;
				
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;

					trequired_carton+=data.rows[i]['required_carton'].replace(/,/g,'')*1;
					tno_of_carton+=data.rows[i]['no_of_carton'].replace(/,/g,'')*1;
					tno_of_carton_yester_day+=data.rows[i]['no_of_carton_yester_day'].replace(/,/g,'')*1;
					ttotal_carton+=data.rows[i]['total_carton'].replace(/,/g,'')*1;

					tyet_to_no_of_carton+=data.rows[i]['yet_to_no_of_carton'].replace(/,/g,'')*1;

					tfinishing_qty+=data.rows[i]['finishing_qty'].replace(/,/g,'')*1;
					tfinishingyesterday_qty+=data.rows[i]['finishingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_finishing+=data.rows[i]['total_finishing'].replace(/,/g,'')*1;
					tyet_to_finishing+=data.rows[i]['yet_to_finishing'].replace(/,/g,'')*1;
					tfinishing_amount+=data.rows[i]['finishing_amount'].replace(/,/g,'')*1;

					tcm_mnuf+=data.rows[i]['cm_mnuf'].replace(/,/g,'')*1;
					tcm_mkt+=data.rows[i]['cm_mkt'].replace(/,/g,'')*1;
					
				}
				
				$('#capacitycartonTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						required_carton: trequired_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_carton: tno_of_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_carton_yester_day: tno_of_carton_yester_day.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_carton: ttotal_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_no_of_carton: tyet_to_no_of_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

						finishing_qty: tfinishing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						finishingyesterday_qty: tfinishingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_finishing: ttotal_finishing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_finishing: tyet_to_finishing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						finishing_amount: tfinishing_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_mnuf: tcm_mnuf.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_mkt: tcm_mkt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						


					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	cartonMonthWindow(company_id)
	{
		
        $('#capacitycartonMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getcartonmonth",{params});
		data.then(function (response) {
			var dg = $('#capacitycartonMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCartonMonth(data)
	{
		var dg = $('#capacitycartonMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;

				var trequired_carton=0;
				var tno_of_carton=0;
				var tno_of_carton_yester_day=0;
				var ttotal_carton=0;
				var tyet_to_no_of_carton=0;

				var tfinishing_qty=0;
				var tfinishingyesterday_qty=0;
				var ttotal_finishing=0;
				var tyet_to_finishing=0;
				var tfinishing_amount=0;

				var tcm_mnuf=0;
				var tcm_mkt=0;
				
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;

					trequired_carton+=data.rows[i]['required_carton'].replace(/,/g,'')*1;
					tno_of_carton+=data.rows[i]['no_of_carton'].replace(/,/g,'')*1;
					tno_of_carton_yester_day+=data.rows[i]['no_of_carton_yester_day'].replace(/,/g,'')*1;
					ttotal_carton+=data.rows[i]['total_carton'].replace(/,/g,'')*1;

					tyet_to_no_of_carton+=data.rows[i]['yet_to_no_of_carton'].replace(/,/g,'')*1;

					tfinishing_qty+=data.rows[i]['finishing_qty'].replace(/,/g,'')*1;
					tfinishingyesterday_qty+=data.rows[i]['finishingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_finishing+=data.rows[i]['total_finishing'].replace(/,/g,'')*1;
					tyet_to_finishing+=data.rows[i]['yet_to_finishing'].replace(/,/g,'')*1;
					tfinishing_amount+=data.rows[i]['finishing_amount'].replace(/,/g,'')*1;

					tcm_mnuf+=data.rows[i]['cm_mnuf'].replace(/,/g,'')*1;
					tcm_mkt+=data.rows[i]['cm_mkt'].replace(/,/g,'')*1;
					
				}
				
				$('#capacitycartonMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						required_carton: trequired_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_carton: tno_of_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_carton_yester_day: tno_of_carton_yester_day.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_carton: ttotal_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_no_of_carton: tyet_to_no_of_carton.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

						finishing_qty: tfinishing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						finishingyesterday_qty: tfinishingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_finishing: ttotal_finishing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_finishing: tyet_to_finishing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						finishing_amount: tfinishing_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_mnuf: tcm_mnuf.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cm_mkt: tcm_mkt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						


					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getCommon(){

		var route_url=$('#capacitycommonWindowFrm  [name=route_url]').val();
		let params={};
		params.company_id=$('#capacitycommonWindowFrm  [name=company_id]').val();
		params.style_ref=$('#capacitycommonWindowFrm  [name=style_ref]').val();
		params.sale_order_no=$('#capacitycommonWindowFrm  [name=sale_order_no]').val();
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/"+route_url,{params});
		data.then(function (response) {
			$('#capacitycommondata').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	commonWindow(company_id)
	{
		
        $('#capacitycommonWindow').window('open');
        $('#capacitycommonWindowFrm  [name=company_id]').val(company_id);
        $('#capacitycommonWindowFrm  [name=route_url]').val('getdataall');
        
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getdataall",{params});
		data.then(function (response) {
			$('#capacitycommondata').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	commonWindowForMonth(company_id)
	{
		
        $('#capacitycommonWindow').window('open');
         $('#capacitycommonWindowFrm  [name=company_id]').val(company_id);
         $('#capacitycommonWindowFrm  [name=route_url]').val('getdataallformonth');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getdataallformonth",{params});
		data.then(function (response) {
			$('#capacitycommondata').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	cuttingWindow(company_id)
	{
		
        $('#capacitycuttingWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getcutting",{params});
		data.then(function (response) {
			var dg = $('#capacitycuttingTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCutting(data)
	{
		var dg = $('#capacitycuttingTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var tcutting_qty=0;
				var tcuttingyesterday_qty=0;
				var ttotal_cut=0;
				var tyet_cut=0;
				var tstyle_cad_cons=0;
				var tused_fabric=0;
				var twastage_fabric=0;
				var treq_fabric=0;
				var tused_variance=0;
				var tcut_pcs_should_be=0;
				var twastage_variance=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tcutting_qty+=data.rows[i]['cutting_qty'].replace(/,/g,'')*1;
					tcuttingyesterday_qty+=data.rows[i]['cuttingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_cut+=data.rows[i]['total_cut'].replace(/,/g,'')*1;
					tyet_cut+=data.rows[i]['yet_cut'].replace(/,/g,'')*1;
					tstyle_cad_cons+=data.rows[i]['style_cad_cons'].replace(/,/g,'')*1;
					tused_fabric+=data.rows[i]['used_fabric'].replace(/,/g,'')*1;
					twastage_fabric+=data.rows[i]['wastage_fabric'].replace(/,/g,'')*1;
					treq_fabric+=data.rows[i]['req_fabric'].replace(/,/g,'')*1;
					tused_variance+=data.rows[i]['used_variance'].replace(/,/g,'')*1;
					tcut_pcs_should_be+=data.rows[i]['cut_pcs_should_be'].replace(/,/g,'')*1;
					twastage_variance+=data.rows[i]['wastage_variance'].replace(/,/g,'')*1;
				}
				
				$('#capacitycuttingTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cutting_qty: tcutting_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cuttingyesterday_qty: tcuttingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_cut: ttotal_cut.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_cut: tyet_cut.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						style_cad_cons: tstyle_cad_cons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						used_fabric: tused_fabric.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						wastage_fabric: twastage_fabric.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_fabric: treq_fabric.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						used_variance: tused_variance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cut_pcs_should_be: tcut_pcs_should_be.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						wastage_variance: twastage_variance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	cuttingMonthWindow(company_id)
	{
		
        $('#capacitycuttingMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getcuttingmonth",{params});
		data.then(function (response) {
			var dg = $('#capacitycuttingMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCuttingMonth(data)
	{
		var dg = $('#capacitycuttingMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var tcutting_qty=0;
				var tcuttingyesterday_qty=0;
				var ttotal_cut=0;
				var tyet_cut=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tcutting_qty+=data.rows[i]['cutting_qty'].replace(/,/g,'')*1;
					tcuttingyesterday_qty+=data.rows[i]['cuttingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_cut+=data.rows[i]['total_cut'].replace(/,/g,'')*1;
					tyet_cut+=data.rows[i]['yet_cut'].replace(/,/g,'')*1;
				}
				
				$('#capacitycuttingMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cutting_qty: tcutting_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cuttingyesterday_qty: tcuttingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_cut: ttotal_cut.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_cut: tyet_cut.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	exfactoryWindow(company_id)
	{
		
        $('#capacityexfactoryWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getexfactory",{params});
		data.then(function (response) {
			var dg = $('#capacityexfactoryTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridExfactory(data)
	{
		var dg = $('#capacityexfactoryTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;
				var texfactory_qty=0;
				var texfactoryyesterday_qty=0;
				var ttotal_exfactory=0;
				var tyet_exfactory=0;
				var texfactory_amount=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					texfactory_qty+=data.rows[i]['exfactory_qty'].replace(/,/g,'')*1;
					texfactoryyesterday_qty+=data.rows[i]['exfactoryyesterday_qty'].replace(/,/g,'')*1;
					ttotal_exfactory+=data.rows[i]['total_exfactory'].replace(/,/g,'')*1;
					tyet_exfactory+=data.rows[i]['yet_exfactory'].replace(/,/g,'')*1;
					texfactory_amount+=data.rows[i]['exfactory_amount'].replace(/,/g,'')*1;

				}
				
				$('#capacityexfactoryTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactory_qty: texfactory_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactoryyesterday_qty: texfactoryyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_exfactory: ttotal_exfactory.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_exfactory: tyet_exfactory.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactory_amount: texfactory_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),


					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	exfactoryMonthWindow(company_id)
	{
		
        $('#capacityexfactoryMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getexfactorymonth",{params});
		data.then(function (response) {
			var dg = $('#capacityexfactoryMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridExfactoryMonth(data)
	{
		var dg = $('#capacityexfactoryMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;
				var texfactory_qty=0;
				var texfactoryyesterday_qty=0;
				var ttotal_exfactory=0;
				var tyet_exfactory=0;
				var texfactory_amount=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					texfactory_qty+=data.rows[i]['exfactory_qty'].replace(/,/g,'')*1;
					texfactoryyesterday_qty+=data.rows[i]['exfactoryyesterday_qty'].replace(/,/g,'')*1;
					ttotal_exfactory+=data.rows[i]['total_exfactory'].replace(/,/g,'')*1;
					tyet_exfactory+=data.rows[i]['yet_exfactory'].replace(/,/g,'')*1;
					texfactory_amount+=data.rows[i]['exfactory_amount'].replace(/,/g,'')*1;

				}
				
				$('#capacityexfactoryMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactory_qty: texfactory_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactoryyesterday_qty: texfactoryyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_exfactory: ttotal_exfactory.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_exfactory: tyet_exfactory.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exfactory_amount: texfactory_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	sewingMonthWindow(company_id)
	{
		
        $('#capacitysewingMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getsewingmonth",{params});
		data.then(function (response) {
			var dg = $('#capacitysewingMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSewingMonth(data)
	{
		var dg = $('#capacitysewingMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;
				var tsewing_qty=0;
				var tsewingyesterday_qty=0;
				var ttotal_sewing=0;
				var tyet_sewing=0;
				var tsewing_amount=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tsewing_qty+=data.rows[i]['sewing_qty'].replace(/,/g,'')*1;
					tsewingyesterday_qty+=data.rows[i]['sewingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_sewing+=data.rows[i]['total_sewing'].replace(/,/g,'')*1;
					tyet_sewing+=data.rows[i]['yet_sewing'].replace(/,/g,'')*1;
					tsewing_amount+=data.rows[i]['sewing_amount'].replace(/,/g,'')*1;
				}
				
				$('#capacitysewingMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewing_qty: tsewing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewingyesterday_qty: tsewingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_sewing: ttotal_sewing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_sewing: tyet_sewing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewing_amount: tsewing_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	invoiceMonthWindow(company_id)
	{
		
        $('#capacityinvoiceMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getinvoicemonth",{params});
		data.then(function (response) {
			var dg = $('#capacityinvoiceMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridInvoiceMonth(data)
	{
		var dg = $('#capacityinvoiceMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;
				var tinvoice_qty=0;
				var tinvoiceyesterday_qty=0;
				var ttotal_invoice=0;
				var tyet_invoice=0;
				var tinvoice_amount=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tinvoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tinvoiceyesterday_qty+=data.rows[i]['invoiceyesterday_qty'].replace(/,/g,'')*1;
					ttotal_invoice+=data.rows[i]['total_invoice'].replace(/,/g,'')*1;
					tyet_invoice+=data.rows[i]['yet_invoice'].replace(/,/g,'')*1;
					tinvoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;

				}
				
				$('#capacityinvoiceMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_qty: tinvoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoiceyesterday_qty: tinvoiceyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_invoice: ttotal_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_invoice: tyet_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: tinvoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),


					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	scprintWindow(company_id)
	{
		
        $('#capacityscprintWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getscprint",{params});
		data.then(function (response) {
			var dg = $('#capacityscprintTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridScprint(data)
	{
		var dg = $('#capacityscprintTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var tscprint_qty=0;
				var tscprintyesterday_qty=0;
				var ttotal_scprint=0;
				var tyet_scprint=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tscprint_qty+=data.rows[i]['scprint_qty'].replace(/,/g,'')*1;
					tscprintyesterday_qty+=data.rows[i]['scprintyesterday_qty'].replace(/,/g,'')*1;
					ttotal_scprint+=data.rows[i]['total_scprint'].replace(/,/g,'')*1;
					tyet_scprint+=data.rows[i]['yet_scprint'].replace(/,/g,'')*1;
				}
				
				$('#capacityscprintTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprint_qty: tscprint_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprintyesterday_qty: tscprintyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_scprint: ttotal_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_scprint: tyet_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	scprintMonthWindow(company_id)
	{
		
        $('#capacityscprintMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getscprintmonth",{params});
		data.then(function (response) {
			var dg = $('#capacityscprintMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridScprintMonth(data)
	{
		var dg = $('#capacityscprintMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var tscprint_qty=0;
				var tscprintyesterday_qty=0;
				var ttotal_scprint=0;
				var tyet_scprint=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tscprint_qty+=data.rows[i]['scprint_qty'].replace(/,/g,'')*1;
					tscprintyesterday_qty+=data.rows[i]['scprintyesterday_qty'].replace(/,/g,'')*1;
					ttotal_scprint+=data.rows[i]['total_scprint'].replace(/,/g,'')*1;
					tyet_scprint+=data.rows[i]['yet_scprint'].replace(/,/g,'')*1;
				}
				
				$('#capacityscprintMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprint_qty: tscprint_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprintyesterday_qty: tscprintyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_scprint: ttotal_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_scprint: tyet_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	scprintMonthTgtWindow(company_id)
	{
		
        $('#capacityscprintMonthTgtWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getscprintmonthtgt",{params});
		data.then(function (response) {
			var dg = $('#capacityscprintMonthTgtTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridScprintTgtMonth(data)
	{
		var dg = $('#capacityscprintMonthTgtTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var treq_qty=0;
				var tscprint_qty=0;
				var tscprintyesterday_qty=0;
				var ttotal_scprint=0;
				var tyet_scprint=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					treq_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
					tscprint_qty+=data.rows[i]['scprint_qty'].replace(/,/g,'')*1;
					tscprintyesterday_qty+=data.rows[i]['scprintyesterday_qty'].replace(/,/g,'')*1;
					ttotal_scprint+=data.rows[i]['total_scprint'].replace(/,/g,'')*1;
					tyet_scprint+=data.rows[i]['yet_scprint'].replace(/,/g,'')*1;
				}
				
				$('#capacityscprintMonthTgtTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_qty: treq_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprint_qty: tscprint_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scprintyesterday_qty: tscprintyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_scprint: ttotal_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_scprint: tyet_scprint.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	embWindow(company_id)
	{
		
        $('#capacityembWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getemb",{params});
		data.then(function (response) {
			var dg = $('#capacityembTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridEmb(data)
	{
		var dg = $('#capacityembTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var temb_qty=0;
				var tembyesterday_qty=0;
				var ttotal_emb=0;
				var tyet_emb=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					temb_qty+=data.rows[i]['emb_qty'].replace(/,/g,'')*1;
					tembyesterday_qty+=data.rows[i]['embyesterday_qty'].replace(/,/g,'')*1;
					ttotal_emb+=data.rows[i]['total_emb'].replace(/,/g,'')*1;
					tyet_emb+=data.rows[i]['yet_emb'].replace(/,/g,'')*1;
				}
				
				$('#capacityembTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						emb_qty: temb_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						embyesterday_qty: tembyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_emb: ttotal_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_emb: tyet_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	embMonthWindow(company_id)
	{
		
        $('#capacityembMountWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getembmonth",{params});
		data.then(function (response) {
			var dg = $('#capacityembMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridEmbMonth(data)
	{
		var dg = $('#capacityembMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var temb_qty=0;
				var tembyesterday_qty=0;
				var ttotal_emb=0;
				var tyet_emb=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					temb_qty+=data.rows[i]['emb_qty'].replace(/,/g,'')*1;
					tembyesterday_qty+=data.rows[i]['embyesterday_qty'].replace(/,/g,'')*1;
					ttotal_emb+=data.rows[i]['total_emb'].replace(/,/g,'')*1;
					tyet_emb+=data.rows[i]['yet_emb'].replace(/,/g,'')*1;
				}
				
				$('#capacityembMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						emb_qty: temb_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						embyesterday_qty: tembyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_emb: ttotal_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_emb: tyet_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	embMonthTgtWindow(company_id)
	{
		
        $('#capacityembMountTgtWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getembmonthtgt",{params});
		data.then(function (response) {
			var dg = $('#capacityembMonthTgtTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridEmbTgtMonth(data)
	{
		var dg = $('#capacityembMonthTgtTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var treq_qty=0;
				var temb_qty=0;
				var tembyesterday_qty=0;
				var ttotal_emb=0;
				var tyet_emb=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					treq_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
					temb_qty+=data.rows[i]['emb_qty'].replace(/,/g,'')*1;
					tembyesterday_qty+=data.rows[i]['embyesterday_qty'].replace(/,/g,'')*1;
					ttotal_emb+=data.rows[i]['total_emb'].replace(/,/g,'')*1;
					tyet_emb+=data.rows[i]['yet_emb'].replace(/,/g,'')*1;
				}
				
				$('#capacityembMonthTgtTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_qty: treq_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						emb_qty: temb_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						embyesterday_qty: tembyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_emb: ttotal_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_emb: tyet_emb.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	aopMonthTgtWindow(company_id)
	{
		
        $('#capacityaopMonthTgtWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getaopmonthtgt",{params});
		data.then(function (response) {
			var dg = $('#capacityaopMonthTgtTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridAopTgtMonth(data)
	{
		var dg = $('#capacityaopMonthTgtTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_plan_cut_qty=0;
				var torder_amount=0;
				var treq_qty=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_plan_cut_qty+=data.rows[i]['order_plan_cut_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					treq_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
				}
				
				$('#capacityaopMonthTgtTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_plan_cut_qty: torder_plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_qty: treq_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	sewingQtyMonthWindow(company_id)
	{
		
        $('#capacitysewingqtyMonthWindow').window('open');
		let params={};
		params.company_id=company_id;
		params.date_from = $('#prodgmtcapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodgmtcapacityachievementFrm  [name=capacity_date_to]').val();
		let data= axios.get(msApp.baseUrl()+"/prodgmtcapacityachievement/getsewingqtymonth",{params});
		data.then(function (response) {
			var dg = $('#capacitysewingqtyMonthTbl');
			dg.datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSewingQtyMonth(data)
	{
		var dg = $('#capacitysewingqtyMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var torder_qty=0;
				var torder_amount=0;
				var tsewing_qty=0;
				var tsewingyesterday_qty=0;
				var ttotal_sewing=0;
				var tyet_sewing=0;
				var tsewing_amount=0;
				for(var i=0; i<data.rows.length; i++){
					torder_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					torder_amount+=data.rows[i]['order_amount'].replace(/,/g,'')*1;
					tsewing_qty+=data.rows[i]['sewing_qty'].replace(/,/g,'')*1;
					tsewingyesterday_qty+=data.rows[i]['sewingyesterday_qty'].replace(/,/g,'')*1;
					ttotal_sewing+=data.rows[i]['total_sewing'].replace(/,/g,'')*1;
					tyet_sewing+=data.rows[i]['yet_sewing'].replace(/,/g,'')*1;
					tsewing_amount+=data.rows[i]['sewing_amount'].replace(/,/g,'')*1;
				}
				
				$('#capacitysewingqtyMonthTbl').datagrid('reloadFooter', [
					{ 
						order_qty: torder_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						order_amount: torder_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewing_qty: tsewing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewingyesterday_qty: tsewingyesterday_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						total_sewing: ttotal_sewing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_sewing: tyet_sewing.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewing_amount: tsewing_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
}	
window.MsProdGmtCapacityAchievement=new MsProdGmtCapacityAchievementController(new MsProdGmtCapacityAchievementModel());
MsProdGmtCapacityAchievement.showGrid({rows :{}});
MsProdGmtCapacityAchievement.showGridCarton({rows :{}});
MsProdGmtCapacityAchievement.showGridCartonMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridCutting({rows :{}});
MsProdGmtCapacityAchievement.showGridCuttingMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridExfactory({rows :{}});
MsProdGmtCapacityAchievement.showGridExfactoryMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridSewingMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridInvoiceMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridScprint({rows :{}});
MsProdGmtCapacityAchievement.showGridScprintMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridScprintTgtMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridEmb({rows :{}});
MsProdGmtCapacityAchievement.showGridEmbMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridEmbTgtMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridAopTgtMonth({rows :{}});
MsProdGmtCapacityAchievement.showGridSewingQtyMonth({rows :{}});