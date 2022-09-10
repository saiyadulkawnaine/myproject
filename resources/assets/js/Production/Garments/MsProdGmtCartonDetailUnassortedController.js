let MsProdGmtCartonDetailUnassortedModel = require('./MsProdGmtCartonDetailUnassortedModel');

class MsProdGmtCartonDetailUnassortedController {
	constructor(MsProdGmtCartonDetailUnassortedModel)
	{
		this.MsProdGmtCartonDetailUnassortedModel = MsProdGmtCartonDetailUnassortedModel;
		this.formId='prodgmtcartondetailunassortedFrm';
		this.dataTable='#prodgmtcartondetailunassortedTbl';
		this.route=msApp.baseUrl()+"/prodgmtcartondetailunassorted"
	}

	submit()
	{
		let prod_gmt_carton_entry_id=$('#prodgmtcartonentryFrm [name=id]').val();
		let mstformObj=msApp.get(this.formId);
		let formObj=msApp.get('prodgmtcartonunassortedpkgratioFrm');
		formObj.prod_gmt_carton_entry_id=mstformObj.prod_gmt_carton_entry_id;
		formObj.style_id=mstformObj.style_id;
		formObj.id=mstformObj.id;
		formObj.style_pkg_id=mstformObj.style_pkg_id;
		formObj.itemclass_id=62;
		formObj.sales_order_country_id=mstformObj.sales_order_country_id;
		formObj.ctqty=mstformObj.qty;

		/*if(formObj.style_id=='')
    	{
    		alert('Please select Order No ');
    		return;
    	}
    	if(formObj.ctqty=='' || formObj.ctqty==0)
    	{
    		alert('Please input Curr. Carton Qty ');
    		return;
    	}*/
		if(formObj.id){
			this.MsProdGmtCartonDetailUnassortedModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCartonDetailUnassortedModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm('prodgmtcartondetailunassortedFrm');
		$('#prodgmtcartonunassortedpkgcs').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCartonDetailUnassortedModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCartonDetailUnassortedModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('prodgmtcartondetailunassortedFrm');
		$('#prodgmtcartonunassortedpkgcs').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let params={};
		params.style_pkg_id=row.style_pkg_id;
		params.prod_gmt_carton_entry_id=row.id;
		let d= axios.get(this.route+'/create',{params})
		.then(function (response) {
			msApp.set(index,row,response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(prod_gmt_carton_entry_id){
		let self=this;
		var data={};
		data.prod_gmt_carton_entry_id=prod_gmt_carton_entry_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCartonDetailUnassorted.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    loadRatioForm()
    {
    	let params={};
    	let style_id=$('#prodgmtcartondetailunassortedFrm [name=style_id]').val();
    	let style_pkg_id=$('#prodgmtcartondetailunassortedFrm [name=style_pkg_id]').val();
    	params.style_id=style_id;
    	params.style_pkg_id=style_pkg_id;
    	if(params.style_id=='')
    	{
    		alert('select Order No ');
    		return;
    	}
		let d= axios.get(this.route+'/getpkgratio',{params})
		.then(function (response) {
			$('#prodgmtcartonunassortedpkgcs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }
}
window.MsProdGmtCartonDetailUnassorted=new MsProdGmtCartonDetailUnassortedController(new MsProdGmtCartonDetailUnassortedModel());