let MsExpDocSubTransectionModel = require('./MsExpDocSubTransectionModel');
class MsExpDocSubTransectionController {
	constructor(MsExpDocSubTransectionModel)
	{
		this.MsExpDocSubTransectionModel = MsExpDocSubTransectionModel;
		this.formId='expdocsubtransectionFrm';
		this.dataTable='#expdocsubtransectionTbl';
		this.route=msApp.baseUrl()+"/expdocsubtransection"
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
			this.MsExpDocSubTransectionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpDocSubTransectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#expdocsubtransectionFrm [name=exp_doc_submission_id]').val($('#expdocsubmissionFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpDocSubTransectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpDocSubTransectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expdocsubtransectionTbl').datagrid('reload');
		msApp.resetForm('expdocsubtransectionFrm');
		$('#expdocsubtransectionFrm [name=exp_doc_submission_id]').val($('#expdocsubmissionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpDocSubTransectionModel.get(index,row);

	}

	showGrid(exp_doc_submission_id)
	{
		let self=this;
		var data={};
		data.exp_doc_submission_id=exp_doc_submission_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['dom_value'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['doc_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ dom_value: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),doc_value: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpDocSubTransection.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(){
	    var radioValue = $("input[name='aa']:checked").val();
	    let dom_value=$("#expdocsubtransectionFrm [name='dom_value']").val();
	    let doc_value=$("#expdocsubtransectionFrm [name='doc_value']").val();
	    let exch_rate=$("#expdocsubtransectionFrm [name='exch_rate']").val();
	    let value

        if(radioValue==3)
        {
        	value=(doc_value*1*exch_rate*1)
            $("#expdocsubtransectionFrm [name='dom_value']").val(value)
        }
        if(radioValue==2)
        {
            value=(dom_value*1)/(doc_value*1)
           
            $("#expdocsubtransectionFrm [name='exch_rate']").val(value) ;
        }
        if(radioValue==1)
        {
            value=(dom_value*1)/(exch_rate*1);
            $("#expdocsubtransectionFrm [name='doc_value']").val(value)
        }
	}

	transbankaccountWindowOpen(){
		$('#opentransbankaccountWindow').window('open');
	}

	searchtransbankAccount()
	{
		let exp_doc_submission_id=$('#expdocsubmissionFrm  [name=id]').val();
		let account_type_id=$('#transbankaccountsearchFrm  [name=account_type_id]').val();
		let account_no=$('#transbankaccountsearchFrm  [name=account_no]').val();
		let data= axios.get(this.route+"/getbankaccount?account_type_id="+account_type_id+"&account_no="+account_no+"&exp_doc_submission_id="+exp_doc_submission_id);
		data.then(function (response) {
			$('#transbankaccountsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridTransBankAccount(data){
		$('#transbankaccountsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#expdocsubtransectionFrm [name=bank_account_id]').val(row.id);
				$('#expdocsubtransectionFrm [name=commercial_head_name]').val(row.commercial_head_name);
				$('#opentransbankaccountWindow').window('close');
				$('#transbankaccountsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsExpDocSubTransection=new MsExpDocSubTransectionController(new MsExpDocSubTransectionModel());
MsExpDocSubTransection.showGridTransBankAccount([]);