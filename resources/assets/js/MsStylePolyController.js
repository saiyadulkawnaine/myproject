

let MsStylePolyModel = require('./MsStylePolyModel');
class MsStylePolyController {
	constructor(MsStylePolyModel)
	{
		this.MsStylePolyModel = MsStylePolyModel;
		this.formId='stylepolyFrm';
		this.dataTable='#stylepolyTbl';
		this.route=msApp.baseUrl()+"/stylepoly"
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
			this.MsStylePolyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStylePolyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStylePolyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStylePolyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylepolyTbl').datagrid('reload');
		$('#stylepolyFrm  [name=id]').val(d.id);
		msApp.resetForm('stylepolyratioFrm');
		$('#stylepolyratioFrm  [name=style_poly_id]').val(d.id);
		$('#stylepolyratioFrm  [name=style_id]').val($('#stylepolyFrm  [name=style_id]'));
		this.getStyleGmts($('#stylepolyFrm  [name=style_id]'));
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStylePolyModel.get(index,row);
		msApp.resetForm('stylepolyratioFrm');
		$('#stylepolyratioFrm  [name=style_poly_id]').val(row.id);
		$('#stylepolyratioFrm  [name=style_id]').val(row.style_id);
		this.getStyleGmts(row.style_id);
		MsStylePolyRatio.showGrid(row.id);
	}

	showGrid(style_id)
	{
		let self=this;
		var data={};
		data.style_id=style_id;
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStylePoly.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getStyleGmts (style_id){
	    let data={};
		data.style_id=style_id;
		msApp.getJson('stylegmts',data)
		.then(function (response) {
			    $('select[name="style_gmt_id"]').empty();
				$('select[name="style_gmt_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
                $('select[name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	openRatioWindow(){
		var id= $('#stylepolyFrm  [name=id]').val();
		axios.get(msApp.baseUrl()+'/stylepolyratio/'+id)
		  .then(function (response) {
			   $('#cccc').html(response.data)
			  $('#polyratio').window('open')
			//alert(response.data);
		  })
		  .catch(function (error) {
			console.log(error);
		  });
	}
}
window.MsStylePoly=new MsStylePolyController(new MsStylePolyModel());
//MsStylePoly.showGrid();
