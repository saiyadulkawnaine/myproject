require('./jquery.easyui.min.js');
let MsSampleRequirementModel = require('./MsSampleRequirementModel');
require('./datagrid-filter.js');

class MsSampleRequirementController {
	constructor(MsSampleRequirementModel)
	{
		this.MsSampleRequirementModel = MsSampleRequirementModel;
		this.formId='samplerequirementFrm';
		this.dataTable='#samplerequirementTbl';
		this.route=msApp.baseUrl()+"/samplerequirement/getdata";
	}

	get(){
		let params={};
		//params.company_id = $('#samplerequirementFrm  [name=company_id]').val();
		params.buyer_id = $('#samplerequirementFrm  [name=buyer_id]').val();
		params.team_id = $('#samplerequirementFrm  [name=team_id]').val();
		params.teammember_id = $('#samplerequirementFrm  [name=teammember_id]').val();
		params.style_ref = $('#samplerequirementFrm  [name=style_ref]').val();
		//params.job_no = $('#samplerequirementFrm  [name=job_no]').val();
		params.date_from = $('#samplerequirementFrm  [name=date_from]').val();
		params.date_to = $('#samplerequirementFrm  [name=date_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#samplerequirementTbl').datagrid('loadData', response.data);
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
	
	imageWindow(flie_src){
		var output = document.getElementById('samplerequirementImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+flie_src;
    	            output.src =  fp;
			$('#samplerequirementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSampleRequirement.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsSampleRequirement.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	
	quotedprice(value,row,index)
	{
		
		
		if (row.cost_per_pcs*1 > value*1){
				return 'color:red;';
			}
	}

	styleformat(value,row,index)
	{
		
		
		
		if (row.status == 'Confirmed'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Refused'){
				return 'background-color:#E66775;';
		}
		if (row.status == 'Cancel'){
				return 'background-color:#E66775;';
		}
	}

	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}


}
window.MsSampleRequirement=new MsSampleRequirementController(new MsSampleRequirementModel());
MsQuotationStatement.showGrid([]);