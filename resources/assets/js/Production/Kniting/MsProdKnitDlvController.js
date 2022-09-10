let MsProdKnitDlvModel = require('./MsProdKnitDlvModel');
require('./../../datagrid-filter.js');
class MsProdKnitDlvController {
	constructor(MsProdKnitDlvModel)
	{
		this.MsProdKnitDlvModel = MsProdKnitDlvModel;
		this.formId='prodknitdlvFrm';
		this.dataTable='#prodknitdlvTbl';
		this.route=msApp.baseUrl()+"/prodknitdlv"
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
			this.MsProdKnitDlvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitDlvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodknitdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitDlvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitDlvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodknitdlvTbl').datagrid('reload');
		msApp.resetForm('prodknitdlvFrm');
		$('#prodknitdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdKnitDlvModel.get(index,row);
		carton.then(function(response){
			$('#prodknitdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdKnitDlv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf()
	{
		var id= $('#prodknitdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	challan()
	{
		var id= $('#prodknitdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getchallan?id="+id);
	}

	bill()
	{
		var id= $('#prodknitdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/bill?id="+id);
	}

	searchProdKint()
	{
		let params = {};
		params.company_search_id = $('#company_search_id').val();
		params.store_search_id = $('#store_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/searchprodkint", { params });
		data.then(function (resources)
		{
			$('#prodknitdlvTbl').datagrid('loadData', resources.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
}
window.MsProdKnitDlv=new MsProdKnitDlvController(new MsProdKnitDlvModel());
MsProdKnitDlv.showGrid();
 $('#prodknitdlvtabs').tabs({
	onSelect:function(title,index){
	 let prod_knit_dlv_id = $('#prodknitdlvFrm  [name=id]').val();

	 if(index==1){
		 if(prod_knit_dlv_id===''){
			 $('#prodknitdlvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  /*msApp.resetForm('prodknititemFrm');
		  $('#prodknititemFrm  [name=prod_knit_id]').val(prod_knit_id);*/
		  MsProdKnitDlvRoll.get(prod_knit_dlv_id);
	  }
   }
}); 
