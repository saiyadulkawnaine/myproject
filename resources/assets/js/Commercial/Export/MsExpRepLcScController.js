let MsExpRepLcScModel = require('./MsExpRepLcScModel');
class MsExpRepLcScController {
	constructor(MsExpRepLcScModel)
	{
		this.MsExpRepLcScModel = MsExpRepLcScModel;
		this.formId='expreplcscFrm';
		this.dataTable='#expreplcscTbl';
		this.route=msApp.baseUrl()+"/expreplcsc"
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
			this.MsExpRepLcScModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpRepLcScModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#expreplcscFrm  [name=exp_lc_sc_id]').val($('#explcscFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpRepLcScModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpRepLcScModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	get(exp_lc_sc_id){
		let data= axios.get(this.route+"?exp_lc_sc_id="+exp_lc_sc_id);
		data.then(function (response) {
			$('#expreplcscTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	response(d)
	{
		//$('#expreplcscTbl').datagrid('reload');
		msApp.resetForm('expreplcscFrm');
		MsExpRepLcSc.get($('#explcscFrm  [name=id]').val());
        $('#expreplcscFrm  [name=exp_lc_sc_id]').val($('#explcscFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpRepLcScModel.get(index,row);
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var lc_sc_value=0;
				var total_replaced=0;
				var balance=0;
				for(var i=0; i<data.rows.length; i++){
					lc_sc_value+=data.rows[i]['lc_sc_value'].replace(/,/g,'')*1;
					total_replaced+=data.rows[i]['total_replaced'].replace(/,/g,'')*1;
					balance+=data.rows[i]['balance']*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					lc_sc_value: lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_replaced: total_replaced.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance: balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpRepLcSc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openRepScWindow(){
		$('#exprepscwindow').window('open');
		MsExpRepLcSc.importRepSc();
	}

	importRepSc()
	{
	    let file_no=$('#explcscFrm  [name=file_no]').val();
	    let lc_sc_no=$('#exprepscsearchFrm  [name=lc_sc_no]').val();
		let beneficiary_id=$('#exprepscsearchFrm  [name=beneficiary_id]').val();
		let buyer_id=$('#exprepscsearchFrm  [name=buyer_id]').val();

		let data= axios.get(this.route+"/importrepsc"+"?file_no="+file_no+"&lc_sc_no="+lc_sc_no+"&beneficiary_id="+beneficiary_id+"&buyer_id="+buyer_id)
		.then(function (response) {
			$('#exprepscsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	repscsearchGrid(data)
	{	
		let self=this;
		$('#exprepscsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			onClickRow: function(index,row){
				$('#expreplcscFrm  [name=lc_sc_no]').val(row.lc_sc_no);
				$('#expreplcscFrm  [name=replaced_lc_sc_id]').val(row.id);
				$('#expreplcscFrm  [name=lc_sc_value]').val(row.lc_sc_value);
				$('#expreplcscFrm  [name=total_replaced]').val(row.total_replaced);
				$('#expreplcscFrm  [name=balance]').val(row.balance);
				$('#expreplcscFrm  [name=lc_sc_date]').val(row.lc_sc_date);
				$('#expreplcscFrm  [name=buyer_id]').val(row.buyer);
				$('#exprepscwindow').window('close');		
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsExpRepLcSc=new MsExpRepLcScController(new MsExpRepLcScModel());
MsExpRepLcSc.showGrid([]);
MsExpRepLcSc.repscsearchGrid([]);