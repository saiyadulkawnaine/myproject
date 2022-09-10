let MsExpRepLcModel = require('./MsExpRepLcModel');
class MsExpRepLcController {
	constructor(MsExpRepLcModel)
	{
		this.MsExpRepLcModel = MsExpRepLcModel;
		this.formId='expreplcFrm';
		this.dataTable='#expreplcTbl';
		this.route=msApp.baseUrl()+"/expreplc"
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
			this.MsExpRepLcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpRepLcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#expreplcFrm  [name=exp_lc_sc_id]').val($('#explcFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpRepLcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpRepLcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#expreplcTbl').datagrid('reload');
		msApp.resetForm('expreplcFrm');
		MsExpRepLc.get($('#explcFrm  [name=id]').val());
        $('#expreplcFrm  [name=exp_lc_sc_id]').val($('#explcFrm  [name=id]').val());
	}

	get(exp_lc_sc_id){
		let data= axios.get(this.route+"?exp_lc_sc_id="+exp_lc_sc_id);
		data.then(function (response) {
			$('#expreplcTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

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
				var replaced_amount=0;
				var total_replaced=0;
				var balance=0;
				for(var i=0; i<data.rows.length; i++){
					lc_sc_value+=data.rows[i]['lc_sc_value'].replace(/,/g,'')*1;
					replaced_amount+=data.rows[i]['replaced_amount'].replace(/,/g,'')*1;
					total_replaced+=data.rows[i]['total_replaced'].replace(/,/g,'')*1;
					balance+=data.rows[i]['balance']*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					lc_sc_value: lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					replaced_amount: replaced_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_replaced: total_replaced.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance: balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
	
	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpRepLcModel.get(index,row);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpRepLc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openRepLcWindow(){
		$('#expreplcwindow').window('open');
		MsExpRepLc.importRepLc();
	}

	importRepLc()
	{
		    let file_no=$('#explcFrm  [name=file_no]').val();
		    let lc_sc_no=$('#expreplcsearchFrm  [name=lc_sc_no]').val();
			let beneficiary_id=$('#expreplcsearchFrm  [name=beneficiary_id]').val();
			let buyer_id=$('#expreplcsearchFrm  [name=buyer_id]').val();

			let data= axios.get(this.route+"/importreplc?file_no="+file_no+"&lc_sc_no="+lc_sc_no+"&beneficiary_id="+beneficiary_id+"&buyer_id="+buyer_id)
			.then(function (response) {
				$('#expreplcsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
			});
	}

	replcsearchGrid(data)
	{
		let self=this;
		$('#expreplcsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			onClickRow: function(index,row){
				$('#expreplcFrm  [name=lc_sc_no]').val(row.lc_sc_no);
				$('#expreplcFrm  [name=replaced_lc_sc_id]').val(row.id);
				$('#expreplcFrm  [name=lc_sc_value]').val(row.lc_sc_value);
				$('#expreplcFrm  [name=total_replaced]').val(row.total_replaced);
				$('#expreplcFrm  [name=balance]').val(row.balance);
				$('#expreplcFrm  [name=lc_sc_date]').val(row.lc_sc_date);
				$('#expreplcFrm  [name=buyer_id]').val(row.buyer);
				$('#expreplcwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsExpRepLc=new MsExpRepLcController(new MsExpRepLcModel());
MsExpRepLc.showGrid([]);
MsExpRepLc.replcsearchGrid([]);