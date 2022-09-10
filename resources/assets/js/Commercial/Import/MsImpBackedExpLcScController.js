let MsImpBackedExpLcScModel = require('./MsImpBackedExpLcScModel');
class MsImpBackedExpLcScController {
	constructor(MsImpBackedExpLcScModel)
	{
		this.MsImpBackedExpLcScModel = MsImpBackedExpLcScModel;
		this.formId='impbackedexplcscFrm';
		this.dataTable='#impbackedexplcscTbl';
		this.route=msApp.baseUrl()+"/impbackedexplcsc"
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
			this.MsImpBackedExpLcScModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpBackedExpLcScModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpBackedExpLcScModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpBackedExpLcScModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impbackedexplcscTbl').datagrid('reload');
		msApp.resetForm('impbackedexplcscFrm');
      $('#impbackedexplcscFrm  [name=imp_lc_id]').val($('#implc  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsImpBackedExpLcScModel.get(index,row);
	}

	showGrid(imp_lc_id)
	{
		let self=this;
      var data={};
		data.imp_lc_id=imp_lc_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
         queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var lc_sc_value=0;
				for(var i=0; i<data.rows.length; i++){
					lc_sc_value+=data.rows[i]['lc_sc_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						lc_sc_value: lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')	}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpBackedExpLcSc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	importlcsc()
	{
			let lc_sc_no=$('#impbackedexplcscFrm  [name=lc_sc_no]').val();
			let lc_sc_date=$('#impbackedexplcscFrm  [name=lc_sc_date]').val();
			let implcid=$('#implcFrm  [name=id]').val();

			let data= axios.get(this.route+"/importlcsc"+"?lc_sc_no="+lc_sc_no+"&lc_sc_date="+lc_sc_date+"&implcid="+implcid)
			.then(function (response) {
				$('#impbackedexplcscsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
			});
	}
	lcscsearchGrid(data)
	{
		
		$('#impbackedexplcscsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	getSelections()
	{
		let formObj={};
		formObj.imp_lc_id=$('#implcFrm  [name=id]').val();
		let i=1;
		let total_lc_sc_value=0;
		$.each($('#impbackedexplcscsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['exp_lc_sc_id['+i+']']=val.id
			formObj['lc_sc_value['+i+']']=val.lc_sc_value
			total_lc_sc_value+=(val.lc_sc_value*1)
			i++;
		});
		formObj.total_lc_sc_value=total_lc_sc_value;
		return formObj;
	}
}
window.MsImpBackedExpLcSc=new MsImpBackedExpLcScController(new MsImpBackedExpLcScModel());
MsImpBackedExpLcSc.lcscsearchGrid({rows:{}})