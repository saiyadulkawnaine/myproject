//require('./jquery.easyui.min.js');
let MsOfferStatementModel = require('./MsOfferStatementModel');
require('./datagrid-filter.js');

class MsOfferStatementController {
	constructor(MsOfferStatementModel)
	{
		this.MsOfferStatementModel = MsOfferStatementModel;
		this.formId='offerstatementFrm';
		this.dataTable='#offerstatementTbl';
		this.route=msApp.baseUrl()+"/offerstatement/getdata";
	}
	show()
	{
		let formObj={};
		let id=[];
		let i=1;
		$.each($('#offerstatementTbl').datagrid('getChecked'), function (idx, val) {
				formObj['mkt_cost_id['+i+']']=val.id
				id.push(val.id)
				
			i++;
		});
		id=id.join(',');
		window.open(msApp.baseUrl()+"/offerstatement/pdf?id="+id);
		
		//this.MsBuyerNatureModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}

	get(){
		let params={};
		//params.company_id = $('#offerstatementFrm  [name=company_id]').val();
		params.buyer_id = $('#offerstatementFrm  [name=buyer_id]').val();
		params.team_id = $('#offerstatementFrm  [name=team_id]').val();
		params.teammember_id = $('#offerstatementFrm  [name=teammember_id]').val();
		params.style_ref = $('#offerstatementFrm  [name=style_ref]').val();
		//params.job_no = $('#offerstatementFrm  [name=job_no]').val();
		params.date_from = $('#offerstatementFrm  [name=date_from]').val();
		params.date_to = $('#offerstatementFrm  [name=date_to]').val();
		if(params.buyer_id==''){
			alert("Select Buyer First");
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#offerstatementTbl').datagrid('loadData', response.data);
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
			singleSelect:false,
			//queryParams:data,
			checkbox:true,
			showFooter:true,
			fit:true,
			//url:this.route,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ pdf: ' '}
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
		var output = document.getElementById('offerstatementImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+flie_src;
    	            output.src =  fp;
			$('#offerstatementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsOfferStatement.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOfferStatement.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	
	quotedprice(value,row,index)
	{
		
		
		if (row.cost_per_pcs*1 > value*1){
				return 'color:red;';
			}
	}
	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}


}
window.MsOfferStatement=new MsOfferStatementController(new MsOfferStatementModel());
MsOfferStatement.showGrid([]);
