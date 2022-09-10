let MsCashIncentiveAdvClaimModel = require('./MsCashIncentiveAdvClaimModel');
class MsCashIncentiveAdvClaimController {
	constructor(MsCashIncentiveAdvClaimModel)
	{
		this.MsCashIncentiveAdvClaimModel = MsCashIncentiveAdvClaimModel;
		this.formId='cashincentiveadvclaimFrm';
		this.dataTable='#cashincentiveadvclaimTbl';
		this.route=msApp.baseUrl()+"/cashincentiveadvclaim"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsCashIncentiveAdvClaimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveAdvClaimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cashincentiveadvclaimFrm  [name=cash_incentive_adv_id]').val($('#cashincentiveadvFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveAdvClaimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveAdvClaimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiveadvclaimTbl').datagrid('reload');
		msApp.resetForm('cashincentiveadvclaimFrm');
		$('#cashincentiveadvclaimFrm  [name=cash_incentive_adv_id]').val($('#cashincentiveadvFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveAdvClaimModel.get(index,row);
	}

	showGrid(cash_incentive_adv_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_adv_id=cash_incentive_adv_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			showFooter:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var iRate=0;
				var advAmount=0;
				var claimAmount=0;
				var localCurrency=0;
				for(var i=0; i<data.rows.length; i++){
					iRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
					advAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					claimAmount+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
					localCurrency+=data.rows[i]['local_cur_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					rate: iRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: advAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					claim_amount: claimAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					local_cur_amount: localCurrency.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter');//.datagrid('loadData', data)
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveAdvClaim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAdvance(){
		let self = this;
		let local_cur_amount=($('#cashincentiveadvclaimFrm  [name=local_cur_amount]').val())*1;
		//let claim_amount=($('#cashincentiveadvclaimFrm  [name=claim_amount]').val())*1;
		let advance_per=1*($('#cashincentiveadvFrm  [name=advance_per]').val());
		let ad_percent=advance_per/100;
		let amount=local_cur_amount*ad_percent;
		$('#cashincentiveadvclaimFrm  [name=amount]').val(amount)
		//let advance_amount_usd=claim_amount*(ad_percent);
		//$('#cashincentiveloanFrm  [name=advance_amount_usd]').val(advance_amount_usd)
	}
	
	openCashIncentiveRefWindow(){
		$('#opencashincentiverefWindow').window('open');
	}

	getParams(){
		let params={}
		params.cash_incentive_adv_id=$('#cashincentiveadvFrm  [name=id]').val();
		params.lc_sc_no=$('#cashincentiverefsearchFrm  [name=lc_sc_no]').val();
		params.lc_sc_date=$('#cashincentiverefsearchFrm  [name=lc_sc_date]').val();
		params.bank_file_no=$('#cashincentiverefsearchFrm  [name=bank_file_no]').val();
		params.beneficiary_id=$('#cashincentiverefsearchFrm  [name=beneficiary_id]').val();
		//alert(cashincentiverefid)
		return params;	
	}

	searchIncentiveRef(){
		let params=this.getParams();
		let e = axios.get(this.route+'/getcashincentiveref',{params})
		.then(function(response){
			$('#cashincentiverefsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showCashIncentiveGrid(){
		let self = this;
		$('#cashincentiverefsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			rownumbers:true,
			fit:true,
			onClickRow: function(index,row){
				$('#cashincentiveadvclaimFrm [name=cash_incentive_ref_id]').val(row.id);
				$('#cashincentiveadvclaimFrm [name=local_cur_amount]').val(row.local_cur_amount);
				$('#cashincentiveadvclaimFrm [name=amount]').val(row.advance_amount);
				$('#cashincentiveadvclaimFrm [name=claim_amount]').val(row.claim_amount);
				$('#cashincentiveadvclaimFrm [name=lc_sc_no]').val(row.lc_sc_no);
				$('#cashincentiveadvclaimFrm [name=bank_file_no]').val(row.bank_file_no);
				$('#cashincentiveadvclaimFrm [name=file_no]').val(row.file_no);
				$('#cashincentiverefsearchTbl').datagrid('loadData',[]);
				$('#opencashincentiverefWindow').window('close');
			}
		}).datagrid('enableFilter');
	}


}
window.MsCashIncentiveAdvClaim=new MsCashIncentiveAdvClaimController(new MsCashIncentiveAdvClaimModel());
MsCashIncentiveAdvClaim.showCashIncentiveGrid([]);