let MsLiabilityCoverageReportModel = require('./MsLiabilityCoverageReportModel');
require('./../../datagrid-filter.js');

class MsLiabilityCoverageReportController {
	constructor(MsLiabilityCoverageReportModel)
	{
		this.MsLiabilityCoverageReportModel = MsLiabilityCoverageReportModel;
		this.formId='LiabilityCoverageReportFrm';
		this.dataTable='#LiabilityCoverageReportTbl';
		this.route=msApp.baseUrl()+"/liabilitycoveragereport";/*getdata*/
	}
	getParams()
	{
	    let params={};
		params.lc_sc_no = $('#explcliabilitysearchFrm  [name=lc_sc_no]').val();
		params.file_no = $('#explcliabilitysearchFrm  [name=file_no]').val();
		params.beneficiary_id = $('#explcliabilitysearchFrm  [name=beneficiary_id]').val();
		params.buyer_id = $('#explcliabilitysearchFrm  [name=buyer_id]').val();
		params.date_from = $('#explcliabilitysearchFrm  [name=date_from]').val();
		params.date_to = $('#explcliabilitysearchFrm  [name=date_to]').val();
		params.bank_id = $('#explcliabilitysearchFrm  [name=bank_id]').val();
		params.last_delivery_date_from = $('#explcliabilitysearchFrm  [name=last_delivery_date_from]').val();
		params.last_delivery_date_to = $('#explcliabilitysearchFrm  [name=last_delivery_date_to]').val();
		return 	params;
	}
	get(){
		let params=this.getParams(); 
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			$('#libcodata').html('');
			$('#libcodata').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	get2(){
		let params=this.getParams();
		let d= axios.get(this.route+'/htmlgrid',{params})
		.then(function (response) {
			$('#libcodata').html('');
	        $('#libcodata').html('<table id="libacoreportmainTbl"></table>');
			$('#libacoreportmainTbl').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'100%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					title:'Order In hand', 
					width:390,
					halign:'center',
					colspan:5
				},
				
				{
					
					title:'LC / Sales Contract',
					width:170,
					halign:'center',
					colspan:2
					
				},
				
				{
					
					title:'BTB',
					width:360,
					halign:'center',
					align:'right',
					colspan:4
				},
				
				{
					
					title:'PC',
					width:360,
					halign:'center',
					align:'right',
					colspan:4
				},
				
				{
					
					title:'Doc.Purchase',
					width:360,
					halign:'center',
					align:'right',
					colspan:4
				},
				
				{
					field:'tot_liab_amount',
					title:'Total <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
					rowspan:3
				},
				{
					
					title:'Shiping Status',
					width:180,
					halign:'center',
					align:'right',
					colspan:2
					
				},
				
				{
					
					title:'Security',
					width:450,
					halign:'center',
					align:'right',
					colspan:5
				}
				,
				
				{
					
					title:'Incentive',
					width:450,
					halign:'center',
					align:'right',
					colspan:7
				}
				



			],[
				{
					field:'',
					title:'1', 
					width:60,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'2',
					width:80,
					halign:'center',
					align:'center',
			    },
				{
					field:'',
					title:'3',
					width:80,
					halign:'center',
					align:'center',
					
				},
				{
					field:'',
					title:'4',
					width:80, 
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'5',
					width:90,
					halign:'center',
					
				},
				{
					field:'',
					title:'6',
					width:80,
					halign:'center',
					align:'center',
					
					
				},
				{
					field:'',
					title:'7',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'8',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'9',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'10',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'',
					title:'11',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'',
					title:'12',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'',
					title:'13',
					width:90,
					halign:'center',
					align:'right',
					
				},
				{
					field:'',
					title:'14',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'15',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'16',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'17',
					width:90,
					halign:'center',
					align:'center',

					
				},
				{
					field:'',
					title:'18',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'19',
					width:90,
					halign:'center',
					align:'center',
				},
				/*{
					field:'tot_liab_amount',
					title:'Total <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
				},*/
				{
					field:'',
					title:'20',
					width:90,
					halign:'center',
					align:'center',
					
				},
				{
					field:'',
					title:'21',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'22',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'23',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'24',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'25',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'26',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'27',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'28',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'29',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'30',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'31',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'32',
					width:90,
					halign:'center',
					align:'center',
				},
				{
					field:'',
					title:'33',
					width:90,
					halign:'center',
					align:'center',
				}



			],[
				{
					field:'buyer_name',
					title:'Buyer', 
					width:60,
					halign:'center',
				},
				{
					field:'so_qty',
					title:'Order Qty',
					width:80,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatOrderQty
			    },
				{
					field:'so_rate',
					title:'Rate/Pcs',
					width:80,
					halign:'center',
					align:'right',
					
				},
				{
					field:'so_amount',
					title:'Amount',
					width:80, 
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatOrderAmount
				},
				{
					field:'last_delivery_date',
					title:'Plan Ship Date',
					width:90,
					halign:'center',
					
				},
				{
					field:'file_no',
					title:'File No',
					width:80,
					halign:'center',
					align:'center',
					styler:MsLiabilityCoverageReport.formatfile,
					formatter:MsLiabilityCoverageReport.formatfileDetails
				},
				{
					field:'lc_amount',
					title:'Amount',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatLcAmount
				},
				{
					field:'limit_btb_open',
					title:'BTB Limit',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'btb_open_amount',
					title:'Opened',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatbtbopen
				},
				{
					field:'yet_btb_open',
					title:'Yet <br/>to Open',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'btb_liab_amount',
					title:'Current <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatbtbadjust
				},
				{
					field:'limit_pc_taken',
					title:'PC Limit',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'pc_taken_mount',
					title:'PC Availed',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatpctaken
					
				},
				{
					field:'yet_pc_taken',
					title:'Yet <br/>to Avail',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'pc_liab_amount',
					title:'Current <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatpcadjust
				},
				{
					field:'limit_doc_pur',
					title:'Purchase <br/> Limit',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'doc_taken_amount',
					title:'Purchased',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatdocpur

					
				},
				{
					field:'yet_doc_pur',
					title:'Yet to <br/>Purchase',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'doc_liab_amount',
					title:'Current <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatdocadjust
				},
				/*{
					field:'tot_liab_amount',
					title:'Total <br/>Liability',
					width:90,
					halign:'center',
					align:'right',
				},*/
				{
					field:'sh_qty',
					title:'Ship Qty',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatshipQty
					
				},
				{
					field:'sh_amount',
					title:'Ship Amount',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatshipAmount
				},
				{
					field:'doc_in_process',
					title:'Doc In <br/>Process',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'un_rlz_amount',
					title:'Un- Realized <br/>Value',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'yet_to_ship_amount',
					title:'Yet to <br/>Ship Amount',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatshipYet
				},
				{
					field:'security',
					title:'Security',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'comments',
					title:'Comments',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'net_realised',
					title:'Net Realised',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_claimable',
					title:'Claimable',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_claimed_usd',
					title:'Claimed USD',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_claimed_tk',
					title:'Claimed TK',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_advence_usd',
					title:'Advence <br/>Taken USD',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_advence_tk',
					title:'Advence <br/>Taken Tk',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'inc_realised',
					title:'Inc. Realised',
					width:90,
					halign:'center',
					align:'right',
				}



			]],
			onLoadSuccess: function(data){
                let totalSoQty=0;
                let totalSoAmount=0;
                let totSoRate=0;
                let totLcAmount=0;

                let totBtbLimit=0;
                let totBtbOpen=0;
                let totBtbYet=0;
                let totBtbLiab=0;

                let totPcLimit=0;
                let totPcOpen=0;
                let totPcYet=0;
                let totPcLiab=0;

                let totDocLimit=0;
                let totDocOpen=0;
                let totDocYet=0;
                let totDocLiab=0;

                let totLiab=0;

                let totShipQty=0;
                let totShipAmount=0;
                let totDocInProcess=0;
                let totUnRlzAmount=0;
                let totYetToShipAmount=0;
                let totSecurity=0;


                let net_realised=0;
                let inc_claimable=0;
                let inc_claimed_usd=0;
                let inc_claimed_tk=0;
                let inc_advence_usd=0;
                let inc_advence_tk=0;
                let inc_realised=0;

				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['so_qty']){
						totalSoQty+=data.rows[i]['so_qty'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['so_amount'])
					{
						totalSoAmount+=data.rows[i]['so_amount'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['lc_amount'])
					{
						totLcAmount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['limit_btb_open'])
					{
						totBtbLimit+=data.rows[i]['limit_btb_open'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['btb_open_amount'])
					{
						totBtbOpen+=data.rows[i]['btb_open_amount'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['yet_btb_open'])
					{
						totBtbYet+=data.rows[i]['yet_btb_open'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['btb_liab_amount'])
					{
						totBtbLiab+=data.rows[i]['btb_liab_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['limit_pc_taken'])
					{
						totPcLimit+=data.rows[i]['limit_pc_taken'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['pc_taken_mount'])
					{
						totPcOpen+=data.rows[i]['pc_taken_mount'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['yet_pc_taken'])
					{
						totPcYet+=data.rows[i]['yet_pc_taken'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['pc_liab_amount'])
					{
						totPcLiab+=data.rows[i]['pc_liab_amount'].replace(/,/g,'')*1;
					}


					if(data.rows[i]['limit_doc_pur'])
					{
						totDocLimit+=data.rows[i]['limit_doc_pur'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['doc_taken_amount'])
					{
						totDocOpen+=data.rows[i]['doc_taken_amount'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['yet_doc_pur'])
					{
						totDocYet+=data.rows[i]['yet_doc_pur'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['doc_liab_amount'])
					{
						totDocLiab+=data.rows[i]['doc_liab_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['tot_liab_amount'])
					{
						totLiab+=data.rows[i]['tot_liab_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['sh_qty'])
					{
						totShipQty+=data.rows[i]['sh_qty'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['sh_amount'])
					{
						totShipAmount+=data.rows[i]['sh_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['doc_in_process'])
					{
						totDocInProcess+=data.rows[i]['doc_in_process'].replace(/,/g,'')*1;
					}
                    if(data.rows[i]['un_rlz_amount'])
					{
						totUnRlzAmount+=data.rows[i]['un_rlz_amount'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['yet_to_ship_amount'])
					{
						totYetToShipAmount+=data.rows[i]['yet_to_ship_amount'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['security'])
					{
						totSecurity+=data.rows[i]['security'].replace(/,/g,'')*1;
					}


					if(data.rows[i]['net_realised'])
					{
						net_realised+=data.rows[i]['net_realised'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['inc_claimable'])
					{
						inc_claimable+=data.rows[i]['inc_claimable'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['inc_claimed_usd'])
					{
						inc_claimed_usd+=data.rows[i]['inc_claimed_usd'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['inc_claimed_tk'])
					{
						inc_claimed_tk+=data.rows[i]['inc_claimed_tk'].replace(/,/g,'')*1;
					}
					if(data.rows[i]['inc_advence_usd'])
					{
						inc_advence_usd+=data.rows[i]['inc_advence_usd'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['inc_advence_tk'])
					{
						inc_advence_tk+=data.rows[i]['inc_advence_tk'].replace(/,/g,'')*1;
					}

					if(data.rows[i]['inc_realised'])
					{
						inc_realised+=data.rows[i]['inc_realised'].replace(/,/g,'')*1;
					}

					

					


					
				}
				totSoRate=totalSoAmount/totalSoQty;
				$('#libacoreportmainTbl').datagrid('reloadFooter', [
				{ 
					buyer_name:'Total',
					so_qty: totalSoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					so_rate: totSoRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					so_amount: totalSoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_amount: totLcAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					limit_btb_open: totBtbLimit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					btb_open_amount: totBtbOpen.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_btb_open: totBtbYet.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					btb_liab_amount: totBtbLiab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					limit_pc_taken: totPcLimit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pc_taken_mount: totPcOpen.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_pc_taken: totPcYet.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pc_liab_amount: totPcLiab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					limit_doc_pur: totDocLimit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					doc_taken_amount: totDocOpen.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yet_doc_pur: totDocYet.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					doc_liab_amount: totDocLiab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					tot_liab_amount: totLiab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					sh_qty: totShipQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sh_amount: totShipAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					doc_in_process: totDocInProcess.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                un_rlz_amount: totUnRlzAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                yet_to_ship_amount: totYetToShipAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                security:totSecurity.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

	                net_realised:net_realised.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_claimable:inc_claimable.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_claimed_usd:inc_claimed_usd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_claimed_tk:inc_claimed_tk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_advence_usd:inc_advence_usd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_advence_tk:inc_advence_tk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
	                inc_realised:inc_realised.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                

				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	formatfile(value,row,index)
	{
		if (row.comments == 'Coverd'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Under Risk'){
				return 'background-color:#E66775;';
		}
	}

	formatfileDetails(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.filepdf('+row.file_no+')">'+row.file_no+'</a>';
	}

	filepdf(file_no){
		window.open(msApp.baseUrl()+"/liabilitycoveragereport/getfilepdf?file_no="+file_no);
	}


	formatOrderQty(value,row)
	{
		if(!row.so_qty)
			row.so_qty=0;
        return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.orderQtyAmtPopUp('+row.file_no+')">'+row.so_qty+'</a>';
	}
	formatOrderAmount(value,row)
	{
		if(!row.so_amount)
			row.so_amount=0;
        return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.orderQtyAmtPopUp('+row.file_no+')">'+row.so_amount+'</a>';
	}

	formatLcAmount(value,row){
		if(!row.lc_amount)
			row.lc_amount=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.lcscQtyAmtPopUp('+row.file_no+')">'+row.lc_amount+'</a>';
	}

	formatbtbopen(value,row){
		if(!row.btb_open_amount)
			row.btb_open_amount=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.btbopenQtyAmtPopUp('+row.file_no+')">'+row.btb_open_amount+'</a>';
	}
	formatbtbadjust(value,row){
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.btbadjustQtyAmtPopUp('+row.file_no+')">'+row.btb_liab_amount+'</a>';
	}

	formatbtbadjustDtail(value,row){
		return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.btbadjustQtyAmtDtailPopUp('+row.imp_lc_id+')">'+row.accept_amount+'</a>';
   }

	formatpctaken(value,row){
		if(!row.pc_taken_mount)
			row.pc_taken_mount=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.pctakenQtyAmtPopUp('+row.file_no+')">'+row.pc_taken_mount+'</a>';
	}

	formatpcadjust(value,row){
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.pcadjustQtyAmtPopUp('+row.file_no+')">'+row.pc_liab_amount+'</a>';
	}

	formatdocpur(value,row){
		if(!row.doc_taken_amount)
			row.doc_taken_amount=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.docpurQtyAmtPopUp('+row.file_no+')">'+row.doc_taken_amount+'</a>';
	}

	formatdocadjust(value,row){
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.docadjustQtyAmtPopUp('+row.file_no+')">'+row.doc_liab_amount+'</a>';
	}

	formatshipQty(value,row){
		if(!row.sh_qty)
			row.sh_qty=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.shipQtyAmtPopUp('+row.file_no+')">'+row.sh_qty+'</a>';
	}
	formatshipAmount(value,row){
		if(!row.sh_amount)
			row.sh_amount=0;
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.shipQtyAmtPopUp('+row.file_no+')">'+row.sh_amount+'</a>';
	}
	formatshipYet(value,row){
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.shipQtyAmtPopUp('+row.file_no+')">'+row.yet_to_ship_amount+'</a>';
	}
	formatimplcpo(value,row){
		 return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.formatimplcpoPopUp('+row.id+')">Click</a>';
	}

	

	

	orderQtyAmtPopUp(file_no)
	{
		let data= axios.get(this.route+'/order?file_no='+file_no);
		let g=data.then(function (response) {
		$('#libacoreportWindow').window('clear');
		let body=$('#libacoreportWindow').window('body');
		$(body).html('<table id="libacoreportTbl"></table>');
		$('#libacoreportTbl').datagrid({
		//title:'Deduction Details',
		width:'100%',
		height:'100%',
		fit:true,
		showFooter:true,
		singleSelect:true,
		idField:'id',
		rownumbers:true,
		columns:[[
			{
				field:'buyer_name',
				title:'Buyer', 
				width:60,
				halign:'center',
			},
			{
				field:'team_member_name',
				title:'Dealing Merchant',
				width:120,
				halign:'center',
			},
			{
				field:'style_ref',
				title:'Style',
				width:80,
				halign:'center',
			},
			{
				field:'sale_order_no',
				title:'Order No',
				halign:'center',
				width:80,
				
			},
			{
				field:'ship_date',
				title:'Ship Date',
				width:80, 
				halign:'center',
				align:'right',
			},
			{
				field:'qty',
				title:'Qty',
				width:90,
				halign:'center',
				align:'right',
			},
			{
				field:'rate',
				title:'Rate',
				width:80,
				halign:'center',
				align:'right',
			},
			{
				field:'amount',
				title:'Amount',
				width:90,
				halign:'center',
				align:'right',
			},
			{
				field:'fin_fab_req',
				title:'Fabric Req Qty',
				width:90,
				halign:'center',
				align:'right',
				formatter:MsLiabilityCoverageReport.formatfinfabrq
			},
			{
				field:'fin_fab_req_amount',
				title:'Fabric Req Cost',
				width:90,
				halign:'center',
				align:'right',
			},
			{
				field:'yarn_req',
				title:'Yarn Req Qty',
				width:90,
				halign:'center',
				align:'right',
				formatter:MsLiabilityCoverageReport.formatyarnrq
			},
			{
				field:'yarn_req_amount',
				title:'Yarn Req Amount',
				width:90,
				halign:'center',
				align:'right',
			},
			{
				field:'trim_amount',
				title:'Accessories Cost',
				width:90,
				halign:'center',
				align:'right',
			}
		]],
		onLoadSuccess: function(data){
			let totalQty=0;
			let totalAmount=0;
			let totRate=0;
			let totYarnRqQty=0;
			let totYarnRqAmount=0;
			let totTrimAmount=0;
			let totFinFabQty=0;
			let totFinFabAmount=0;
			let file_no='';

			for(var i=0; i<data.rows.length; i++){
				file_no=data.rows[i]['file_no'];
				if(data.rows[i]['qty']){
					totalQty+=data.rows[i]['qty']*1;
				}
				if(data.rows[i]['amount'])
				{
					totalAmount+=data.rows[i]['amount']*1;
				}
				if(data.rows[i]['yarn_req'])
				{
					totYarnRqQty+=data.rows[i]['yarn_req']*1;
				}
				if(data.rows[i]['yarn_req_amount'])
				{
					totYarnRqAmount+=data.rows[i]['yarn_req_amount']*1;
				}
				if(data.rows[i]['trim_amount'])
				{
					totTrimAmount+=data.rows[i]['trim_amount']*1;
				}
				if(data.rows[i]['fin_fab_req'])
				{
					totFinFabQty+=data.rows[i]['fin_fab_req']*1;
				}
				if(data.rows[i]['fin_fab_req_amount'])
				{
					totFinFabAmount+=data.rows[i]['fin_fab_req_amount']*1;
				}
			}
			totRate=totalAmount/totalQty;
			$('#libacoreportTbl').datagrid('reloadFooter', [
			{ 
			buyer_name:'Total',
			qty: totalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			rate: totRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			yarn_req: totYarnRqQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			yarn_req_amount: totYarnRqAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			trim_amount: totTrimAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			fin_fab_req: totFinFabQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			fin_fab_req_amount: totFinFabAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			file_no:file_no,
			}
			]);	
		}
		}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#libacoreportWindow').window({ title: 'Order Details',width:'100%'});
		$('#libacoreportWindow').window('open');
		$('#libacoreportWindow').window('center');
		//body.innerHtml('nnnnnn');

	}

	detailsYarnWindow(id,file_no)
	{
		let params=this.getParams();
		params.id=id;
		params.file_no=file_no;
		//params.file_no=file_no;
		
		let data= axios.get(msApp.baseUrl()+"/liabilitycoveragereport/getyarn",{params});
		data.then(function (response) {
			if(id)
			{
				$('#rqbudgetyarnTbl').datagrid('loadData', response.data);
				$('#rqbudgetyarnWindow').window('open');	
			}
			else
			{
				$('#rqbudgetyarnTbl2').datagrid('loadData', response.data);
				$('#rqbudgetyarnWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	showGridYarn(data)
	{
		var drg = $('#rqbudgetyarnTbl2');
		drg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tRate=0;
				var tPoQty=0;
				var tPoAmount=0;
				var tLcQty=0;
				var tLcAmount=0;
				var tPoBalQty=0;
				var tPoBalAmount=0;
				var tLcBalQty=0;
				var tLcBalAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['req_amount'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tLcQty+=data.rows[i]['lc_qty'].replace(/,/g,'')*1;
					tLcAmount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					tPoBalQty+=data.rows[i]['po_bal_qty'].replace(/,/g,'')*1;
					tPoBalAmount+=data.rows[i]['po_bal_amount'].replace(/,/g,'')*1;
					tLcBalQty+=data.rows[i]['lc_bal_qty'].replace(/,/g,'')*1;
					tLcBalAmount+=data.rows[i]['lc_bal_amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$('#rqbudgetyarnTbl2').datagrid('reloadFooter', [
					{ 
						yarn_req: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_qty: tLcQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_amount: tLcAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_qty: tPoBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_amount: tPoBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_qty: tLcBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_amount: tLcBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		drg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridYarnS(data)
	{
		var drgs = $('#rqbudgetyarnTbl');
		drgs.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tRate=0;
				var tPoQty=0;
				var tPoAmount=0;
				var tLcQty=0;
				var tLcAmount=0;
				var tPoBalQty=0;
				var tPoBalAmount=0;
				var tLcBalQty=0;
				var tLcBalAmount=0;
				for(var i=0; i<data.rows.length; i++){
					
					tQty+=data.rows[i]['yarn_req'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['req_amount'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tLcQty+=data.rows[i]['lc_qty'].replace(/,/g,'')*1;
					tLcAmount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					tPoBalQty+=data.rows[i]['po_bal_qty'].replace(/,/g,'')*1;
					tPoBalAmount+=data.rows[i]['po_bal_amount'].replace(/,/g,'')*1;
					tLcBalQty+=data.rows[i]['lc_bal_qty'].replace(/,/g,'')*1;
					tLcBalAmount+=data.rows[i]['lc_bal_amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$('#rqbudgetyarnTbl').datagrid('reloadFooter', [
					{ 
						yarn_req: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						req_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_qty: tLcQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_amount: tLcAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_qty: tPoBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_amount: tPoBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_qty: tLcBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_amount: tLcBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		drgs.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatyarnrq(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.detailsYarnWindow('+row.id+','+'\''+row.file_no+'\''+')">'+row.yarn_req+'</a>';
	}

	showExcel(id){
		let params=this.getParams();
		params.id=id;
		let d= axios.get(msApp.baseUrl()+"/liabilitycoveragereport/getyarn",{params})
		.then(function (response) {
			if(id)
			{
				$('#rqbudgetyarnTbl').datagrid('toExcel','Item wise yarn details.xls');
			}
			else
			{
				$('#rqbudgetyarnTbl2').datagrid('toExcel','Item wise yarn details.xls');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
	detailsFinFabWindow(id,file_no)
	{
		let params=this.getParams();
		params.id=id;
		params.file_no=file_no;
		//params.file_no=file_no;
		
		let data= axios.get(msApp.baseUrl()+"/liabilitycoveragereport/getfinfab",{params});
		data.then(function (response) {
			if(id)
			{
				$('#rqbudgetfinfabTbl').datagrid('loadData', response.data);
				$('#rqbudgetfinfabWindow').window('open');	
			}
			else
			{
				$('#rqbudgetfinfabTbl2').datagrid('loadData', response.data);
				$('#rqbudgetfinfabWindow2').window('open');
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	showGridFinfab(data)
	{
		var dgf = $('#rqbudgetfinfabTbl2');
		dgf.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tRate=0;
				var tPoQty=0;
				var tPoAmount=0;
				var tLcQty=0;
				var tLcAmount=0;
				var tPoBalQty=0;
				var tPoBalAmount=0;
				var tLcBalQty=0;
				var tLcBalAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['fin_fab_req'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['fin_fab_req_amount'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tLcQty+=data.rows[i]['lc_qty'].replace(/,/g,'')*1;
					tLcAmount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					tPoBalQty+=data.rows[i]['po_bal_qty'].replace(/,/g,'')*1;
					tPoBalAmount+=data.rows[i]['po_bal_amount'].replace(/,/g,'')*1;
					tLcBalQty+=data.rows[i]['lc_bal_qty'].replace(/,/g,'')*1;
					tLcBalAmount+=data.rows[i]['lc_bal_amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$('#rqbudgetfinfabTbl2').datagrid('reloadFooter', [
					{ 
						fin_fab_req: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						fin_fab_req_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_qty: tLcQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_amount: tLcAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_qty: tPoBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_amount: tPoBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_qty: tLcBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_amount: tLcBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dgf.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	showGridFinfabS(data)
	{
		var dgfs = $('#rqbudgetfinfabTbl');
		dgfs.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tRate=0;
				var tPoQty=0;
				var tPoAmount=0;
				var tLcQty=0;
				var tLcAmount=0;
				var tPoBalQty=0;
				var tPoBalAmount=0;
				var tLcBalQty=0;
				var tLcBalAmount=0;
				for(var i=0; i<data.rows.length; i++){
					
					tQty+=data.rows[i]['fin_fab_req'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['fin_fab_req_amount'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tLcQty+=data.rows[i]['lc_qty'].replace(/,/g,'')*1;
					tLcAmount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					tPoBalQty+=data.rows[i]['po_bal_qty'].replace(/,/g,'')*1;
					tPoBalAmount+=data.rows[i]['po_bal_amount'].replace(/,/g,'')*1;
					tLcBalQty+=data.rows[i]['lc_bal_qty'].replace(/,/g,'')*1;
					tLcBalAmount+=data.rows[i]['lc_bal_amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);

				$('#rqbudgetfinfabTbl').datagrid('reloadFooter', [
					{ 
						fin_fab_req: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						fin_fab_req_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_qty: tLcQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_amount: tLcAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_qty: tPoBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_bal_amount: tPoBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_qty: tLcBalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						lc_bal_amount: tLcBalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dgfs.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatfinfabrq(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsLiabilityCoverageReport.detailsFinFabWindow('+row.id+','+'\''+row.file_no+'\''+')">'+row.fin_fab_req+'</a>';
	}

	lcscQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/lcsc?file_no='+file_no);
			let g=data.then(function (response) {
			let total_lc_sc_value=0;
			let total_replaceable=0;

			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table><table id="libacoreportTbl2"></table><table id="libacoreportTbl3"></table>');

			$('#libacoreportTbl').datagrid({
			title:'A. Replaceable Sales Contract',
			width:'100%',
			height:'33%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'buyer_name',
					title:'Buyer', 
					width:60,
					halign:'center',
				},
				{
					field:'lc_sc_no',
					title:'LC/SC NO',
					width:150,
					halign:'center',
			    },
				{
					field:'lc_sc_date',
					title:'LC/SC Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'last_delivery_date',
					title:'Last Ship Date',
					width:80, 
					halign:'center',
					align:'right',
				},
				{
					field:'amount',
					title:'LC/SC Value',
					width:90,
					halign:'center',
					align:'right',
				},
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				total_lc_sc_value+=totalAmount;
				total_replaceable=totalAmount;
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				buyer_name:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				$('#libacoreportTbl').datagrid('mergeCells', {
					index: 0,
					field: 'buyer_name',
					colspan: 2,
					type: 'footer'
				});
				
			}
		    }).datagrid('loadData',response.data.ReplaceableSalesContract);

		    $('#libacoreportTbl2').datagrid({
			title:'B. Direct LC / SC',
			width:'100%',
			height:'33%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'buyer_name',
					title:'Buyer', 
					width:60,
					halign:'center',
				},
				{
					field:'lc_sc_no',
					title:'LC/SC NO',
					width:150,
					halign:'center',
			    },
				{
					field:'lc_sc_date',
					title:'LC/SC Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'last_delivery_date',
					title:'Last Ship Date',
					width:80, 
					halign:'center',
					align:'right',
				},
				{
					field:'amount',
					title:'LC/SC Value',
					width:90,
					halign:'center',
					align:'right',
				},
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				total_lc_sc_value+=totalAmount;
				$('#libacoreportTbl2').datagrid('reloadFooter', [
				{ 
				buyer_name:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				},
				{ 
				buyer_name:'C. Total LC / SC Value (A+B)',
				amount: total_lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				$('#libacoreportTbl2').datagrid('mergeCells', {
					index: 0,
					field: 'buyer_name',
					colspan: 2,
					type: 'footer'
				});
				$('#libacoreportTbl2').datagrid('mergeCells', {
					index: 1,
					field: 'buyer_name',
					colspan: 2,
					type: 'footer'
				});
				
			}
		    }).datagrid('loadData',response.data.direct);

		    $('#libacoreportTbl3').datagrid({
			title:'D. Replaced LC / SC',
			width:'100%',
			height:'33%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'buyer_name',
					title:'Buyer', 
					width:60,
					halign:'center',
				},
				{
					field:'lc_sc_no',
					title:'LC/SC NO',
					width:150,
					halign:'center',
			    },
				{
					field:'lc_sc_date',
					title:'LC/SC Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'last_delivery_date',
					title:'Last Ship Date',
					width:80, 
					halign:'center',
					align:'right',
				},
				{
					field:'amount',
					title:'LC/SC Value',
					width:90,
					halign:'center',
					align:'right',
				},
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				let yet_to_replace=total_replaceable-totalAmount;
				$('#libacoreportTbl3').datagrid('reloadFooter', [
				{ 
				buyer_name:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				},
				{ 
				buyer_name:'Yet to Replace (A-D)',
				amount: yet_to_replace.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				$('#libacoreportTbl3').datagrid('mergeCells', {
					index: 0,
					field: 'buyer_name',
					colspan: 2,
					type: 'footer'
				});
				$('#libacoreportTbl3').datagrid('mergeCells', {
					index: 1,
					field: 'buyer_name',
					colspan: 2,
					type: 'footer'
				});
				
			}
		    }).datagrid('loadData',response.data.Replaced);

			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'LC/SC Details',width:530});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	btbopenQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/btbopen?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');	
			$('#libacoreportTbl').datagrid({
			//title:'Replaceable Sales Contract',
			width:'100%',
			height:'100%',
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'lc_no',
					title:'BTB LC NO',
					width:150,
					halign:'center',
			    },
			    {
					field:'company_name',
					title:'Company',
					width:60,
					halign:'center',
			    },
			    {
					field:'menu_name',
					title:'PO Type',
					width:100,
					halign:'center',
			    },
				{
					field:'lc_date',
					title:'BTB LC Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'bank_name',
					title:'Bank',
					halign:'center',
					width:180,
					
				},
				{
					field:'supplier_name',
					title:'Supplier',
					halign:'center',
					width:80,
					
				},
				
				{
					field:'amount',
					title:'BTB Value',
					width:90,
					halign:'center',
					align:'right',
				},

				{
					field:'last_delivery_date',
					title:'Last Ship Date',
					width:80, 
					halign:'center',
					
				},
				{
					field:'expiry_date',
					title:'Expiry Date ',
					width:80, 
					halign:'center',
					
				},
				{
					field:'pay_term',
					title:'Pay term ',
					width:80, 
					halign:'center',
					
				}
				,
				{
					field:'po_no',
					title:'Po No',
					width:80, 
					halign:'center',
					formatter:MsLiabilityCoverageReport.formatimplcpo
					
				}
			]],
			rowStyler:function(index,row){
				if (row.lc_no==='Sub Total'){
				return 'background-color:pink;color:#000000;font-weight:bold;';
				}
		    },
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['lc_no'] !=='Sub Total')
					{
						totalAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					}
				}
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				lc_no:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'BTB Open',width:1100});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	formatimplcpoPopUp(id)
	{
		let params={};
		params.imp_lc_id = id;
		let data= axios.get(msApp.baseUrl()+"/implcpo",{params})
		.then(function (response) {
			$('#libacoreportbtbopenpoTbl').datagrid('loadData', response.data);
		    $('#libacoreportbtbopenpoWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	formatimplcpoGrid(data)
	{
		var dg = $('#libacoreportbtbopenpoTbl');
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

	formatimplcpoPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLiabilityCoverageReport.poPdf('+row.purchase_order_id+','+row.menu_id+')">'+row.po_no+'</a>';
	}
	poPdf(purchase_order_id,menu_id)
	{
		if(menu_id==2)
		{
				window.open(msApp.baseUrl()+"/potrim/reportshort?id="+purchase_order_id);
		}
		if(menu_id==3)
		{
				window.open(msApp.baseUrl()+"/poyarn/report?id="+purchase_order_id);
		}
		if(menu_id==4)
		{
				window.open(msApp.baseUrl()+"/poknitservice/report?id="+purchase_order_id);
		}
		if(menu_id==6)
		{
				window.open(msApp.baseUrl()+"/podyeingservice/report?id="+purchase_order_id);
		}
		if(menu_id==7)
		{
				window.open(msApp.baseUrl()+"/podyechem/report?id="+purchase_order_id);
		}
		if(menu_id==8)
		{
				window.open(msApp.baseUrl()+"/pogeneral/report?id="+purchase_order_id);
		}
		if(menu_id==9)
		{
				window.open(msApp.baseUrl()+"/poyarndyeing/report?id="+purchase_order_id);
		}
	}

	btbadjustQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/btbadjust?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');	
			$('#libacoreportTbl').datagrid({
			//title:'Replaceable Sales Contract',
			width:'100%',
			height:'100%',
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'lc_no',
					title:'BTB LC NO',
					width:150,
					halign:'center',
			    },
				
				{
					field:'bank_name',
					title:'Bank',
					halign:'center',
					width:180,
					
				},
				{
					field:'supplier_name',
					title:'Supplier',
					halign:'center',
					width:80,
				},
				
				{
					field:'btb_amount',
					title:'BTB Value',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'accept_amount',
					title:'Accepted Value',
					width:90,
					halign:'center',
					align:'right',
					formatter:MsLiabilityCoverageReport.formatbtbadjustDtail
				},
				{
					field:'amount',
					title:'Liability Adjusted',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'balance',
					title:'Balance',
					width:90,
					halign:'center',
					align:'right',
				}

				
				
			]],
			onLoadSuccess: function(data){
                let totalBtbAmount=0;
                let totalAccAmount=0;
                let totalAmount=0;
                let totalBalaAmount=0;
                
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['btb_amount'])
					{
						totalBtbAmount+=data.rows[i]['btb_amount']*1;
					}
					if(data.rows[i]['accept_amount'])
					{
						totalAccAmount+=data.rows[i]['accept_amount']*1;
					}
					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
					if(data.rows[i]['balance'])
					{
						totalBalaAmount+=data.rows[i]['balance']*1;
					}

				}
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				lc_no:'Total',
				btb_amount: totalBtbAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				accept_amount: totalAccAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				balance: totalBalaAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'BTB Adjust',width:850});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	
	btbadjustQtyAmtDtailPopUp(imp_lc_id)
	{
		let data= axios.get(this.route+'/btbadjustacceptdtail?imp_lc_id='+imp_lc_id);
		let g=data.then(function (response) {
		$('#libacoreportWindow').window('clear');
		let body=$('#libacoreportWindow').window('body');
		$(body).html('<table id="libacoreportTbl"></table>');	
		$('#libacoreportTbl').datagrid({
		//title:'Replaceable Sales Contract',
		width:'100%',
		height:'100%',
		fit:true,
		showFooter:true,
		singleSelect:true,
		idField:'id',
		rownumbers:true,
		columns:[[
			{
				field:'acceptance_id',
				title:'Acceptance ID',
				width:40,
				halign:'center',
				align:'right',
			},
			{
				field:'lc_no',
				title:'BTB LC NO',
				width:120,
				halign:'center',
			},
			
			{
				field:'bank_name',
				title:'Bank',
				halign:'center',
				width:100,
				
			},
			{
				field:'supplier_name',
				title:'Supplier',
				halign:'center',
				width:60,
				
			},
			{
				field:'invoice_no',
				title:'Invoice No',
				width:70,
				halign:'center',
			},
			{
				field:'invoice_date',
				title:'Invoice Date',
				width:70,
				halign:'center',
			},
			{
				field:'bank_ref',
				title:'Bank Ref',
				width:90,
				halign:'center',
			},
			{
				field:'bank_accep_date',
				title:'BankRef Date',
				width:70,
				halign:'center',
			},

			{
				field:'doc_value',
				title:'Doc Value',
				width:80,
				halign:'center',
				align:'right',
			}

			
			
		]],
		onLoadSuccess: function(data){
			let totalBtbAmount=0;
			let totalAccAmount=0;
			let totalAmount=0;
			let totalBalaAmount=0;
			
			for(var i=0; i<data.rows.length; i++){

				if(data.rows[i]['btb_amount']){
					totalBtbAmount+=data.rows[i]['btb_amount']*1;
				}
				if(data.rows[i]['accept_amount']){
					totalAccAmount+=data.rows[i]['accept_amount']*1;
				}
				if(data.rows[i]['amount']){
					totalAmount+=data.rows[i]['amount']*1;
				}
				if(data.rows[i]['balance']){
					totalBalaAmount+=data.rows[i]['balance']*1;
				}

			}
			$('#libacoreportTbl').datagrid('reloadFooter', [
			{ 
			lc_no:'Total',
			btb_amount: totalBtbAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			accept_amount: totalAccAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			balance: totalBalaAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
			}
			]);
		}
		}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#libacoreportWindow').window({ title: 'BTB Adjust',width:850});
		$('#libacoreportWindow').window('open');
		$('#libacoreportWindow').window('center');
	}


	pctakenQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/pctaken?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');
			$('#libacoreportTbl').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'100%',
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'lc_sc_no',
					title:'LC NO',
					width:150,
					halign:'center',
				},
				
				{
					field:'loan_no',
					title:'Loan NO',
					width:150,
					halign:'center',
				},
				
				{
					field:'cr_date',
					title:'Loan Date',
					halign:'center',
					width:80,
					
				},

				{
					field:'amount',
					title:'Loan Amount',
					width:90,
					halign:'center',
					align:'right',
				},

				{
					field:'exch_rate',
					title:'Exch.Rate',
					width:70,
					halign:'center',
					align:'right',
				},

				{
					field:'credit_taken',
					title:'Credit Taken',
					width:90,
					halign:'center',
					align:'right',
				},

				{
					field:'tenor',
					title:'Tenure',
					width:80, 
					halign:'center',
					align:'right',
					
				},
				{
					field:'maturity_date',
					title:'Maturity Date ',
					width:80, 
					halign:'center',
					
				}
				
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				loan_no:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'PC Taken',width:550});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	pcadjustQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/pcadjust?file_no='+file_no);
			let g=data.then(function (response) {
			let total_pc_taken=0;
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table><table id="libacoreportTbl2"></table>');
	        $('#libacoreportTbl').datagrid({
			title:'A. PC Taken',
			width:'100%',
			height:'50%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'loan_no',
					title:'Loan NO',
					width:150,
					halign:'center',
			    },
				{
					field:'cr_date',
					title:'Loan Date',
					halign:'center',
					width:80,
					
				},
				
				
				
				{
					field:'amount',
					title:'Loan Amount',
					width:90,
					halign:'center',
					align:'right',
				},

				{
					field:'tenor',
					title:'Tenure',
					width:80, 
					halign:'center',
					align:'right',
					
				},
				{
					field:'maturity_date',
					title:'Maturity Date ',
					width:80, 
					halign:'center',
					
				}
				
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				total_pc_taken+=totalAmount;
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				loan_no:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('loadData',response.data.pctaken);

			$('#libacoreportTbl2').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'50%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'bill_no',
					title:'Bank Ref/Bill NO',
					halign:'center',
					width:150,		
				},
				{
					field:'ad_date',
					title:'Adjusted Date',
					halign:'center',
					width:80,			
				},
				{
					field:'event',
					title:'Event',
					halign:'center',
					width:150,					
				},
				{
					field:'amount',
					title:'Loan Amount',
					width:90,
					halign:'center',
					align:'right',
				}
				
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
				}
				let pc_liab=total_pc_taken-totalAmount;
				$('#libacoreportTbl2').datagrid('reloadFooter', [
				{ 
				event:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				},
				{ 
				event:'Balance',
				amount: pc_liab.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('loadData',response.data.adjust);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'PC Adjust',width:550});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	docpurQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/docpur?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');
			$('#libacoreportTbl').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'100%',
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'bank_ref_bill_no',
					title:'Bank Ref No',
					width:150,
					halign:'center',
			    },
				{
					field:'bank_ref_date',
					title:'Bank Ref Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'amount',
					title:'Amount',
					width:90,
					halign:'center',
					align:'right',
				},
                {
					field:'pc_amount',
					title:'PC A/C',
					width:80, 
					halign:'center',
					align:'right',
					
				},
				{
					field:'cd_amount',
					title:'CD A/C',
					width:80, 
					halign:'center',
					align:'right',
					
				}
				,
				{
					field:'other',
					title:'Others',
					width:80, 
					halign:'center',
					align:'right',
					
				}
				
				
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
                let totalPc=0;
                let totalCd=0;
                let totalOther=0;

				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
					if(data.rows[i]['pc_amount'])
					{
						totalPc+=data.rows[i]['pc_amount']*1;
					}
					if(data.rows[i]['cd_amount'])
					{
						totalCd+=data.rows[i]['cd_amount']*1;
					}
					if(data.rows[i]['other'])
					{
						totalOther+=data.rows[i]['other']*1;
					}
				}
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				bank_ref_bill_no:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				pc_amount: totalPc.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				cd_amount: totalCd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				other: totalOther.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'Doc Purchase',width:650});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');


	}

	docadjustQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/docadjust?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');
			$('#libacoreportTbl').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'100%',
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'bank_ref_bill_no',
					title:'Bank Ref No',
					width:150,
					halign:'center',
			    },
				{
					field:'realization_date',
					title:'Adjust Date',
					halign:'center',
					width:80,
					
				},
				{
					field:'amount',
					title:'Purchased',
					width:90,
					halign:'center',
					align:'right',
				},
                {
					field:'doc_adj_amount',
					title:'Adjusted',
					width:80, 
					halign:'center',
					align:'right',
					
				},
				{
					field:'balance',
					title:'Balance',
					width:80, 
					halign:'center',
					align:'right',
					
				}
			]],
			onLoadSuccess: function(data){
                let totalAmount=0;
                let totalAdjusted=0;
                let totalBalance=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['amount'])
					{
						totalAmount+=data.rows[i]['amount']*1;
					}
					if(data.rows[i]['doc_adj_amount'])
					{
						totalAdjusted+=data.rows[i]['doc_adj_amount']*1;
					}
					if(data.rows[i]['balance'])
					{
						totalBalance+=data.rows[i]['balance']*1;
					}
				}
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				bank_ref_bill_no:'Total',
				amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				doc_adj_amount: totalAdjusted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				balance: totalBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'Doc Purchase',width:650});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');
	}

	shipQtyAmtPopUp(file_no)
	{
		    let data= axios.get(this.route+'/invoiceqty?file_no='+file_no);
			let g=data.then(function (response) {
			$('#libacoreportWindow').window('clear');
			let body=$('#libacoreportWindow').window('body');
	        $(body).html('<table id="libacoreportTbl"></table>');
			$('#libacoreportTbl').datagrid({
			//title:'Deduction Details',
			width:'100%',
			height:'100%',
			//fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			rownumbers:true,
			columns:[[
				{
					field:'company_id',
					title:'Company', 
					width:60,
					halign:'left',
				},
				{
					field:'lc_sc_no',
					title:'LC/SC No',
					width:150,
					halign:'left',
			    },
				{
					field:'lc_sc_date',
					title:'LC/SC Date',
					halign:'left',
					width:80,
					
				},
				{
					field:'lien_bank',
					title:'Lien Bank',
					width:100, 
					halign:'center',
					align:'left',
				},
				{
					field:'buyer_name',
					title:'Buyer',
					width:150,
					halign:'center',
					align:'left',
				},
				{
					field:'invoice_no',
					title:'Invoice No',
					width:80,
					halign:'center',
					align:'left',
				},
				{
					field:'invoice_date',
					title:'Invoice Date',
					width:90,
					halign:'center',
					align:'left',
				},
				{
					field:'invoice_qty',
					title:'Invoice Qty',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'invoice_rate',
					title:'Invoice Rate',
					width:90,
					halign:'center',
					align:'right',
				},
				{
					field:'invoice_amount',
					title:'Invoice Amount',
					width:90,
					halign:'center',
					align:'right',
				}
			]],
			onLoadSuccess: function(data){
                let totalQty=0;
                let totalAmount=0;
                let totRate=0;
                
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['invoice_qty']){
						totalQty+=data.rows[i]['invoice_qty']*1;
					}
					if(data.rows[i]['invoice_amount'])
					{
						totalAmount+=data.rows[i]['invoice_amount']*1;
					}
					
				}
				totRate=totalAmount/totalQty;
				$('#libacoreportTbl').datagrid('reloadFooter', [
				{ 
				buyer_name:'Total',
				invoice_qty: totalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				invoice_rate: totRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				invoice_amount: totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				
				}
				]);
				
			}
		    }).datagrid('enableFilter').datagrid('loadData',response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
			$('#libacoreportWindow').window({ title: 'Ship Details',width:1100});
	        $('#libacoreportWindow').window('open');
	        $('#libacoreportWindow').window('center');
	        
	        
            //body.innerHtml('nnnnnn');

	}

	/*liabilitycoveragereportsWindow(flie_src){
			$('#liabilitycoveragereportsWindow').window('open');
	}*/

}
window.MsLiabilityCoverageReport=new MsLiabilityCoverageReportController(new MsLiabilityCoverageReportModel());
MsLiabilityCoverageReport.formatimplcpoGrid([]);
MsLiabilityCoverageReport.showGridYarnS([]);
MsLiabilityCoverageReport.showGridYarn([]);
MsLiabilityCoverageReport.showGridFinfabS([]);
MsLiabilityCoverageReport.showGridFinfab([]);