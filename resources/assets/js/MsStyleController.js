//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsStyleModel = require('./MsStyleModel');
class MsStyleController {
	constructor(MsStyleModel)
	{
		this.MsStyleModel = MsStyleModel;
		this.formId='styleFrm';
		this.dataTable='#styleTbl';
		this.route=msApp.baseUrl()+"/style"
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
			this.MsStyleModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#styleFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#styleTbl').datagrid('reload');
		$('#styleFrm  [name=id]').val(d.id);
		$('#styleFrm [id="buyer_id"]').combobox('setValue', '');
		//$('#po_w').window('open')
		msApp.resetForm('stylegmtsFrm');
		$('#stylegmtsFrm  [name=style_id]').val(d.id);

		msApp.resetForm('stylepolyFrm');
		$('#stylepolyFrm  [name=style_id]').val(d.id);

		msApp.resetForm('stylepkgFrm');
		$('#stylepkgFrm  [name=style_id]').val(d.id);

		msApp.resetForm('styleevaluationFrm');
		$('#styleevaluationFrm  [name=style_id]').val(d.id);

	}

	edit(index,row)
	{
		let self=this;
		row.route=this.route;
		row.formId=this.formId;
		let style=this.MsStyleModel.get(index,row);
		style.then(function (response) {
			$('#styleFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			let Presponse=response
			self.getTeamMember (response.data.fromData.team_id)
			.then(function(){
				msApp.set(index,row,Presponse.data)
			})
		})
		.catch(function (error) {
			console.log(error);
		});

		msApp.resetForm('stylegmtsFrm');
		$('#stylegmtsFrm  [name=style_id]').val(row.id);
		//MsStyleGmts.showGrid(row.id);
		
		//MsStyleEmbelishment.showGrid(row.id);
		//MsStyleColor.showGrid(row.id);
		//MsStyleSize.showGrid(row.id);

		msApp.resetForm('stylepolyFrm');
		$('#stylepolyFrm  [name=style_id]').val(row.id);
		//MsStylePoly.showGrid(row.id);

		msApp.resetForm('stylepkgFrm');
		$('#stylepkgFrm  [name=style_id]').val(row.id);
		//MsStylePkg.showGrid(row.id);

		msApp.resetForm('styleevaluationFrm');
		$('#styleevaluationFrm  [name=style_id]').val(row.id);
		//MsStyleEvaluation.showGrid(row.id);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			fit:true,
			singleSelect:true,
			url:this.route,
			//pagination:true,
			//pageSize:100,
			//pageList:[100,200,300,400,500],
			//remoteFilter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyle.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	getTeamMember (team_id){
		let data={};
		data.team_id=team_id;
		let team=msApp.getJson('teammember',data)
		.then(function (response) {
			    $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
				$('select[name="factory_merchant_id"]').empty();
				$('select[name="factory_merchant_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
					if(value.type_id==1 || value.type_id==2){
						$('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					}
					else if(value.type_id==3){
						$('select[name="factory_merchant_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					}
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return team;
	}

	openStyleWindow(){
		$('#stylesearchWindow').window('open');
	}
	getStyleParams(){
		let params={};
		params.buyer_id = $('#stylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#stylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#stylesearchFrm  [name=style_description]').val();
		return params;
	}

	searchStyle(){
		let params=this.getStyleParams();
		let st= axios.get(this.route+"/getoldstyle",{params})
		.then(function(response){
			$('#stylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showStyleGrid(data){
		let self=this;
		$('#stylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				//$('#styleFrm [name=style_ref]').val(row.style_ref);
				$('#styleFrm [name=id]').val(row.id);
				$('#styleFrm [id="buyer_id"]').combobox('setValue', row.buyer_id);
				$('#styleFrm [name=receive_date]').val(row.receive_date);
				$('#styleFrm [name=style_ref]').val(row.style_ref);
				$('#styleFrm [name=style_description]').val(row.style_description);
				$('#styleFrm [name=dept_category_id]').val(row.dept_category_id);
				$('#styleFrm [name=productdepartment_id]').val(row.productdepartment_id);
				$('#styleFrm [name=product_code]').val(row.product_code);
				$('#styleFrm [name=offer_qty]').val(row.offer_qty);
				$('#styleFrm [name=ship_date]').val(row.ship_date);
				$('#styleFrm [name=season_id]').val(row.season_id);
				$('#styleFrm [name=uom_id]').val(row.uom_id);
				$('#styleFrm [name=team_id]').val(row.team_id);
				$('#styleFrm [name=teammember_id]').val(row.teammember_id);
				$('#styleFrm [name=buyer_ref]').val(row.buyer_ref);
				$('#styleFrm [name=factory_merchant_id]').val(row.factory_merchant_id);
				$('#styleFrm [name=buying_agent_id]').val(row.buying_agent_id);
				$('#styleFrm [name=remarks]').val(row.remarks);
				$('#styleFrm [name=contact]').val(row.contact);
				$('#styleFrm [name=file_name]').val(row.file_name);
				$('#stylesearchTbl').datagrid('loadData', []);
				$('#stylesearchWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsStyle=new MsStyleController(new MsStyleModel());
MsStyle.showGrid();
MsStyle.showStyleGrid([]);

    

    $('#styletabs').tabs({
        onSelect:function(title,index){
           // alert(title+' is selected'+index);
		   let style_id = $('#styleFrm  [name=id]').val();
		   let style_ref = $('#styleFrm  [name=style_ref]').val();

			var data={};
		    data.style_id=style_id;

			if(index==1){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylecolorFrm');
				$('#stylecolorFrm  [name=style_ref]').val(style_ref)
				$('#stylecolorFrm  [name=style_id]').val(style_id)
				MsStyleColor.showGrid(style_id);
			}
			if(index==2){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylesizeFrm');
				$('#stylesizeFrm  [name=style_ref]').val(style_ref)
				$('#stylesizeFrm  [name=style_id]').val(style_id)
				MsStyleSize.showGrid(style_id);
			}
			if(index==3){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylegmtsFrm');
				$('#stylegmtsFrm  [name=style_ref]').val(style_ref)
				$('#stylegmtsFrm  [name=style_id]').val(style_id)
				MsStyleGmts.showGrid(style_id);
			}
			if(index==4){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('styleembelishmentFrm');
				let stylegmts = msApp.getJson('stylegmts',data);
				$('#styleembelishmentFrm  [name=style_ref]').val(style_ref)
				$('#styleembelishmentFrm  [name=style_id]').val(style_id)
				MsStyleEmbelishment.showGrid(style_id);
				stylegmts.then(function (response) {
				$('#styleembelishmentFrm [name="style_gmt_id"]').empty();
				$('#styleembelishmentFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#styleembelishmentFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
			}
			if(index==5){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylefabricationFrm');
			    msApp.resetForm('stylefabricationstripeFrm');
				$('#stylefabricationFrm  [name=style_ref]').val(style_ref)
				$('#stylefabricationFrm  [name=style_id]').val(style_id)
				MsStyleFabrication.showGrid(style_id);
				let stylegmts = msApp.getJson('stylegmts',data);
				stylegmts.then(function (response) {
				$('#stylefabricationFrm [name="style_gmt_id"]').empty();
				$('#stylefabricationFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#stylefabricationFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
			}
			if(index==6){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylesizemsureFrm');
			    msApp.resetForm('stylesizemsurevalFrm');
				$('#stylesizemsureFrm  [name=style_ref]').val(style_ref)
				$('#stylesizemsureFrm  [name=style_id]').val(style_id)
				MsStyleSizeMsure.showGrid(style_id);
				let stylegmts = msApp.getJson('stylegmts',data);
				stylegmts.then(function (response) {
				$('#stylesizemsureFrm [name="style_gmt_id"]').empty();
				$('#stylesizemsureFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#stylesizemsureFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
			}
			if(index==7){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylesampleFrm');
			    msApp.resetForm('stylesamplecsFrm');
			    
				$('#stylesampleFrm  [name=style_ref]').val(style_ref)
				$('#stylesampleFrm  [name=style_id]').val(style_id)
				MsStyleSample.showGrid(style_id);
				let stylegmts = msApp.getJson('stylegmts',data);
				stylegmts.then(function (response) {
				$('#stylesampleFrm [name="style_gmt_id"]').empty();
				$('#stylesampleFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#stylesampleFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
			}

			if(index==8){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylepolyFrm');
			    msApp.resetForm('stylepolyratioFrm');
				$('#stylepolyFrm  [name=style_ref]').val(style_ref)
				$('#stylepolyFrm  [name=style_id]').val(style_id)
				MsStylePoly.showGrid(style_id);
				let stylegmts = msApp.getJson('stylegmts',data);
				stylegmts.then(function (response) {
				$('#stylepolyratioFrm [name="style_gmt_id"]').empty();
				$('#stylepolyratioFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#stylepolyratioFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
			}
			if(index==9){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylepkgFrm');
			    msApp.resetForm('stylepkgratioFrm');
			    $('#pkgcs').html('');
				$('#stylepkgFrm  [name=style_ref]').val(style_ref)
				$('#stylepkgFrm  [name=style_id]').val(style_id)
				$('#stylepkgFrm  [name=itemclass_id]').val(62)
				MsStylePkg.showGrid(style_id);
			}
			if(index==10){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('styleevaluationFrm');
				$('#styleevaluationFrm  [name=style_ref]').val(style_ref)
				$('#styleevaluationFrm  [name=style_id]').val(style_id)
				MsStyleEvaluation.showGrid(style_id);
			}
			if(index==11){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('styleimageFrm');
				$('#styleimageFrm  [name=style_ref]').val(style_ref)
				$('#styleimageFrm  [name=style_id]').val(style_id)
				let data= axios.get(msApp.baseUrl()+"/style/"+style_id+"/edit");
					let g=data.then(function (response) {
					//alert(response.data.fromData.identity)
					//$('#itemaccountFrm  [name=identity]').val(response.data.fromData.flie_src);
					var output = document.getElementById('output');
					var fp=msApp.baseUrl()+"/images/"+response.data.fromData.flie_src;
    	            output.src =  fp;


					})
					.catch(function (error) {
					console.log(error);
					});
				//MsStyleEvaluation.showGrid(style_id);
			}
			if(index==12){
				if(style_id===''){
					$('#styletabs').tabs('select',0);
					msApp.showError('Select Style First',0);
					return;
			    }
			    msApp.resetForm('stylefileuploadFrm');
				$('#stylefileuploadFrm  [name=style_ref]').val(style_ref)
				$('#stylefileuploadFrm  [name=style_id]').val(style_id);
				MsStyleFileUpload.showGrid(style_id);
			}
		}
});
