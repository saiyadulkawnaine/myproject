//require('./../../../jquery.easyui.min.js');
let MsSubInbMarketingReportModel = require('./MsSubInbMarketingReportModel');
require('./../../../datagrid-filter.js');

class MsSubInbMarketingReportController {
	constructor(MsSubInbMarketingReportModel)
	{
		this.MsSubInbMarketingReportModel = MsSubInbMarketingReportModel;
		this.formId='subinbmarketingreportFrm';
		this.dataTable='#subinbmarketingreportTbl';
		this.route=msApp.baseUrl()+"/subinbmarketingreport/getdata"
	}
	
	// get(){
	// 	let params={};
	// 	params.company_id = $('#subinbmarketingreportFrm  [name=company_id]').val();
	// 	params.team_id = $('#subinbmarketingreportFrm  [name=team_id]').val();
	// 	params.teammember_id = $('#subinbmarketingreportFrm  [name=teammember_id]').val();
	// 	params.production_area_id = $('#subinbmarketingreportFrm  [name=production_area_id]').val();
	// 	params.date_from = $('#subinbmarketingreportFrm  [name=date_from]').val();
	// 	params.date_to = $('#subinbmarketingreportFrm  [name=date_to]').val();
	// 	let d= axios.get(this.route,{params})
	// 	.then(function (response) {
	// 		$('#subinbmarketingreportTbl').datagrid('loadData', response.data);
	// 		//alert(response.data)
	// 		//MsSubInbMarketingReport.showGrid(response.data);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	get(){
		let params={};
		params.company_id = $('#subinbmarketingreportFrm  [name=company_id]').val();
		params.team_id = $('#subinbmarketingreportFrm  [name=team_id]').val();
		params.teammember_id = $('#subinbmarketingreportFrm  [name=teammember_id]').val();
		params.production_area_id = $('#subinbmarketingreportFrm  [name=production_area_id]').val();
		params.date_from = $('#subinbmarketingreportFrm  [name=date_from]').val();
		params.date_to = $('#subinbmarketingreportFrm  [name=date_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#subinbmarketingreportContainer').html(response.data);
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
			data:data,
			nowrap:false,
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tRate=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				/*if(!tEmb){
					$(this).datagrid('hideColumn', 'emb_amount');
				}*/
				
			}
			
		});
		dg.datagrid('enableFilter');
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	
	getTeamMember (team_id){
		let data={};
		data.team_id=team_id;
		let team=msApp.getJson('teammember',data)
		.then(function (response) {
			    $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
                    $('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return team;
	}

	formatDetail(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsSubInbMarketingReport.detailsWindow('+row.id+')">'+row.amount+'</a>';
	}

	formatimage(value,row)
	{
		if(row.buyer_name){
				return '<a href="javascript:void(0)" onClick="MsSubInbMarketingReport.ImageWindow('+'\''+row.file_src+'\''+')">'+row.buyer_name+'</a>';

		}
//return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOrderProgress.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	ImageWindow(file_src){
		var output = document.getElementById('subinbImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+file_src;
    	            output.src =  fp;
			$('#subinbImageWindow').window('open');
	}

	detailsWindow(id)
	{

		
		let data= axios.get(msApp.baseUrl()+"/subinbmarketingreport/getdetail?id="+id);
		let g=data.then(function (response) {
		$('#subinbDetailTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
        $('#subinbDetailWindow').window('open');	
		
		
	}
	showGridDetail(data)
	{
		var dg = $('#subinbDetailTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		nowrap:false,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tQty=0;
			var tAmout=0;
			var tRate=0;
			var production_area_id;
			for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				production_area_id=data.rows[i]['production_area_id'];
			}
			tRate=(tAmout/tQty);
			$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmout.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
			if(production_area_id==10){
					$(this).datagrid('hideColumn', 'colorrange');
					$(this).datagrid('hideColumn', 'dyeing_type_id');
					$(this).datagrid('hideColumn', 'fabricshape');

					$(this).datagrid('hideColumn', 'aop_type');
					$(this).datagrid('hideColumn', 'from_coverage');
					$(this).datagrid('hideColumn', 'to_coverage');
					$(this).datagrid('hideColumn', 'from_impression');
					$(this).datagrid('hideColumn', 'to_impression');


					$(this).datagrid('showColumn', 'fabrication');
					$(this).datagrid('showColumn', 'fabric_look_id');
					$(this).datagrid('showColumn', 'gmtspart_id');
					$(this).datagrid('showColumn', 'yarncount_id');
					$(this).datagrid('showColumn', 'gauge');
				}
				if(production_area_id==20){
					$(this).datagrid('showColumn', 'colorrange');
					$(this).datagrid('showColumn', 'dyeing_type_id');
					$(this).datagrid('showColumn', 'fabricshape');
					
					$(this).datagrid('hideColumn', 'aop_type');
					$(this).datagrid('hideColumn', 'from_coverage');
					$(this).datagrid('hideColumn', 'to_coverage');
					$(this).datagrid('hideColumn', 'from_impression');
					$(this).datagrid('hideColumn', 'to_impression');


					$(this).datagrid('hideColumn', 'fabrication');
					$(this).datagrid('hideColumn', 'fabric_look_id');
					$(this).datagrid('hideColumn', 'gmtspart_id');
					$(this).datagrid('hideColumn', 'yarncount_id');
					$(this).datagrid('hideColumn', 'gauge');
				}
				if(production_area_id==25){
					$(this).datagrid('hideColumn', 'colorrange');
					$(this).datagrid('hideColumn', 'dyeing_type_id');
					$(this).datagrid('hideColumn', 'fabricshape');
					
					$(this).datagrid('showColumn', 'aop_type');
					$(this).datagrid('showColumn', 'from_coverage');
					$(this).datagrid('showColumn', 'to_coverage');
					$(this).datagrid('showColumn', 'from_impression');
					$(this).datagrid('showColumn', 'to_impression');


					$(this).datagrid('hideColumn', 'fabrication');
					$(this).datagrid('hideColumn', 'fabric_look_id');
					$(this).datagrid('hideColumn', 'gmtspart_id');
					$(this).datagrid('hideColumn', 'yarncount_id');
					$(this).datagrid('hideColumn', 'gauge');
				}
		}
		});
		dg.datagrid('loadData', data);
	}

}
window.MsSubInbMarketingReport = new MsSubInbMarketingReportController(new MsSubInbMarketingReportModel());
MsSubInbMarketingReport.showGrid([]);
MsSubInbMarketingReport.showGridDetail([]);
