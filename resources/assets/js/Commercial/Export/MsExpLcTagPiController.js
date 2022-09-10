let MsExpLcTagPiModel = require('./MsExpLcTagPiModel');
class MsExpLcTagPiController {
	constructor(MsExpLcTagPiModel)
	{
		this.MsExpLcTagPiModel = MsExpLcTagPiModel;
		this.formId='explctagpiFrm';
		this.dataTable='#explctagpiTbl';
		this.route=msApp.baseUrl()+"/explctagpi"
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
			this.MsExpLcTagPiModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcTagPiModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcTagPiModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcTagPiModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explctagpiTbl').datagrid('reload');
		msApp.resetForm('explctagpiFrm');
        $('#explctagpiFrm  [name=exp_lc_id]').val($('#explc  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpLcTagPiModel.get(index,row);
	}

	showGrid(exp_lc_id)
	{
		let self=this;
        var data={};
		data.exp_lc_id=exp_lc_id;
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
		return '<a href="javascript:void(0)"  onClick="MsExpLcTagPi.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	importPi()
	{
		let pi_no=$('#lctagpisearchFrm  [name=pi_no]').val();
		let style_ref=$('#lctagpisearchFrm  [name=style_ref]').val();
		let job_no=$('#lctagpisearchFrm  [name=job_no]').val();
		let order_no=$('#lctagpisearchFrm  [name=order_no]').val();
		let expsaleconid=$('#explcFrm  [name=id]').val();

		let data= axios.get(this.route+"/importpi"+"?pi_no="+pi_no+"&style_ref="+style_ref+"&job_no="+job_no+"&order_no="+order_no+"&expsaleconid="+expsaleconid)
		.then(function (response) {
			$('#explctagpisearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
		console.log(error);
		});
	}
	pisearchGrid(data)
	{
		
		$('#explctagpisearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		let formObj={};
		formObj.exp_lc_sc_id=$('#explcFrm  [name=id]').val();
		let i=1;
		$.each($('#explctagpisearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['exp_pi_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
}
window.MsExpLcTagPi=new MsExpLcTagPiController(new MsExpLcTagPiModel());
MsExpLcTagPi.pisearchGrid({rows:{}})
