let MsOrderProgressModel = require('./MsOrderProgressModel');
require('./datagrid-filter.js');

class MsOrderProgressController {
	constructor(MsOrderProgressModel)
	{
		this.MsOrderProgressModel = MsOrderProgressModel;
		this.formId='orderprogressFrm';
		this.dataTable='#orderprogressTbl';
		this.route=msApp.baseUrl()+"/orderprogress"
	}
	getParams(){
		let params={};
		params.company_id = $('#orderprogressFrm  [name=company_id]').val();
		params.produced_company_id = $('#orderprogressFrm  [name=produced_company_id]').val();
		params.buyer_id = $('#orderprogressFrm  [name=buyer_id]').val();
		params.style_ref = $('#orderprogressFrm  [name=style_ref]').val();
		params.style_id = $('#orderprogressFrm  [name=style_id]').val();
		params.factory_merchant_id = $('#orderprogressFrm  [name=factory_merchant_id]').val();
		params.date_from = $('#orderprogressFrm  [name=date_from]').val();
		params.date_to = $('#orderprogressFrm  [name=date_to]').val();
		params.order_status = $('#orderprogressFrm  [name=order_status]').val();
		params.receive_date_from = $('#orderprogressFrm  [name=receive_date_from]').val();
		params.receive_date_to = $('#orderprogressFrm  [name=receive_date_to]').val();
		params.sort_by = $('#orderprogressFrm  [name=sort_by]').val();
		return params;
	}
	
