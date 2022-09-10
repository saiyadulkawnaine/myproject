let MsProdKnitModel = require('./MsProdKnitModel');
require('./../../datagrid-filter.js');
class MsProdKnitController {
	constructor(MsProdKnitModel)
	{
		this.MsProdKnitModel = MsProdKnitModel;
		this.formId='prodknitFrm';
		this.dataTable='#prodknitTbl';
		this.route=msApp.baseUrl()+"/prodknit"
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
			this.MsProdKnitModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#prodknitFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodknitTbl').datagrid('reload');
		msApp.resetForm('prodknitFrm');
		$('#prodknitFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdKnitModel.get(index,row);
		carton.then(function(response){
			$('#prodknitFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdKnit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	search(){
		//let qc_date=$('#prodknitqcFrm [name=qc_date]').val();
		let from_prod_date=$('#from_prod_date').val();
		let to_prod_date=$('#to_prod_date').val();
		let params={};

		params.from_prod_date=from_prod_date;
		params.to_prod_date=to_prod_date;
		if(!params.from_prod_date){
			alert('Select Prod Date');
			return;
		}
		if(!params.to_prod_date){
			alert('Select Prod Date');
			return;
		}
		let data= axios.get(this.route+'/search',{params})
		.then(function (response) {
			$('#prodknitTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
}
window.MsProdKnit=new MsProdKnitController(new MsProdKnitModel());
MsProdKnit.showGrid();
 $('#prodknittabs').tabs({
	onSelect:function(title,index){
	 let prod_knit_id = $('#prodknitFrm  [name=id]').val();
	 let prod_knit_item_id = $('#prodknititemFrm  [name=id]').val();
	 var data={};
	  data.prod_knit_id=prod_knit_id;

	 if(index==1){
		 if(prod_knit_id===''){
			 $('#prodknittabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodknititemFrm');
		  $('#prodknititemFrm  [name=prod_knit_id]').val(prod_knit_id);
		  MsProdKnitItem.get(prod_knit_id);
	  }
	  if(index==2){
		 if(prod_knit_item_id===''){
			 $('#prodknittabs').tabs('select',1);
			 msApp.showError('Select a Item First',1);
			 return;
		  }
		  msApp.resetForm('prodknititemrollFrm');
		  $('#prodknititemrollFrm  [name=prod_knit_item_id]').val(prod_knit_item_id);
		  $('#fabrication_td').html($('#prodknititemFrm  [name=fabrication]').val())
          MsProdKnitRollItem.get(prod_knit_item_id);
		  
	  }

	  if(index==3){
		 if(prod_knit_item_id===''){
			 $('#prodknittabs').tabs('select',1);
			 msApp.showError('Select a Item First',1);
			 return;
		  }
		  msApp.resetForm('prodknititemyarnFrm');
		  $('#prodknititemyarnFrm  [name=prod_knit_item_id]').val(prod_knit_item_id);
		  $('#fabrication_yarn_td').html($('#prodknititemFrm  [name=fabrication]').val())
          MsProdKnitItemYarn.get(prod_knit_item_id);
		  
	  }
   }
}); 
