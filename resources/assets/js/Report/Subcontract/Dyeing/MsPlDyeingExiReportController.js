let MsPlDyeingExiReportModel = require('./MsPlDyeingExiReportModel');
require('./../../../datagrid-filter.js');

class MsPlDyeingExiReportController {
	constructor(MsPlDyeingExiReportModel)
	{
		this.MsPlDyeingExiReportModel = MsPlDyeingExiReportModel;
		this.formId='pldyeingexireportFrm';
		this.dataTable='#pldyeingexireportTbl';
		this.route=msApp.baseUrl()+"/pldyeingexireport";
	}
	
	get(){
		let params={};
		params.company_id = $('#pldyeingexireportFrm  [name=company_id]').val();
		//params.location_id = $('#pldyeingexireportFrm  [name=location_id]').val();
		params.date_from = $('#pldyeingexireportFrm  [name=date_from]').val();
		params.date_to = $('#pldyeingexireportFrm  [name=date_to]').val();
		if(!params.company_id){
			alert('Select Company');
			return;
		}
		/*if(!params.location_id){
			alert('Select Location');
			return;
		}*/
		if(!params.date_from){
			alert('Select Date Range');
			return;
		}
		if(!params.date_to){
			alert('Select Date Range');
			return;
		}
		let d= axios.get(this.route+"/html",{params})
		.then(function (response) {
			
			//$('#pldyeingreportData').html(response.data);
			$('#pldyeingexireportTbl').datagrid('loadData', response.data);
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
}
window.MsPlDyeingExiReport= new MsPlDyeingExiReportController(new MsPlDyeingExiReportModel());
MsPlDyeingExiReport.showGrid([]);