	get()
	{
		$('#orderprogressTab').tabs('select',0);
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#orderprogressTbl').datagrid('loadData', response.data);
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
				var tQty=0;
				var tAmout=0;
				var tBookedMinute=0;
				var ttarget_qty=0;
				var tship_qty=0;
				var tship_value=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_value=0;

				var tdyed_yarn_rq=0;
				var tgrey_yarn_issue_qty_for_dye=0;
				var grey_yarn_issue_for_dye_bal=0;
				var tdyed_yarn_rcv_qty=0;
				var tdyed_yarn_bal_qty=0;

				var tyarn_req=0;
				var tpoyarnlc_qty=0;
				var tpoyarnlc_qty_bal=0;
				var yarn_rcv=0;
				var yarn_rcv_bal=0;
				var tinh_yarn_isu_qty=0;
				var tout_yarn_isu_qty=0;
				var tyarn_req_bal=0;
				
				var knit_qty=0;
				var knit_bal=0;

				var batch_qty=0;
				var batch_bal=0;
				var dyeing_qty=0;
				var dyeing_bal=0;
				var fin_fab_req=0;
				var finish_qty=0;
				var finish_bal=0;


				var plan_cut_qty=0;
				var cut_qty=0;
				var cut_bal=0;
				var req_scr_qty=0;
				var snd_scr_qty=0;
				var snd_scr_qty_bal=0;
				var rcv_scr_qty=0;
				var bal_scr_qty=0;
				var sew_line_qty=0;
				var sew_line_bal=0;
				var sew_qty=0;
				var sew_bal=0;

				var iron_qty=0;
				var iron_bal_qty=0;
				var iron_bal=0;
				var poly_qty=0;
				var poly_bal_qty=0;
				var poly_bal=0;

				var car_qty=0;
				var car_bal=0;
				var insp_pass_qty=0;
				var insp_re_check_qty=0;
				var insp_faild_qty=0;
				
				var ci_qty=0;
				var ci_qty_bal=0;
				var ci_amount=0;
				var ci_amount_bal=0;

				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tBookedMinute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
				ttarget_qty+=data.rows[i]['target_qty'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_value+=data.rows[i]['ship_value'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_value+=data.rows[i]['yet_to_ship_value'].replace(/,/g,'')*1;

				tdyed_yarn_rq+=data.rows[i]['dyed_yarn_rq'].replace(/,/g,'')*1;
				tgrey_yarn_issue_qty_for_dye+=data.rows[i]['grey_yarn_issue_qty_for_dye'].replace(/,/g,'')*1;
				grey_yarn_issue_for_dye_bal+=data.rows[i]['grey_yarn_issue_for_dye_bal'].replace(/,/g,'')*1;
				tdyed_yarn_rcv_qty+=data.rows[i]['dyed_yarn_rcv_qty'].replace(/,/g,'')*1;
				tdyed_yarn_bal_qty+=data.rows[i]['dyed_yarn_bal_qty'].replace(/,/g,'')*1;

				tyarn_req+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
				tpoyarnlc_qty+=data.rows[i]['poyarnlc_qty'].replace(/,/g,'')*1;
				tpoyarnlc_qty_bal+=data.rows[i]['poyarnlc_qty_bal'].replace(/,/g,'')*1;
				yarn_rcv+=data.rows[i]['yarn_rcv'].replace(/,/g,'')*1;
				yarn_rcv_bal+=data.rows[i]['yarn_rcv_bal'].replace(/,/g,'')*1;
				tinh_yarn_isu_qty+=data.rows[i]['inh_yarn_isu_qty'].replace(/,/g,'')*1;
				tout_yarn_isu_qty+=data.rows[i]['out_yarn_isu_qty'].replace(/,/g,'')*1;
				tyarn_req_bal+=data.rows[i]['yarn_req_bal'].replace(/,/g,'')*1;
				knit_qty+=data.rows[i]['knit_qty'].replace(/,/g,'')*1;
				knit_bal+=data.rows[i]['knit_bal'].replace(/,/g,'')*1;
				batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
				dyeing_qty+=data.rows[i]['dyeing_qty'].replace(/,/g,'')*1;
				dyeing_bal+=data.rows[i]['dyeing_bal'].replace(/,/g,'')*1;
				fin_fab_req+=data.rows[i]['fin_fab_req'].replace(/,/g,'')*1;
				finish_qty+=data.rows[i]['finish_qty'].replace(/,/g,'')*1;
				finish_bal+=data.rows[i]['finish_bal'].replace(/,/g,'')*1;
				plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
				cut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
				cut_bal+=data.rows[i]['cut_bal'].replace(/,/g,'')*1;
				req_scr_qty+=data.rows[i]['req_scr_qty'].replace(/,/g,'')*1;
				snd_scr_qty+=data.rows[i]['snd_scr_qty'].replace(/,/g,'')*1;
				snd_scr_qty_bal+=data.rows[i]['snd_scr_qty_bal'].replace(/,/g,'')*1;
				rcv_scr_qty+=data.rows[i]['rcv_scr_qty'].replace(/,/g,'')*1;
				bal_scr_qty+=data.rows[i]['bal_scr_qty'].replace(/,/g,'')*1;
				sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
				sew_line_bal+=data.rows[i]['sew_line_bal'].replace(/,/g,'')*1;
				sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
				sew_bal+=data.rows[i]['sew_bal'].replace(/,/g,'')*1;
				iron_qty+=data.rows[i]['iron_qty'].replace(/,/g,'')*1;
				iron_bal_qty+=data.rows[i]['iron_bal_qty'].replace(/,/g,'')*1;
				iron_bal+=data.rows[i]['iron_bal'].replace(/,/g,'')*1;
				poly_qty+=data.rows[i]['poly_qty'].replace(/,/g,'')*1;
				poly_bal_qty+=data.rows[i]['poly_bal_qty'].replace(/,/g,'')*1;
				poly_bal+=data.rows[i]['poly_bal'].replace(/,/g,'')*1;
				car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
				car_bal+=data.rows[i]['car_bal'].replace(/,/g,'')*1;
				insp_pass_qty+=data.rows[i]['insp_pass_qty'].replace(/,/g,'')*1;
				insp_re_check_qty+=data.rows[i]['insp_re_check_qty'].replace(/,/g,'')*1;
				insp_faild_qty+=data.rows[i]['insp_faild_qty'].replace(/,/g,'')*1;
				ci_qty+=data.rows[i]['ci_qty'].replace(/,/g,'')*1;
				ci_qty_bal+=data.rows[i]['ci_qty_bal'].replace(/,/g,'')*1;
				ci_amount+=data.rows[i]['ci_amount'].replace(/,/g,'')*1;
				ci_amount_bal+=data.rows[i]['ci_amount_bal'].replace(/,/g,'')*1;
				}
				let tRate=0;
				if(tQty>0){
				 tRate=tAmout/tQty;	
				}
				
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					booked_minute:tBookedMinute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					target_qty: ttarget_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_value: tship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_value: tyet_to_ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					dyed_yarn_rq: tdyed_yarn_rq.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_yarn_issue_qty_for_dye: tgrey_yarn_issue_qty_for_dye.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_yarn_issue_for_dye_bal: grey_yarn_issue_for_dye_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyed_yarn_rcv_qty: tdyed_yarn_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyed_yarn_bal_qty: tdyed_yarn_bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					yarn_req: tyarn_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty: tpoyarnlc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty_bal: tpoyarnlc_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv: yarn_rcv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_bal: yarn_rcv_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					inh_yarn_isu_qty: tinh_yarn_isu_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					out_yarn_isu_qty: tout_yarn_isu_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_req_bal: tyarn_req_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_qty: knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_bal: knit_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_qty: dyeing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_bal: dyeing_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_fab_req: fin_fab_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_qty: finish_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_bal: finish_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_qty: cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_bal: cut_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					req_scr_qty: req_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					snd_scr_qty: snd_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					snd_scr_qty_bal: snd_scr_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_scr_qty: rcv_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bal_scr_qty: bal_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_line_bal: sew_line_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_bal: sew_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_qty: iron_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_bal_qty: iron_bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_bal: iron_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_qty: poly_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_bal_qty: poly_bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_bal: poly_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_bal: car_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					insp_pass_qty: insp_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					insp_re_check_qty: insp_re_check_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					insp_faild_qty: insp_faild_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty: ci_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_bal: ci_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount: ci_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount_bal: ci_amount_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#orderprogressTbl').datagrid('loadData', response.data);
			msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatteamleader(value,row){
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.teamleaderWindow('+row.teamleader_id+')">'+row.team_name+'</a>';
	}

	teamleaderWindow(teamleader_id){
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getdlmerchant?user_id="+teamleader_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatdlmerchant(value,row){
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.dlmerchantWindow('+row.user_id+')">'+row.team_member_name+'</a>';
	}

	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDlmct(data)
	{
		var dg = $('#dealmctinfoTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}

	formatbuyingAgent(value,row){
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
	}

	buyingAgentWindow(buying_agent_id){
		
		let agent= axios.get(msApp.baseUrl()+"/orderprogress/getbuyhouse?buyer_id="+buying_agent_id);
		agent.then(function (response) {
			$('#buyagentTbl').datagrid('loadData', response.data);
			$('#buyagentwindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridBAgent(data)
	{
		var dg = $('#buyagentTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:false,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}

	formatopfiles(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.opfileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}

	opfileWindow(style_id)
	{
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getopfile?style_id="+style_id);
		data.then(function (response) {
			$('#opfilesrcTbl').datagrid('loadData', response.data);
			$('#opfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridOpFileSrc(data)
	{
		var dg = $('#opfilesrcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'
		});
		dg.datagrid('loadData', data);
	}

	formatShowOpFile(value,row)
	{
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	formatlcsc(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.lcscWindow('+row.sale_order_id+')">'+row.lc_sc_no+'</a>';	
	}

	lcscWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getlcsc?sale_order_id="+sale_order_id);
		data.then(function (response) {
			$('#oplcscTbl').datagrid('loadData', response.data);
			$('#oplcscwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridLcSc(data)
	{
		var dg = $('#oplcscTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		});
		dg.datagrid('loadData', data);
	}

	formatorderqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.orderqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	orderqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getorderqty",{params});
		data.then(function (response) {
			$('#oporderqtyTbl').datagrid('loadData', response.data);
			$('#oporderqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridOrderQty(data)
	{
		var dg = $('#oporderqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					var rate=0;
					var amount=0;
					var smv=0;
					var booked_minute=0;
					
					for(var i=0; i<data.rows.length; i++){
						qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
						booked_minute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
					}

					if(qty){
						rate=amount/qty;
						smv=booked_minute/qty;
					}

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						smv: smv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						booked_minute: booked_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatimage(value,row)
	{
	return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOrderProgress.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	imageWindow(flie_src)
	{
		var output = document.getElementById('orderProgressImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#orderProgressImageWindow').window('open');
	}
	
	formadyedyarnrq(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.dyedyarnrqWindow('+row.sale_order_id+')">'+row.dyed_yarn_rq+'</a>';	
	}

	dyedyarnrqWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getdyedyarnrq",{params});
		data.then(function (response) {
			$('#opdyedyarnrqTbl').datagrid('loadData', response.data);
			$('#opdyedyarnrqwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDyedYarnRQ(data)
	{
		var dg = $('#opdyedyarnrqTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var tQty=0;
					
					for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['dyed_yarn_rq'].replace(/,/g,'')*1;
					}
					$(this).datagrid('reloadFooter', [
					{ 
						dyed_yarn_rq: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatgreyyarntodye(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.greyyarntodyeWindow('+row.sale_order_id+')">'+row.grey_yarn_issue_qty_for_dye+'</a>';	
	}

	greyyarntodyeWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getgreyyarntodye",{params});
		data.then(function (response) {
			//$('#opgreyyarntodyeTbl').datagrid('loadData', response.data.issue);
			//$('#opdyedyarnrcvTbl').datagrid('loadData', response.data.receive);
			$('#opgreyyarntodyewindowcontainer').html(response.data);
			$('#opgreyyarntodyewindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridGreyYarntoDye(data)
	{
		var dg = $('#opgreyyarntodyeTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['grey_yarn_issue_qty_for_dye'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					grey_yarn_issue_qty_for_dye: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatdyedyarnrcv(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.dyedyarnrcvWindow('+row.sale_order_id+')">'+row.dyed_yarn_rcv_qty+'</a>';	
	}

	dyedyarnrcvWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getdyedyarnrcv",{params});
		data.then(function (response) {
			$('#opdyedyarnrcvTbl').datagrid('loadData', response.data);
			$('#opdyedyarnrcvwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDyedYarnRcv(data)
	{
		var dg = $('#opdyedyarnrcvTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['dyed_yarn_rcv_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					dyed_yarn_rcv_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatyarnrq(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.yarnrqWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	yarnrqWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getyarnrq",{params});
		data.then(function (response) {
			$('#opyarnrqTbl').datagrid('loadData', response.data);
			$('#opyarnrqwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridYarnRq(data)
	{
		var dg = $('#opyarnrqTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var yarn_req=0;
				var rate=0;
				var req_amount=0;
				var rcv_qty=0;
				var rcv_rate=0;
				var rcv_amount=0;
				var pending_qty=0;
				var pending_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
				yarn_req+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
				req_amount+=data.rows[i]['req_amount'].replace(/,/g,'')*1;
				rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				rcv_amount+=data.rows[i]['rcv_amount'].replace(/,/g,'')*1;
				pending_qty+=data.rows[i]['pending_qty'].replace(/,/g,'')*1;
				pending_amount+=data.rows[i]['pending_amount'].replace(/,/g,'')*1;
				}
				if(yarn_req){
					rate=req_amount/yarn_req;
				}
				if(rcv_qty){
					rcv_rate=rcv_amount/rcv_qty;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					yarn_req: yarn_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					req_amount: req_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rate: rcv_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_amount: rcv_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_qty: pending_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_amount: pending_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatyarnisuinh(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.yarnisuinhWindow('+row.sale_order_id+')">'+row.inh_yarn_isu_qty+'</a>';	
	}

	yarnisuinhWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getyarnisuinh",{params});
		data.then(function (response) {
			//$('#opyarnisuinhTbl').datagrid('loadData', response.data);
			$('#opyarnisuinhwindowcontainer').html(response.data);
			$('#opyarnisuinhwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*showGridYarnIsuInh(data)
	{
		var dg = $('#opyarnisuinhTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['inh_yarn_isu_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					inh_yarn_isu_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}*/

	formatyarnisuout(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.yarnisuoutWindow('+row.sale_order_id+')">'+row.out_yarn_isu_qty+'</a>';	
	}

	yarnisuoutWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getyarnisuout",{params});
		data.then(function (response) {
			//$('#opyarnisuoutTbl').datagrid('loadData', response.data);
			$('#opyarnisuoutwindowcontainer').html(response.data);
			$('#opyarnisuoutwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*showGridYarnIsuOut(data)
	{
		var dg = $('#opyarnisuoutTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var tQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['out_yarn_isu_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					out_yarn_isu_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}*/

	formatknit(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.knitWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	knitWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getknit",{params});
		data.then(function (response) {
			$('#opknitTbl').datagrid('loadData', response.data);
			$('#opknitwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridKnit(data)
	{
		var dg = $('#opknitTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
				var req_qty=0;
				var qc_pass_qty=0;
				var qc_pass_qty_pcs=0;
				var pending_knit=0;
				
				for(var i=0; i<data.rows.length; i++){
				req_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
				qc_pass_qty+=data.rows[i]['qc_pass_qty'].replace(/,/g,'')*1;
				qc_pass_qty_pcs+=data.rows[i]['qc_pass_qty_pcs'].replace(/,/g,'')*1;
				pending_knit+=data.rows[i]['pending_knit'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					req_qty: req_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					qc_pass_qty: qc_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					qc_pass_qty_pcs: qc_pass_qty_pcs.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_knit: pending_knit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatcutqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.cutqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	cutqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getcutqty",{params});
		data.then(function (response) {
			$('#opcutqtyTbl').datagrid('loadData', response.data);
			$('#opcutqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCutQty(data)
	{
		var dg = $('#opcutqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					var plan_cut_qty=0;
					var cut_qty=0;
					var cut_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
					cut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
					cut_pending+=data.rows[i]['cut_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cut_qty: cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cut_pending: cut_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatscrqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.scrqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	scrqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getscrqty",{params});
		data.then(function (response) {
			$('#opscrqtyTbl').datagrid('loadData', response.data);
			$('#opscrqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridScrQty(data)
	{
		var dg = $('#opscrqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					//var plan_cut_qty=0;
					var cut_qty=0;
					//var cut_pending=0;
					//var req_scr_qty=0;
					var snd_scr_qty=0;
					var rcv_scr_qty=0;
					var scr_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					//plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
					cut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
					//cut_pending+=data.rows[i]['cut_pending'].replace(/,/g,'')*1;
					//req_scr_qty+=data.rows[i]['req_scr_qty'].replace(/,/g,'')*1;
					snd_scr_qty+=data.rows[i]['snd_scr_qty'].replace(/,/g,'')*1;
					rcv_scr_qty+=data.rows[i]['rcv_scr_qty'].replace(/,/g,'')*1;
					scr_pending+=data.rows[i]['scr_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cut_qty: cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//cut_pending: cut_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//req_scr_qty: req_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						snd_scr_qty: snd_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rcv_scr_qty: rcv_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						scr_pending: scr_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatsewqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.sewqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	sewqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getsewqty",{params});
		data.then(function (response) {
			$('#opsewqtyTbl').datagrid('loadData', response.data);
			$('#opsewqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSewQty(data)
	{
		var dg = $('#opsewqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					//var plan_cut_qty=0;
					var sew_line_qty=0;
					//var cut_pending=0;
					//var req_scr_qty=0;
					var sew_qty=0;
					var sewwip=0;
					var sew_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					//plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
					sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
					//cut_pending+=data.rows[i]['cut_pending'].replace(/,/g,'')*1;
					//req_scr_qty+=data.rows[i]['req_scr_qty'].replace(/,/g,'')*1;
					sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					sewwip+=data.rows[i]['sewwip'].replace(/,/g,'')*1;
					sew_pending+=data.rows[i]['sew_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//cut_pending: cut_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//req_scr_qty: req_scr_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sewwip: sewwip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_pending: sew_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatcarqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.carqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	carqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getcarqty",{params});
		data.then(function (response) {
			$('#opcarqtyTbl').datagrid('loadData', response.data);
			$('#opcarqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridCarQty(data)
	{
		var dg = $('#opcarqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					//var plan_cut_qty=0;
					//var sew_line_qty=0;
					//var cut_pending=0;
					var sew_qty=0;
					var car_qty=0;
					var carwip=0;
					var car_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					//plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
					//sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
					//cut_pending+=data.rows[i]['cut_pending'].replace(/,/g,'')*1;
					sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
					carwip+=data.rows[i]['carwip'].replace(/,/g,'')*1;
					car_pending+=data.rows[i]['car_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//cut_pending: cut_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						carwip: carwip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						car_pending: car_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	formatinspqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.inspqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	inspqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getinspqty",{params});
		data.then(function (response) {
			$('#opinspqtyTbl').datagrid('loadData', response.data);
			$('#opinspqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridInspQty(data)
	{
		var dg = $('#opinspqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					//var plan_cut_qty=0;
					//var sew_line_qty=0;
					//var cut_pending=0;
					//var sew_qty=0;
					var car_qty=0;
					var insp_pass_qty=0;
					var insp_re_check_qty=0;
					var insp_faild_qty=0;
					var insp_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					//plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
					//sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
					//cut_pending+=data.rows[i]['cut_pending'].replace(/,/g,'')*1;
					//sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
					car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
					insp_pass_qty+=data.rows[i]['insp_pass_qty'].replace(/,/g,'')*1;
					insp_re_check_qty+=data.rows[i]['insp_re_check_qty'].replace(/,/g,'')*1;
					insp_faild_qty+=data.rows[i]['insp_faild_qty'].replace(/,/g,'')*1;
					insp_pending+=data.rows[i]['insp_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//cut_pending: cut_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						insp_pass_qty: insp_pass_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						insp_re_check_qty: insp_re_check_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						insp_faild_qty: insp_faild_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						insp_pending: insp_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatexfqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.exfqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	exfqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id;
		let data= axios.get(msApp.baseUrl()+"/orderprogress/getexfqty",{params});
		data.then(function (response) {
			$('#opexfqtyTbl').datagrid('loadData', response.data);
			$('#opexfqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridExfQty(data)
	{
		var dg = $('#opexfqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					var car_qty=0;
					var exf_qty=0;
					var exf_pending=0;
					
					for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
					exf_qty+=data.rows[i]['exf_qty'].replace(/,/g,'')*1;
					exf_pending+=data.rows[i]['exf_pending'].replace(/,/g,'')*1;
					}

					

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exf_qty: exf_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						exf_pending: exf_pending.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	greyyarnissuefordyebal(value,row,index)
	{
		if (row.grey_yarn_issue_for_dye_bal*1 < 0 ){
		    return 'color:red;';
		}
	}

	yarnrcvbal(value,row,index)
	{
		if (parseFloat(row.yarn_rcv_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	knitbal(value,row,index)
	{
		if (parseFloat(row.knit_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	batchbal(value,row,index)
	{
		if (parseFloat(row.batch_bal) < 0 ){
		    return 'color:red;';
	    }
	}
	dyeingbal(value,row,index)
	{
		if (parseFloat(row.dyeing_bal) < 0 ){
		    return 'color:red;';
	    }
	}
	finishbal(value,row,index)
	{
		if (parseFloat(row.finish_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	cutbal(value,row,index)
	{
		if (parseFloat(row.cut_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	sndscrqtybal(value,row,index)
	{
		if (parseFloat(row.snd_scr_qty_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	balscrqty(value,row,index)
	{
		if (parseFloat(row.bal_scr_qty) < 0 ){
		    return 'color:red;';
	    }
	}

	sewlinebal(value,row,index)
	{
		if (parseFloat(row.sew_line_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	sewbal(value,row,index)
	{
		if (parseFloat(row.sew_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	ironbal(value,row,index)
	{
		if (parseFloat(row.iron_bal) < 0 ){
		    return 'color:red;';
	    }
	}
	polybal(value,row,index)
	{
		if (parseFloat(row.poly_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	carbal(value,row,index)
	{
		if (parseFloat(row.car_bal) < 0 ){
		    return 'color:red;';
	    }
	}

	yettoshipqty(value,row,index)
	{
		if (parseFloat(row.yet_to_ship_qty) < 0 ){
		    return 'color:red;';
	    }
	}
	ciqtybal(value,row,index)
	{
		if (parseFloat(row.ci_qty_bal) < 0 ){
		    return 'color:red;';
	    }
	}





	/*getsummary()
	{
        let params=this.getParams();
		let d= axios.get(this.route+'/getsummarydata',{params})
		.then(function (response) {
			$('#orderprogresssummaryTbl').datagrid('loadData', response.data);
			$('#summarywindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSummary(data)
	{
		var dg = $('#orderprogresssummaryTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			columns:[[
			{field:'buyer_name',title:'Buyer',width:250,halign:'center'},
			{field:'company_name',title:'Producing <br/>Company',width:200,halign:'center'},
			{field:'qty',title:'Order Qty',width:100,halign:'center',align:'right',formatter:MsOrderProgress.formatdetail},
			{field:'amount',title:'Selling <br/>Value',width:80,halign:'center',align:'right'},
			{field:'ship_qty',title:'Ship Out <br/>Qty',width:60,halign:'center',align:'right'},
			{field:'ship_amount',title:'Ship Out <br/>Value',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_qty',title:'Yet to <br/>Ship Qty',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_amount',title:'Yet to <br/>Ship Value',width:80,halign:'center',align:'right'},
			{field:'pending_ship_qty',title:'Pending <br/>Shipment Qty',width:80,halign:'center',align:'right'},
			{field:'pending_ship_amount',title:'Pending <br/>Shipment Value',width:100,halign:'center',align:'right'},
			]],
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tship_qty=0;
				var tship_amount=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_amount=0;
				var tpending_ship_qty=0;
				var tpending_ship_amount=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_amount+=data.rows[i]['ship_amount'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_amount+=data.rows[i]['yet_to_ship_amount'].replace(/,/g,'')*1;
				tpending_ship_qty+=data.rows[i]['pending_ship_qty'].replace(/,/g,'')*1;
				tpending_ship_amount+=data.rows[i]['pending_ship_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_amount: tship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_amount: tyet_to_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_qty: tpending_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_amount: tpending_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getBuyerSummary()
	{
        let params=this.getParams();
		let d= axios.get(this.route+'/getsummarybuyerdata',{params})
		.then(function (response) {
			$('#summarybuyerTbl').datagrid('loadData', response.data);
			$('#summarybuyerwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBuyerSummary(data)
	{
		var dg = $('#summarybuyerTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			columns:[[
			{field:'buyer_name',title:'Buyer',width:250,halign:'center'},
			{field:'qty',title:'Order Qty',width:100,halign:'center',align:'right',formatter:MsOrderProgress.formatdetail},
			{field:'amount',title:'Selling <br/>Value',width:80,halign:'center',align:'right'},
			{field:'ship_qty',title:'Ship Out <br/>Qty',width:60,halign:'center',align:'right'},
			{field:'ship_amount',title:'Ship Out <br/>Value',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_qty',title:'Yet to <br/>Ship Qty',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_amount',title:'Yet to <br/>Ship Value',width:80,halign:'center',align:'right'},
			{field:'pending_ship_qty',title:'Pending <br/>Shipment Qty',width:80,halign:'center',align:'right'},
			{field:'pending_ship_amount',title:'Pending <br/>Shipment Value',width:100,halign:'center',align:'right'},
			]],
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tship_qty=0;
				var tship_amount=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_amount=0;
				var tpending_ship_qty=0;
				var tpending_ship_amount=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_amount+=data.rows[i]['ship_amount'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_amount+=data.rows[i]['yet_to_ship_amount'].replace(/,/g,'')*1;
				tpending_ship_qty+=data.rows[i]['pending_ship_qty'].replace(/,/g,'')*1;
				tpending_ship_amount+=data.rows[i]['pending_ship_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_amount: tship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_amount: tyet_to_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_qty: tpending_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_amount: tpending_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getCompanySummary()
	{
        let params=this.getParams();
		let d= axios.get(this.route+'/getsummarycompanydata',{params})
		.then(function (response) {
			$('#summarycompanyTbl').datagrid('loadData', response.data);
			$('#summarycompanywindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridCompanySummary(data)
	{
		var dg = $('#summarycompanyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			columns:[[
			{field:'company_name',title:'Producing <br/>Company',width:200,halign:'center'},
			{field:'qty',title:'Order Qty',width:100,halign:'center',align:'right',formatter:MsOrderProgress.formatdetail},
			{field:'amount',title:'Selling <br/>Value',width:80,halign:'center',align:'right'},
			{field:'ship_qty',title:'Ship Out <br/>Qty',width:60,halign:'center',align:'right'},
			{field:'ship_amount',title:'Ship Out <br/>Value',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_qty',title:'Yet to <br/>Ship Qty',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_amount',title:'Yet to <br/>Ship Value',width:80,halign:'center',align:'right'},
			{field:'pending_ship_qty',title:'Pending <br/>Shipment Qty',width:80,halign:'center',align:'right'},
			{field:'pending_ship_amount',title:'Pending <br/>Shipment Value',width:100,halign:'center',align:'right'},
			]],
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tship_qty=0;
				var tship_amount=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_amount=0;
				var tpending_ship_qty=0;
				var tpending_ship_amount=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_amount+=data.rows[i]['ship_amount'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_amount+=data.rows[i]['yet_to_ship_amount'].replace(/,/g,'')*1;
				tpending_ship_qty+=data.rows[i]['pending_ship_qty'].replace(/,/g,'')*1;
				tpending_ship_amount+=data.rows[i]['pending_ship_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_amount: tship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_amount: tyet_to_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_qty: tpending_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_amount: tpending_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getbuyerbuyinghousesummary()
	{
        let params=this.getParams();
		let d= axios.get(this.route+'/getsummarybuyerbuyinghousedata',{params})
		.then(function (response) {
			$('#summarybuyerBuyingHouseTbl').datagrid('loadData', response.data);
			$('#summarybuyerbuyinghousewindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBuyerBuyinghouseSummary(data)
	{
		var dg = $('#summarybuyerBuyingHouseTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			columns:[[
			{field:'buyer_name',title:'Buyer',width:250,halign:'center'},
			{field:'buying_agent_name',title:'Buying <br/>House',width:200,halign:'center'},
			{field:'qty',title:'Order Qty',width:100,halign:'center',align:'right',formatter:MsOrderProgress.formatdetail},
			{field:'amount',title:'Selling <br/>Value',width:80,halign:'center',align:'right'},
			{field:'ship_qty',title:'Ship Out <br/>Qty',width:60,halign:'center',align:'right'},
			{field:'ship_amount',title:'Ship Out <br/>Value',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_qty',title:'Yet to <br/>Ship Qty',width:80,halign:'center',align:'right'},
			{field:'yet_to_ship_amount',title:'Yet to <br/>Ship Value',width:80,halign:'center',align:'right'},
			{field:'pending_ship_qty',title:'Pending <br/>Shipment Qty',width:80,halign:'center',align:'right'},
			{field:'pending_ship_amount',title:'Pending <br/>Shipment Value',width:100,halign:'center',align:'right'},
			]],
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tship_qty=0;
				var tship_amount=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_amount=0;
				var tpending_ship_qty=0;
				var tpending_ship_amount=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_amount+=data.rows[i]['ship_amount'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_amount+=data.rows[i]['yet_to_ship_amount'].replace(/,/g,'')*1;
				tpending_ship_qty+=data.rows[i]['pending_ship_qty'].replace(/,/g,'')*1;
				tpending_ship_amount+=data.rows[i]['pending_ship_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_amount: tship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_amount: tyet_to_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_qty: tpending_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_amount: tpending_ship_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatdetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderProgress.detailWindow('+row.buyer_id+','+row.produced_company_id+')">'+value+'</a>';	
	}

	detailWindow(buyer_id,produced_company_id)
	{
		
		let params=this.getParams();
		params.buyer_id=buyer_id;
		params.produced_company_id=produced_company_id;
		let d= axios.get(this.route+'/getdetaildata',{params})
		.then(function (response) {
			$('#orderprogresdetailTbl').datagrid('loadData', response.data);
			$('#detailwindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDetail(data)
	{
		var dg = $('#orderprogresdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tship_qty=0;
				var tship_value=0;
				var tyet_to_ship_qty=0;
				var tyet_to_ship_value=0;
				var tpending_ship_qty=0;
				var tpending_ship_value=0;

				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tship_value+=data.rows[i]['ship_value'].replace(/,/g,'')*1;
				tyet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				tyet_to_ship_value+=data.rows[i]['yet_to_ship_value'].replace(/,/g,'')*1;
				tpending_ship_qty+=data.rows[i]['pending_ship_qty'].replace(/,/g,'')*1;
				tpending_ship_value+=data.rows[i]['pending_ship_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_qty: tship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_value: tship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: tyet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_value: tyet_to_ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_qty: tpending_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_ship_value: tpending_ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}*/
	getCom()
	{
		$('#orderprogressTab').tabs('select',1);
		let params=this.getParams();
		let d= axios.get(this.route+'/getdatacom',{params})
		.then(function (response) {
			$('#orderprogresscomTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridCom(data)
	{
		var dg = $('#orderprogresscomTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			frozenColumns:[[
				{
				title:'Order', 
				width:460,
				halign:'center',
				colspan:6,
				id:'opordersumcolcom',
				field:'fgrtd1',
				},
			],
			[{
				field:'company_code',
				title:'Pro. Company', 
				width:60,
				halign:'center',
				},
				{
				field:'qty',
				title:'Order Qty (Pcs)', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'rate',
				title:'Rate', 
				width:60,
				halign:'center',
				align:'right',
				},
				{
				field:'amount',
				title:'Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'smv',
				title:'SMV', 
				width:60,
				halign:'center',
				align:'right',
				},
				{
				field:'booked_minute',
				title:'Booked Minute', 
				width:100,
				halign:'center',
				align:'right',
				},
				]],
			columns:[[
					{
					title:'Yarn Summary', 
					width:900,
					halign:'center',
					colspan:9,
					id:'opyarnsumcolcom',
					},
					{
					title:'Knit Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opknitsumcolcom',
					},
					{
					title:'Dyeing & Finishing Summary', 
					width:1100,
					halign:'center',
					colspan:11,
					id:'opdyeingsumcolcom',
					},
					{
					title:'Cutting Summary', 
					width:700,
					halign:'center',
					colspan:7,
					id:'opcutsumcolcom',
					},
					{
					title:'Sewing Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opsewsumcolcom',
					},
					{
					title:'Iron Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opironsumcolcom',
					},
					{
					title:'Poly Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'oppolysumcolcom',
					},
					{
					title:'Finishing Summary', 
					width:400,
					halign:'center',
					colspan:4,
					id:'opfinsumcolcom',
					},
					{
					title:'Shipment Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opshipsumcolcom',
					},
					{
					title:'Invoice Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opinvsumcolcom',
					},
			    ],
			    [
				{
				field:'yarn_req',
				title:'Yarn Required', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poyarnlc_qty',
				title:'Yarn Lc Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poyarnlc_qty_bal',
				title:'Yarn Lc Bal', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_rcv',
				title:'Yarn Rcv.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_rcv_bal',
				title:'Yarn Rcv. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.yarnrcvbal
				},
				{
				field:'yarn_rcv_per',
				title:'Yarn Rcv. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_cons',
				title:'Avg. Cons/DZN', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_issue_to_knit',
				title:'Issue To Knit', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_req_bal',
				title:'Issue Balance', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'prod_knit_req',
				title:'Req', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'prod_knit_qty',
				title:'Done', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_prod_bal',
				title:'Bal.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_prod_per',
				title:'Knit %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_qty',
				title:'QC  Done', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_bal',
				title:'QC Pending', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.knitbal
				},
				{
				field:'prod_dyeing_req',
				title:'Req', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'batch_qty',
				title:'Batch Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'batch_bal',
				title:'Batch Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.batchbal,
				},
				{
				field:'batch_per',
				title:'Batch %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'dyeing_qty',
				title:'Dyeing Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'dyeing_bal',
				title:'Dyeing Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.dyeingbal,
				},
				{
				field:'dyeing_per',
				title:'Dyeing %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'fin_fab_req',
				title:'Fin. Fab Req.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'finish_qty',
				title:'Fin. Fab Qty.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'finish_bal',
				title:'Fin. Fab. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.finishbal,
				},
				{
				field:'finish_per',
				title:'Fin. Fab. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'plan_cut_qty',
				title:'Req. Cut Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'cut_qty',
				title:'Cut Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				
				{
				field:'cut_bal',
				title:'Cut Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.cutbal
				},
				{
				field:'cut_per',
				title:'Cut %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_line_qty',
				title:'Line Input', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_line_bal',
				title:'Line Input Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.sewlinebal
				},
				{
				field:'cut_wip',
				title:'Cut WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_qty',
				title:'Sew Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_bal',
				title:'Sew Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.sewbal
				},
				{
				field:'sew_per',
				title:'Sew %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_wip',
				title:'Sew WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_qty',
				title:'Iron Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_bal',
				title:'Iron Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.ironbal
				},
				{
				field:'iron_per',
				title:'Iron %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_wip',
				title:'Iron WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_qty',
				title:'Poly Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_bal',
				title:'Poly Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.polybal
				},
				{
				field:'poly_per',
				title:'Poly %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_wip',
				title:'Poly WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_qty',
				title:'Fin. Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_bal',
				title:'Fin. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.carbal
				},
				{
				field:'car_per',
				title:'Fin. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_wip',
				title:'Fin. WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_qty',
				title:'Ship Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yet_to_ship_qty',
				title:'Ship Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.yettoshipqty
				},
				{
				field:'ship_per',
				title:'Ship %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_wip',
				title:'Ship WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_value',
				title:'Ship Value', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yet_to_ship_value',
				title:'Ship Bal. Value', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty',
				title:'Invoice Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty_bal',
				title:'Invoice Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.ciqtybal
				},
				{
				field:'ci_qty_per',
				title:'Invoice %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty_wip',
				title:'Invoice WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_amount',
				title:'Invoice Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_amount_bal',
				title:'Invoice Bal. Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
			]],
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				var booked_minute=0;
				

				/*var tdyed_yarn_rq=0;
				var tgrey_yarn_issue_qty_for_dye=0;
				var grey_yarn_issue_for_dye_bal=0;
				var tdyed_yarn_rcv_qty=0;
				var tdyed_yarn_bal_qty=0;*/

				var yarn_req=0;
				var poyarnlc_qty=0;
				var poyarnlc_qty_bal=0;
				var yarn_rcv=0;
				var yarn_rcv_bal=0;
				var yarn_rcv_per=0;
				var yarn_cons=0;
				var yarn_issue_to_knit=0;
				//var tinh_yarn_isu_qty=0;
				//var tout_yarn_isu_qty=0;
				var yarn_req_bal=0;
				
				var prod_knit_req=0;
				var prod_knit_qty=0;
				var knit_prod_bal=0;
				var knit_prod_per=0;
				var knit_qty=0;
				var knit_bal=0;

				var prod_dyeing_req=0;
				var batch_qty=0;
				var batch_bal=0;
				var batch_per=0;

				var dyeing_qty=0;
				var dyeing_bal=0;
				var dyeing_per=0;

				var fin_fab_req=0;
				var finish_qty=0;
				var finish_bal=0;
				var finish_per=0;


				var plan_cut_qty=0;
				var cut_qty=0;
				var cut_bal=0;
				var cut_per=0;
				var sew_line_qty=0;
				var sew_line_bal=0;
				var cut_wip=0;

				var sew_qty=0;
				var sew_bal=0;
				var sew_per=0;
				var alter_qty=0;
				var reject_qty=0;
				var sew_wip=0;

				var iron_qty=0;
				var iron_bal=0;
				var iron_per=0;
				var iron_alter_qty=0;
				var iron_reject_qty=0;
				var iron_wip=0;

				var poly_qty=0;
				var poly_bal=0;
				var poly_per=0;
				var poly_alter_qty=0;
				var poly_reject_qty=0;
				var poly_wip=0;

				var car_qty=0;
				var car_bal=0;
				var car_per=0;
				var car_wip=0;
				

				var ship_qty=0;
				var yet_to_ship_qty=0;
				var ship_per=0;
				var ship_wip=0;
				var ship_value=0;
				var yet_to_ship_value=0;

				var ci_qty=0;
				var ci_qty_bal=0;
				var ci_qty_per=0;
				var ci_qty_wip=0;
				var ci_amount=0;
				var ci_amount_bal=0;

				for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				booked_minute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
				

				

				yarn_req+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
				poyarnlc_qty+=data.rows[i]['poyarnlc_qty'].replace(/,/g,'')*1;
				poyarnlc_qty_bal+=data.rows[i]['poyarnlc_qty_bal'].replace(/,/g,'')*1;
				yarn_rcv+=data.rows[i]['yarn_rcv'].replace(/,/g,'')*1;
				yarn_rcv_bal+=data.rows[i]['yarn_rcv_bal'].replace(/,/g,'')*1;
				yarn_issue_to_knit+=data.rows[i]['yarn_issue_to_knit'].replace(/,/g,'')*1;
				yarn_req_bal+=data.rows[i]['yarn_req_bal'].replace(/,/g,'')*1;
				//yarn_rcv_per+=data.rows[i]['yarn_rcv_per'].replace(/,/g,'')*1;
				prod_knit_req+=data.rows[i]['prod_knit_req'].replace(/,/g,'')*1;
				prod_knit_qty+=data.rows[i]['prod_knit_qty'].replace(/,/g,'')*1;
				knit_prod_bal+=data.rows[i]['knit_prod_bal'].replace(/,/g,'')*1;
				knit_qty+=data.rows[i]['knit_qty'].replace(/,/g,'')*1;
				knit_bal+=data.rows[i]['knit_bal'].replace(/,/g,'')*1;
				
				prod_dyeing_req+=data.rows[i]['prod_dyeing_req'].replace(/,/g,'')*1;
				batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
				
				dyeing_qty+=data.rows[i]['dyeing_qty'].replace(/,/g,'')*1;
				dyeing_bal+=data.rows[i]['dyeing_bal'].replace(/,/g,'')*1;
				
				fin_fab_req+=data.rows[i]['fin_fab_req'].replace(/,/g,'')*1;
				finish_qty+=data.rows[i]['finish_qty'].replace(/,/g,'')*1;
				finish_bal+=data.rows[i]['finish_bal'].replace(/,/g,'')*1;


				plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
				cut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
				cut_bal+=data.rows[i]['cut_bal'].replace(/,/g,'')*1;
				cut_wip+=data.rows[i]['cut_wip'].replace(/,/g,'')*1;
				sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
				sew_line_bal+=data.rows[i]['sew_line_bal'].replace(/,/g,'')*1;
				cut_wip+=data.rows[i]['cut_wip'].replace(/,/g,'')*1;

				sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
				sew_bal+=data.rows[i]['sew_bal'].replace(/,/g,'')*1;
				alter_qty+=data.rows[i]['alter_qty'].replace(/,/g,'')*1;
				reject_qty+=data.rows[i]['reject_qty'].replace(/,/g,'')*1;
				sew_wip+=data.rows[i]['sew_wip'].replace(/,/g,'')*1;

				iron_qty+=data.rows[i]['iron_qty'].replace(/,/g,'')*1;
				iron_bal+=data.rows[i]['iron_bal'].replace(/,/g,'')*1;
				iron_alter_qty+=data.rows[i]['iron_alter_qty'].replace(/,/g,'')*1;
				iron_reject_qty+=data.rows[i]['iron_reject_qty'].replace(/,/g,'')*1;
				iron_wip+=data.rows[i]['iron_wip'].replace(/,/g,'')*1;

				poly_qty+=data.rows[i]['poly_qty'].replace(/,/g,'')*1;
				poly_bal+=data.rows[i]['poly_bal'].replace(/,/g,'')*1;
				poly_alter_qty+=data.rows[i]['poly_alter_qty'].replace(/,/g,'')*1;
				poly_reject_qty+=data.rows[i]['poly_reject_qty'].replace(/,/g,'')*1;
				poly_wip+=data.rows[i]['poly_wip'].replace(/,/g,'')*1;

				car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
				car_bal+=data.rows[i]['car_bal'].replace(/,/g,'')*1;
				car_wip+=data.rows[i]['car_wip'].replace(/,/g,'')*1;
				
				ship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				yet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				ship_wip+=data.rows[i]['ship_wip'].replace(/,/g,'')*1;
				ship_value+=data.rows[i]['ship_value'].replace(/,/g,'')*1;
				yet_to_ship_value+=data.rows[i]['yet_to_ship_value'].replace(/,/g,'')*1;

				ci_qty+=data.rows[i]['ci_qty'].replace(/,/g,'')*1;
				ci_qty_bal+=data.rows[i]['ci_qty_bal'].replace(/,/g,'')*1;
				ci_qty_wip+=data.rows[i]['ci_qty_wip'].replace(/,/g,'')*1;
				ci_amount+=data.rows[i]['ci_amount'].replace(/,/g,'')*1;
				ci_amount_bal+=data.rows[i]['ci_amount_bal'].replace(/,/g,'')*1;
				}

				if(yarn_req){
					yarn_rcv_per=(yarn_rcv/yarn_req)*100;
				}
				if(qty){
					yarn_cons=(yarn_req/qty)*12;
				}
				if(yarn_req){
					knit_prod_per=(prod_knit_qty/yarn_req)*100;
				}
				if(yarn_req){
					batch_per=(batch_qty/yarn_req)*100;
				}
				if(yarn_req){
					dyeing_per=(dyeing_qty/yarn_req)*100;
				}
				if(fin_fab_req){
					finish_per=(finish_qty/fin_fab_req)*100;
				}
				
				if(plan_cut_qty){
					cut_per=(cut_qty/plan_cut_qty)*100;
				}
				if(qty){
					sew_per=(sew_qty/qty)*100;
				}
				if(qty){
					iron_per=(iron_qty/qty)*100;
				}
				if(qty){
					poly_per=(poly_qty/qty)*100;
				}
				if(sew_qty){
					car_per=(car_qty/sew_qty)*100;
				}
				if(qty){
					ship_per=(ship_qty/qty)*100;
				}
				if(ship_qty){
					ci_qty_per=(ci_qty/ship_qty)*100;
				}

				

				
				

				$(this).datagrid('reloadFooter', [
				{ 
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					booked_minute:booked_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					yarn_req: yarn_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty: poyarnlc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty_bal: poyarnlc_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv: yarn_rcv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_bal: yarn_rcv_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_per: yarn_rcv_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_cons: yarn_cons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_issue_to_knit: yarn_issue_to_knit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_req_bal: yarn_req_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_knit_req: prod_knit_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_knit_qty: prod_knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_prod_bal: knit_prod_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_prod_per: knit_prod_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_qty: knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_bal: knit_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					prod_dyeing_req: prod_dyeing_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_per: batch_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					dyeing_qty: dyeing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_bal: dyeing_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_per: dyeing_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					fin_fab_req: fin_fab_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_qty: finish_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_bal: finish_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_per: finish_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_qty: cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_bal: cut_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_per: cut_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_line_bal: sew_line_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				    cut_wip: cut_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_bal: sew_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_per: sew_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					alter_qty: alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					reject_qty: reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_wip: sew_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					iron_qty: iron_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_bal: iron_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_per: iron_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_alter_qty: iron_alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_reject_qty: iron_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_wip: iron_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					poly_qty: poly_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_bal: poly_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_per: poly_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_alter_qty: poly_alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_reject_qty: poly_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_wip: poly_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_bal: car_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_per: car_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_wip: car_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					ship_qty: ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: yet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_per: ship_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_wip: ship_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_value: ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					yet_to_ship_value: yet_to_ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty: ci_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_bal: ci_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_per: ci_qty_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_wip: ci_qty_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount: ci_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount_bal: ci_amount_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	getBuy()
	{
		$('#orderprogressTab').tabs('select',2);
		let params=this.getParams();
		let d= axios.get(this.route+'/getdatabuy',{params})
		.then(function (response) {
			$('#orderprogressbuyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBuy(data)
	{
		var dg = $('#orderprogressbuyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			frozenColumns:[[
				{
				title:'Order', 
				width:460,
				halign:'center',
				colspan:6,
				id:'opordersumcolcom',
				field:'fgrtd1',
				},
			],
			[{
				field:'buyer_name',
				title:'Buyer', 
				width:60,
				halign:'center',
				},
				{
				field:'qty',
				title:'Order Qty (Pcs)', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'rate',
				title:'Rate', 
				width:60,
				halign:'center',
				align:'right',
				},
				{
				field:'amount',
				title:'Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'smv',
				title:'SMV', 
				width:60,
				halign:'center',
				align:'right',
				},
				{
				field:'booked_minute',
				title:'Booked Minute', 
				width:100,
				halign:'center',
				align:'right',
				},
				]],
			columns:[[
					
					{
					title:'Yarn Summary', 
					width:900,
					halign:'center',
					colspan:9,
					id:'opyarnsumcolbuy',
					},
					{
					title:'Knit Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opknitsumcolbuy',
					},
					{
					title:'Dyeing & Finishing Summary', 
					width:1100,
					halign:'center',
					colspan:11,
					id:'opdyeingsumcolbuy',
					},
					{
					title:'Cutting Summary', 
					width:700,
					halign:'center',
					colspan:7,
					id:'opcutsumcolbuy',
					},
					{
					title:'Sewing Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opsewsumcolbuy',
					},
					{
					title:'Iron Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opironsumcolbuy',
					},
					{
					title:'Poly Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'oppolysumcolbuy',
					},
					{
					title:'Finishing Summary', 
					width:400,
					halign:'center',
					colspan:4,
					id:'opfinsumcolbuy',
					},
					{
					title:'Shipment Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opshipsumcolbuy',
					},
					{
					title:'Invoice Summary', 
					width:600,
					halign:'center',
					colspan:6,
					id:'opinvsumcolbuy',
					},
			    ],
			    [
				
				{
				field:'yarn_req',
				title:'Yarn Required', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poyarnlc_qty',
				title:'Yarn Lc Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poyarnlc_qty_bal',
				title:'Yarn Lc Bal', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_rcv',
				title:'Yarn Rcv.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_rcv_bal',
				title:'Yarn Rcv. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.yarnrcvbal
				},
				{
				field:'yarn_rcv_per',
				title:'Yarn Rcv. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_cons',
				title:'Avg. Cons/DZN', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_issue_to_knit',
				title:'Issue To Knit', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yarn_req_bal',
				title:'Issue Balance', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'prod_knit_req',
				title:'Req', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'prod_knit_qty',
				title:'Done', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_prod_bal',
				title:'Bal.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_prod_per',
				title:'Knit %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_qty',
				title:'QC  Done', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'knit_bal',
				title:'QC Pending', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.knitbal
				},
				{
				field:'prod_dyeing_req',
				title:'Req', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'batch_qty',
				title:'Batch Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'batch_bal',
				title:'Batch Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.batchbal,
				},
				{
				field:'batch_per',
				title:'Batch %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'dyeing_qty',
				title:'Dyeing Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'dyeing_bal',
				title:'Dyeing Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.dyeingbal,
				},
				{
				field:'dyeing_per',
				title:'Dyeing %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'fin_fab_req',
				title:'Fin. Fab Req.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'finish_qty',
				title:'Fin. Fab Qty.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'finish_bal',
				title:'Fin. Fab. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.finishbal,
				},
				{
				field:'finish_per',
				title:'Fin. Fab. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'plan_cut_qty',
				title:'Req. Cut Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'cut_qty',
				title:'Cut Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				
				{
				field:'cut_bal',
				title:'Cut Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.cutbal
				},
				{
				field:'cut_per',
				title:'Cut %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_line_qty',
				title:'Line Input', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_line_bal',
				title:'Line Input Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.sewlinebal
				},
				{
				field:'cut_wip',
				title:'Cut WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_qty',
				title:'Sew Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_bal',
				title:'Sew Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.sewbal
				},
				{
				field:'sew_per',
				title:'Sew %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'sew_wip',
				title:'Sew WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_qty',
				title:'Iron Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_bal',
				title:'Iron Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.ironbal
				},
				{
				field:'iron_per',
				title:'Iron %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'iron_wip',
				title:'Iron WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_qty',
				title:'Poly Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_bal',
				title:'Poly Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.polybal
				},
				{
				field:'poly_per',
				title:'Poly %.', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_alter_qty',
				title:'Alter Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_reject_qty',
				title:'Reject Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'poly_wip',
				title:'Poly WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_qty',
				title:'Fin. Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_bal',
				title:'Fin. Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.carbal
				},
				{
				field:'car_per',
				title:'Fin. %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'car_wip',
				title:'Fin. WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_qty',
				title:'Ship Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yet_to_ship_qty',
				title:'Ship Bal.', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.yettoshipqty
				},
				{
				field:'ship_per',
				title:'Ship %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_wip',
				title:'Ship WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ship_value',
				title:'Ship Value', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'yet_to_ship_value',
				title:'Ship Bal. Value', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty',
				title:'Invoice Qty', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty_bal',
				title:'Invoice Bal', 
				width:100,
				halign:'center',
				align:'right',
				styler:MsOrderProgress.ciqtybal
				},
				{
				field:'ci_qty_per',
				title:'Invoice %', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_qty_wip',
				title:'Invoice WIP', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_amount',
				title:'Invoice Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
				{
				field:'ci_amount_bal',
				title:'Invoice Bal. Amount', 
				width:100,
				halign:'center',
				align:'right',
				},
			]],
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				var booked_minute=0;
				

				/*var tdyed_yarn_rq=0;
				var tgrey_yarn_issue_qty_for_dye=0;
				var grey_yarn_issue_for_dye_bal=0;
				var tdyed_yarn_rcv_qty=0;
				var tdyed_yarn_bal_qty=0;*/

				var yarn_req=0;
				var poyarnlc_qty=0;
				var poyarnlc_qty_bal=0;
				var yarn_rcv=0;
				var yarn_rcv_bal=0;
				var yarn_rcv_per=0;
				var yarn_cons=0;
				var yarn_issue_to_knit=0;
				//var tinh_yarn_isu_qty=0;
				//var tout_yarn_isu_qty=0;
				var yarn_req_bal=0;
				
				var prod_knit_req=0;
				var prod_knit_qty=0;
				var knit_prod_bal=0;
				var knit_prod_per=0;
				var knit_qty=0;
				var knit_bal=0;

				var prod_dyeing_req=0;
				var batch_qty=0;
				var batch_bal=0;
				var batch_per=0;

				var dyeing_qty=0;
				var dyeing_bal=0;
				var dyeing_per=0;

				var fin_fab_req=0;
				var finish_qty=0;
				var finish_bal=0;
				var finish_per=0;

				var plan_cut_qty=0;
				var cut_qty=0;
				var cut_bal=0;
				var cut_per=0;
				var sew_line_qty=0;
				var sew_line_bal=0;
				var cut_wip=0;

				var sew_qty=0;
				var sew_bal=0;
				var sew_per=0;
				var alter_qty=0;
				var reject_qty=0;
				var sew_wip=0;

				var iron_qty=0;
				var iron_bal=0;
				var iron_per=0;
				var iron_alter_qty=0;
				var iron_reject_qty=0;
				var iron_wip=0;

				var poly_qty=0;
				var poly_bal=0;
				var poly_per=0;
				var poly_alter_qty=0;
				var poly_reject_qty=0;
				var poly_wip=0;

				var car_qty=0;
				var car_bal=0;
				var car_per=0;
				var car_wip=0;
				

				var ship_qty=0;
				var yet_to_ship_qty=0;
				var ship_per=0;
				var ship_wip=0;
				var ship_value=0;
				var yet_to_ship_value=0;

				var ci_qty=0;
				var ci_qty_bal=0;
				var ci_qty_per=0;
				var ci_qty_wip=0;
				var ci_amount=0;
				var ci_amount_bal=0;

				for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				booked_minute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
				

				

				yarn_req+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
				poyarnlc_qty+=data.rows[i]['poyarnlc_qty'].replace(/,/g,'')*1;
				poyarnlc_qty_bal+=data.rows[i]['poyarnlc_qty_bal'].replace(/,/g,'')*1;
				yarn_rcv+=data.rows[i]['yarn_rcv'].replace(/,/g,'')*1;
				yarn_rcv_bal+=data.rows[i]['yarn_rcv_bal'].replace(/,/g,'')*1;
				yarn_issue_to_knit+=data.rows[i]['yarn_issue_to_knit'].replace(/,/g,'')*1;
				yarn_req_bal+=data.rows[i]['yarn_req_bal'].replace(/,/g,'')*1;
				//yarn_rcv_per+=data.rows[i]['yarn_rcv_per'].replace(/,/g,'')*1;
				prod_knit_req+=data.rows[i]['prod_knit_req'].replace(/,/g,'')*1;
				prod_knit_qty+=data.rows[i]['prod_knit_qty'].replace(/,/g,'')*1;
				knit_prod_bal+=data.rows[i]['knit_prod_bal'].replace(/,/g,'')*1;
				knit_qty+=data.rows[i]['knit_qty'].replace(/,/g,'')*1;
				knit_bal+=data.rows[i]['knit_bal'].replace(/,/g,'')*1;

				prod_dyeing_req+=data.rows[i]['prod_dyeing_req'].replace(/,/g,'')*1;
				batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
				
				dyeing_qty+=data.rows[i]['dyeing_qty'].replace(/,/g,'')*1;
				dyeing_bal+=data.rows[i]['dyeing_bal'].replace(/,/g,'')*1;
				
				fin_fab_req+=data.rows[i]['fin_fab_req'].replace(/,/g,'')*1;
				finish_qty+=data.rows[i]['finish_qty'].replace(/,/g,'')*1;
				finish_bal+=data.rows[i]['finish_bal'].replace(/,/g,'')*1;

				plan_cut_qty+=data.rows[i]['plan_cut_qty'].replace(/,/g,'')*1;
				cut_qty+=data.rows[i]['cut_qty'].replace(/,/g,'')*1;
				cut_bal+=data.rows[i]['cut_bal'].replace(/,/g,'')*1;
				cut_wip+=data.rows[i]['cut_wip'].replace(/,/g,'')*1;
				sew_line_qty+=data.rows[i]['sew_line_qty'].replace(/,/g,'')*1;
				sew_line_bal+=data.rows[i]['sew_line_bal'].replace(/,/g,'')*1;
				cut_wip+=data.rows[i]['cut_wip'].replace(/,/g,'')*1;

				sew_qty+=data.rows[i]['sew_qty'].replace(/,/g,'')*1;
				sew_bal+=data.rows[i]['sew_bal'].replace(/,/g,'')*1;
				alter_qty+=data.rows[i]['alter_qty'].replace(/,/g,'')*1;
				reject_qty+=data.rows[i]['reject_qty'].replace(/,/g,'')*1;
				sew_wip+=data.rows[i]['sew_wip'].replace(/,/g,'')*1;

				iron_qty+=data.rows[i]['iron_qty'].replace(/,/g,'')*1;
				iron_bal+=data.rows[i]['iron_bal'].replace(/,/g,'')*1;
				iron_alter_qty+=data.rows[i]['iron_alter_qty'].replace(/,/g,'')*1;
				iron_reject_qty+=data.rows[i]['iron_reject_qty'].replace(/,/g,'')*1;
				iron_wip+=data.rows[i]['iron_wip'].replace(/,/g,'')*1;

				poly_qty+=data.rows[i]['poly_qty'].replace(/,/g,'')*1;
				poly_bal+=data.rows[i]['poly_bal'].replace(/,/g,'')*1;
				poly_alter_qty+=data.rows[i]['poly_alter_qty'].replace(/,/g,'')*1;
				poly_reject_qty+=data.rows[i]['poly_reject_qty'].replace(/,/g,'')*1;
				poly_wip+=data.rows[i]['poly_wip'].replace(/,/g,'')*1;

				car_qty+=data.rows[i]['car_qty'].replace(/,/g,'')*1;
				car_bal+=data.rows[i]['car_bal'].replace(/,/g,'')*1;
				car_wip+=data.rows[i]['car_wip'].replace(/,/g,'')*1;
				
				ship_qty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				yet_to_ship_qty+=data.rows[i]['yet_to_ship_qty'].replace(/,/g,'')*1;
				ship_wip+=data.rows[i]['ship_wip'].replace(/,/g,'')*1;
				ship_value+=data.rows[i]['ship_value'].replace(/,/g,'')*1;
				yet_to_ship_value+=data.rows[i]['yet_to_ship_value'].replace(/,/g,'')*1;

				ci_qty+=data.rows[i]['ci_qty'].replace(/,/g,'')*1;
				ci_qty_bal+=data.rows[i]['ci_qty_bal'].replace(/,/g,'')*1;
				ci_qty_wip+=data.rows[i]['ci_qty_wip'].replace(/,/g,'')*1;
				ci_amount+=data.rows[i]['ci_amount'].replace(/,/g,'')*1;
				ci_amount_bal+=data.rows[i]['ci_amount_bal'].replace(/,/g,'')*1;
				}

				if(yarn_req){
					yarn_rcv_per=(yarn_rcv/yarn_req)*100;
				}
				if(qty){
					yarn_cons=(yarn_req/qty)*12;
				}
				if(yarn_req){
					knit_prod_per=(prod_knit_qty/yarn_req)*100;
				}
				if(yarn_req){
					batch_per=(batch_qty/yarn_req)*100;
				}
				if(yarn_req){
					dyeing_per=(dyeing_qty/yarn_req)*100;
				}
				if(fin_fab_req){
					finish_per=(finish_qty/fin_fab_req)*100;
				}
				if(plan_cut_qty){
					cut_per=(cut_qty/plan_cut_qty)*100;
				}
				if(qty){
					sew_per=(sew_qty/qty)*100;
				}
				if(qty){
					iron_per=(iron_qty/qty)*100;
				}
				if(qty){
					poly_per=(poly_qty/qty)*100;
				}
				if(sew_qty){
					car_per=(car_qty/sew_qty)*100;
				}
				if(qty){
					ship_per=(ship_qty/qty)*100;
				}
				if(ship_qty){
					ci_qty_per=(ci_qty/ship_qty)*100;
				}

				

				
				

				$(this).datagrid('reloadFooter', [
				{ 
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					booked_minute:booked_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					yarn_req: yarn_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty: poyarnlc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poyarnlc_qty_bal: poyarnlc_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv: yarn_rcv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_bal: yarn_rcv_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_per: yarn_rcv_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_cons: yarn_cons.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_issue_to_knit: yarn_issue_to_knit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_req_bal: yarn_req_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_knit_req: prod_knit_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_knit_qty: prod_knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_prod_bal: knit_prod_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_prod_per: knit_prod_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_qty: knit_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knit_bal: knit_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					prod_dyeing_req: prod_dyeing_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_per: batch_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					dyeing_qty: dyeing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_bal: dyeing_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_per: dyeing_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					fin_fab_req: fin_fab_req.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_qty: finish_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_bal: finish_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					finish_per: finish_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					plan_cut_qty: plan_cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_qty: cut_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_bal: cut_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					cut_per: cut_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					sew_line_qty: sew_line_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_line_bal: sew_line_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				    cut_wip: cut_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					sew_qty: sew_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_bal: sew_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_per: sew_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					alter_qty: alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					reject_qty: reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sew_wip: sew_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					iron_qty: iron_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_bal: iron_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_per: iron_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_alter_qty: iron_alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_reject_qty: iron_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					iron_wip: iron_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					poly_qty: poly_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_bal: poly_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_per: poly_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_alter_qty: poly_alter_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_reject_qty: poly_reject_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					poly_wip: poly_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					car_qty: car_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_bal: car_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_per: car_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					car_wip: car_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					ship_qty: ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_to_ship_qty: yet_to_ship_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_per: ship_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_wip: ship_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ship_value: ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
					yet_to_ship_value: yet_to_ship_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty: ci_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_bal: ci_qty_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_per: ci_qty_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_qty_wip: ci_qty_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount: ci_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					ci_amount_bal: ci_amount_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	openOrdStyleWindow(){
		$('#ordstyleWindow').window('open');
	}
	getOrdStyleParams(){
		let params={};
		params.buyer_id = $('#ordstylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#ordstylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#ordstylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchOrdStyleGrid(){
		let params=this.getOrdStyleParams();
		let d= axios.get(this.route+'/ordpstyle',{params})
		.then(function(response){
			$('#ordstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrdStyleGrid(data){
		let self=this;
		$('#ordstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#orderprogressFrm [name=style_ref]').val(row.style_ref);
				$('#orderprogressFrm [name=style_id]').val(row.id);
				$('#ordstyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openTeammemberDlmWindow(){
		$('#teammemberDlmWindow').window('open');
	}
	getTdlmParams(){
		let params={};
		params.team_id = $('#teammemberdlmFrm  [name=team_id]').val();
		return params;
	}
	
	searchTeammemberDlmGrid(){
		let params=this.getTdlmParams();
		let dlm= axios.get(this.route+'/ordteammemberdlm',{params})
		.then(function(response){
			$('#teammemberdlmTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showTeammemberDlmGrid(data){
		let self=this;
		$('#teammemberdlmTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#orderprogressFrm [name=factory_merchant_id]').val(row.factory_merchant_id);
				$('#orderprogressFrm [name=team_member_name]').val(row.dlm_name);
				$('#teammemberDlmWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	getsummery(){
		let params=this.getParams();
		let d= axios.get(this.route+'/orderprogresssummery',{params})
		.then(function (response) {
			$('#orderprogressSummeryWindow').window('open');
			$('#orderprogressSummeryContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsOrderProgress=new MsOrderProgressController(new MsOrderProgressModel());

MsOrderProgress.showGrid([]);

MsOrderProgress.showGridDlmct([]);
MsOrderProgress.showGridBAgent([]);
MsOrderProgress.showGridOpFileSrc([]);
MsOrderProgress.showGridLcSc([]);
MsOrderProgress.showGridOrderQty([]);

MsOrderProgress.showGridDyedYarnRQ([]);
MsOrderProgress.showGridGreyYarntoDye([]);
MsOrderProgress.showGridDyedYarnRcv([]);
MsOrderProgress.showGridYarnRq([]);
//MsOrderProgress.showGridYarnIsuInh([]);
//MsOrderProgress.showGridYarnIsuOut([]);
MsOrderProgress.showGridKnit([]);
MsOrderProgress.showGridCutQty([]);
MsOrderProgress.showGridScrQty([]);
MsOrderProgress.showGridSewQty([]);
MsOrderProgress.showGridCarQty([]);
MsOrderProgress.showGridInspQty([]);
MsOrderProgress.showGridExfQty([]);
MsOrderProgress.showGridCom([]);
MsOrderProgress.showGridBuy([]);

MsOrderProgress.showOrdStyleGrid([]);
MsOrderProgress.showTeammemberDlmGrid([]);

$('#orderprogressTab').tabs({
	onSelect:function(title,index){
		if(index==1){
			
			//MsOrderProgress.getCom();

		}
		if(index==2){
			
			//MsOrderProgress.getBuy();
		}
	}
}); 




/*MsOrderProgress.showGridSummary([]);
MsOrderProgress.showGridCompanySummary([]);
MsOrderProgress.showGridBuyerSummary([]);
MsOrderProgress.showGridBuyerBuyinghouseSummary([]);
MsOrderProgress.showGridDetail([]);*/