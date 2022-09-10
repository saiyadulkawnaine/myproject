let MsLocalExpDocSubTransModel = require('./MsLocalExpDocSubTransModel');
class MsLocalExpDocSubTransController {
	constructor(MsLocalExpDocSubTransModel)
	{
		this.MsLocalExpDocSubTransModel = MsLocalExpDocSubTransModel;
		this.formId='localexpdocsubtransFrm';
		this.dataTable='#localexpdocsubtransTbl';
		this.route=msApp.baseUrl()+"/localexpdocsubtrans"
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
			this.MsLocalExpDocSubTransModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpDocSubTransModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#localexpdocsubtransFrm [name=local_exp_doc_sub_bank_id]').val($('#localexpdocsubbankFrm [name=id]').val());
		$('#localexpdocsubtransFrm [id="commercialhead_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpDocSubTransModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpDocSubTransModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpdocsubtransTbl').datagrid('reload');
		msApp.resetForm('localexpdocsubtransFrm');
		$('#localexpdocsubtransFrm [name=local_exp_doc_sub_bank_id]').val($('#localexpdocsubbankFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let trans=this.MsLocalExpDocSubTransModel.get(index,row);	
		trans.then(function(response){
			$('#localexpdocsubtransFrm [id="commercialhead_id"]').combobox('setValue', response.data.fromData.commercialhead_id);
		});

	}

	showGrid(local_exp_doc_sub_bank_id)
	{
		let self=this;
		var data={};
		data.local_exp_doc_sub_bank_id=local_exp_doc_sub_bank_id;
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
				{ 
					dom_value: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					doc_value: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubTrans.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(){
		    var radioValue = $("input[name='aa']:checked").val();
		    let dom_value=$("#localexpdocsubtransFrm [name='dom_value']").val();
		    let doc_value=$("#localexpdocsubtransFrm [name='doc_value']").val();
		    let exch_rate=$("#localexpdocsubtransFrm [name='exch_rate']").val();
		    let value

            if(radioValue==3)
            {
            	value=(doc_value*1*exch_rate*1)
                $("#localexpdocsubtransFrm [name='dom_value']").val(value)
            }
            if(radioValue==2)
            {
                value=(dom_value*1)/(doc_value*1)
                $("#localexpdocsubtransFrm [name='exch_rate']").val(value) ;
            }
            if(radioValue==1)
            {
                value=(dom_value*1)/(exch_rate*1);
                $("#localexpdocsubtransFrm [name='doc_value']").val(value)
            }
	}

}
window.MsLocalExpDocSubTrans=new MsLocalExpDocSubTransController(new MsLocalExpDocSubTransModel());