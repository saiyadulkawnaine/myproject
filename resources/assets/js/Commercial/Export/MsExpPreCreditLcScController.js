let MsExpPreCreditLcScModel = require('./MsExpPreCreditLcScModel');
class MsExpPreCreditLcScController {
	constructor(MsExpPreCreditLcScModel)
	{
		this.MsExpPreCreditLcScModel = MsExpPreCreditLcScModel;
		this.formId='expprecreditlcscFrm';
		this.dataTable='#expprecreditlcscTbl';
		this.route=msApp.baseUrl()+"/expprecreditlcsc"
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
			this.MsExpPreCreditLcScModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpPreCreditLcScModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpPreCreditLcScModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpPreCreditLcScModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expprecreditlcscTbl').datagrid('reload');
		msApp.resetForm('expprecreditlcscFrm');
		$('#expprecreditlcscFrm [name=exp_pre_credit_id]').val($('#expprecreditFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpPreCreditLcScModel.get(index,row);

	}

	showGrid(exp_pre_credit_id)
	{
		let self=this;
		var data={};
		data.exp_pre_credit_id=exp_pre_credit_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			showFooter:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var credit_taken=0;
				var equivalent_fc=0;
				for(var i=0; i<data.rows.length; i++){
					credit_taken+=data.rows[i]['credit_taken'].replace(/,/g,'')*1;
					equivalent_fc+=data.rows[i]['equivalent_fc'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					credit_taken: credit_taken.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					equivalent_fc: equivalent_fc.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpPreCreditLcSc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openExpPreCreditLcScWindow(){
		$('#expprecreditlcscWindow').window('open');
	}

	searchPreCreditSalesContractGrid()
	{
		let bank_account_id=$('#expprecreditFrm  [name=bank_account_id]').val();
		let lc_sc_no=$('#precreditlcscsearchFrm  [name=lc_sc_no]').val();
		let lc_sc_date=$('#precreditlcscsearchFrm  [name=lc_sc_date]').val();
		let data= axios.get(this.route+"/getExpLcSc?lc_sc_no="+lc_sc_no+"&lc_sc_date="+lc_sc_date+"&bank_account_id="+bank_account_id);
		data.then(function (response) {
			$('#precreditlcscsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	ShowSalesContractGrid(data){
		//let self = this;
		$('#precreditlcscsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#expprecreditlcscFrm [name=exp_lc_sc_id]').val(row.id);
				$('#expprecreditlcscFrm [name=lc_sc_no]').val(row.lc_sc_no);
				//$('#expprecreditlcscFrm [name=tenor]').val(row.tenor);
				$('#expprecreditlcscWindow').window('close');
				$('#precreditlcscsearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter').datagrid('loadData',data);
	}

	calculateEquiFc(){
		let exch_rate=$('#expprecreditlcscFrm [name=exch_rate]').val();
		let credit_taken=$('#expprecreditlcscFrm [name=credit_taken]').val();
		let equivalent_fc=credit_taken/exch_rate;
		$('#expprecreditlcscFrm [name=equivalent_fc]').val(equivalent_fc);
	}
}
window.MsExpPreCreditLcSc=new MsExpPreCreditLcScController(new MsExpPreCreditLcScModel());
MsExpPreCreditLcSc.ShowSalesContractGrid([]);