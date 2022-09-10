let MsCashIncentiveLoanModel = require('./MsCashIncentiveLoanModel');
class MsCashIncentiveLoanController {
	constructor(MsCashIncentiveLoanModel)
	{
		this.MsCashIncentiveLoanModel = MsCashIncentiveLoanModel;
		this.formId='cashincentiveloanFrm';
		this.dataTable='#cashincentiveloanTbl';
		this.route=msApp.baseUrl()+"/cashincentiveloan"
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
			this.MsCashIncentiveLoanModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveLoanModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
       $('#cashincentiveloanFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
       let exporter_branch_name = $('#cashincentiverefFrm  [name=exporter_branch_name]').val();
		$('#cashincentiveloanFrm  [name=exporter_branch_name]').val(exporter_branch_name)
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveLoanModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveLoanModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiveloanTbl').datagrid('reload');
		msApp.resetForm('cashincentiveloanFrm');
		$('#cashincentiveloanFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
		let exporter_branch_name = $('#cashincentiverefFrm  [name=exporter_branch_name]').val();
		$('#cashincentiveloanFrm  [name=exporter_branch_name]').val(exporter_branch_name)

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveLoanModel.get(index,row);
	}

	showGrid(cash_incentive_ref_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_ref_id=cash_incentive_ref_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess:function(data){
				var AmountUsd = 0 ;
				var AmountTk = 0 ;
				for(var i=0; i<data.rows.length; i++){
					AmountUsd+=data.rows[i]['advance_amount_usd'].replace(/,/g,'')*1;
					AmountTk+=data.rows[i]['advance_amount_tk'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						advance_amount_usd: AmountUsd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						advance_amount_tk: AmountTk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveLoan.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getParams(){
		let params={}
		params.cash_incentive_ref_id=$('#cashincentiverefFrm [name=id]').val();
		return params;
	}

	getCash(data){
		let params=this.getParams();
		let d = axios.get(this.route+'/getclaim',{params})
		d.then(function (response) {
			//alert(response.data.claim_amount)
		$('#cashincentiveloanFrm  [name=local_cur_amount]').val(response.data.local_cur_amount)
		$('#cashincentiveloanFrm  [name=claim_amount]').val(response.data.claim_amount)
		})
		.catch(function (error) {
			console.log(error);
		});
		//return d;
	}

	calculateAdvance(){
		let self = this;
		let local_cur_amount=($('#cashincentiveloanFrm  [name=local_cur_amount]').val())*1;
		//alert(local_cur_amount)
		let claim_amount=($('#cashincentiveloanFrm  [name=claim_amount]').val())*1;
		let advance_per=1*($('#cashincentiveloanFrm  [name=advance_per]').val());
		let ad_percent=advance_per/100;
		let advance_amount_tk=local_cur_amount*ad_percent;
		$('#cashincentiveloanFrm  [name=advance_amount_tk]').val(advance_amount_tk)
		let advance_amount_usd=claim_amount*(ad_percent);
		$('#cashincentiveloanFrm  [name=advance_amount_usd]').val(advance_amount_usd)
	}


}
window.MsCashIncentiveLoan=new MsCashIncentiveLoanController(new MsCashIncentiveLoanModel());