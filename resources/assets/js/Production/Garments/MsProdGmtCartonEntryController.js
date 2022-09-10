let MsProdGmtCartonEntryModel = require('./MsProdGmtCartonEntryModel');
require('./../../datagrid-filter.js');
class MsProdGmtCartonEntryController {
	constructor(MsProdGmtCartonEntryModel)
	{
		this.MsProdGmtCartonEntryModel = MsProdGmtCartonEntryModel;
		this.formId='prodgmtcartonentryFrm';
		this.dataTable='#prodgmtcartonentryTbl';
		this.route=msApp.baseUrl()+"/prodgmtcartonentry"
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
			this.MsProdGmtCartonEntryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCartonEntryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtcartonentryFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCartonEntryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCartonEntryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtcartonentryTbl').datagrid('reload');
		msApp.resetForm('prodgmtcartonentryFrm');
		$('#prodgmtcartonentryFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdGmtCartonEntryModel.get(index,row);
		carton.then(function(response){
			$('#prodgmtcartonentryFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
	}

	showGrid(){

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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCartonEntry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	
}
window.MsProdGmtCartonEntry=new MsProdGmtCartonEntryController(new MsProdGmtCartonEntryModel());
MsProdGmtCartonEntry.showGrid();

 $('#prodgmtcartontabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_carton_entry_id = $('#prodgmtcartonentryFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_carton_entry_id=prod_gmt_carton_entry_id;

	 if(index==1){
		 if(prod_gmt_carton_entry_id===''){
			 $('#prodgmtcartontabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtcartondetailFrm');
		  $('#prodgmtcartondetailFrm  [name=prod_gmt_carton_entry_id]').val(prod_gmt_carton_entry_id);
		  MsProdGmtCartonDetail.showGrid(prod_gmt_carton_entry_id);
	  }

	  if(index==2){
		 if(prod_gmt_carton_entry_id===''){
			 $('#prodgmtcartontabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtcartondetailunassortedFrm');
		  $('#prodgmtcartonunassortedpkgcs').html('');

		  $('#prodgmtcartondetailunassortedFrm  [name=prod_gmt_carton_entry_id]').val(prod_gmt_carton_entry_id);
		  MsProdGmtCartonDetailUnassorted.showGrid(prod_gmt_carton_entry_id);
	  }
   }
}); 
