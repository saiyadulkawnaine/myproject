let MsLocalExpLcTagPiModel = require('./MsLocalExpLcTagPiModel');
class MsLocalExpLcTagPiController {
	constructor(MsLocalExpLcTagPiModel)
	{
		this.MsLocalExpLcTagPiModel = MsLocalExpLcTagPiModel;
		this.formId='localexplctagpiFrm';
		this.dataTable='#localexplctagpiTbl';
		this.route=msApp.baseUrl()+"/localexplctagpi"
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
			this.MsLocalExpLcTagPiModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpLcTagPiModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpLcTagPiModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpLcTagPiModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexplctagpiTbl').datagrid('reload');
		msApp.resetForm('localexplctagpiFrm');
        $('#localexplctagpiFrm  [name=local_exp_lc_id]').val($('#localexplc  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsLocalExpLcTagPiModel.get(index,row);
	}

	showGrid(local_exp_lc_id)
	{
		let self=this;
        var data={};
		data.local_exp_lc_id=local_exp_lc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
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
		return '<a href="javascript:void(0)"  onClick="MsLocalExpLcTagPi.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	importLcPi()
	{
		let pi_no=$('#lctagpisearchFrm  [name=pi_no]').val();
		let localexppiid=$('#localexplcFrm  [name=id]').val();

		let data= axios.get(this.route+"/importlocalpi"+"?pi_no="+pi_no+"&localexppiid="+localexppiid)
		.then(function (response) {
			$('#localexplctagpisearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	pisearchGrid(data)
	{
		
		$('#localexplctagpisearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		let formObj={};
		formObj.local_exp_lc_id=$('#localexplcFrm  [name=id]').val();
		let i=1;
		$.each($('#localexplctagpisearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['local_exp_pi_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
}
window.MsLocalExpLcTagPi=new MsLocalExpLcTagPiController(new MsLocalExpLcTagPiModel());
MsLocalExpLcTagPi.pisearchGrid({rows:{}})
