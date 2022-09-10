//require('./jquery.easyui.min.js');
let MsBuyerModel = require('./MsBuyerModel');
require('./datagrid-filter.js');

class MsBuyerController {
	constructor(MsBuyerModel)
	{
		this.MsBuyerModel = MsBuyerModel;
		this.formId='buyerFrm';
		this.dataTable='#buyerTbl';
		this.route=msApp.baseUrl()+"/buyer"
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
			this.MsBuyerModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#buyerTbl').datagrid('reload');
		//$('#BuyerFrm  [name=id]').val(d.id);
		msApp.resetForm('buyerFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBuyer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	getTeamMember (team_id){
		let data={};
		data.team_id=team_id;
		msApp.getJson('teammember',data)
		.then(function (response) {
			$('select[name="teammember_id"]').empty();
			$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
			$.each(response.data, function(key, value) {
			if(value.type_id==3){
			$('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			}
			});
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsBuyer=new MsBuyerController(new MsBuyerModel());
MsBuyer.showGrid();

$('#utilbuyertabs').tabs({
        onSelect:function(title,index){
		   let buyer_id = $('#buyerFrm  [name=id]').val();

			var data={};
		    data.buyer_id=buyer_id;

			if(index==1){
				if(buyer_id===''){
					$('#utilbuyertabs').tabs('select',0);
					msApp.showError('Select Buyer First',0);
					return;
			    }
				$('#companybuyerFrm  [name=buyer_id]').val(buyer_id)
				MsCompanyBuyer.create()
			}
			if(index==2){
				if(buyer_id===''){
					$('#utilbuyertabs').tabs('select',0);
					msApp.showError('Select Buyer First',0);
					return;
			    }
				$('#buyernatureFrm  [name=buyer_id]').val(buyer_id)
				MsBuyerNature.create()
            }
			
			if(index==3){
				if(buyer_id===''){
					$('#utilbuyertabs').tabs('select',0);
					msApp.showError('Select Buyer First',0);
					return;
			    }
				msApp.resetForm('buyerbranchFrm');
				$('#buyerbranchFrm  [name=buyer_id]').val(buyer_id)
				MsBuyerBranch.showGrid(buyer_id);
            }
			
    }
 });
