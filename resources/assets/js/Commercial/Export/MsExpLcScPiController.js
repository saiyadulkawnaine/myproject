let MsExpLcScPiModel = require('./MsExpLcScPiModel');
class MsExpLcScPiController {
	constructor(MsExpLcScPiModel)
	{
		this.MsExpLcScPiModel = MsExpLcScPiModel;
		this.formId='lcscpiFrm';
		this.dataTable='#lcscpiTbl';
		this.route=msApp.baseUrl()+"/lcscpi"
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

		let formObj=this.getSelections();
		if(formObj.id){
			this.MsExpLcScPiModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcScPiModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcScPiModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcScPiModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#lcscpiTbl').datagrid('reload');
		//MsBankAccount.create()
		msApp.resetForm('lcscpiFrm');
        $('#lcscpiFrm  [name=exp_lc_sc_id]').val($('#explcsc  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpLcScPiModel.get(index,row);
	}

	showGrid(exp_lc_sc_id)
	{
		let self=this;
        var data={};
		data.exp_lc_sc_id=exp_lc_sc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			showFooter:true,
            queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);

	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpRepLcSc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	importPi()
	{
			let pi_no=$('#lcscpisearchFrm  [name=pi_no]').val();
			let style_ref=$('#lcscpisearchFrm  [name=style_ref]').val();
			let job_no=$('#lcscpisearchFrm  [name=job_no]').val();
			let order_no=$('#lcscpisearchFrm  [name=order_no]').val();
			let explcscid=$('#explcscFrm  [name=id]').val();

			let data= axios.get(this.route+"/importpi"+"?pi_no="+pi_no+"&style_ref="+style_ref+"&job_no="+job_no+"&order_no="+order_no+"&explcscid="+explcscid)
			.then(function (response) {
			$('#explcscpisearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
			});
	}
	pisearchGrid(data)
	{
		
		$('#explcscpisearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		let formObj={};
		formObj.exp_lc_sc_id=$('#explcscFrm  [name=id]').val();
		let i=1;
		$.each($('#explcscpisearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['exp_pi_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
}
window.MsExpLcScPi=new MsExpLcScPiController(new MsExpLcScPiModel());
MsExpLcScPi.pisearchGrid({rows:{}})


