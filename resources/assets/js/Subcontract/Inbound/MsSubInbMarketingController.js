
//require('./../../jquery.easyui.min.js');
let MsSubInbMarketingModel = require('./MsSubInbMarketingModel');
require('./../../datagrid-filter.js');
class MsSubInbMarketingController {
	constructor(MsSubInbMarketingModel)
	{
		this.MsSubInbMarketingModel = MsSubInbMarketingModel;
		this.formId='subinbmarketingFrm';
		this.dataTable='#subinbmarketingTbl';
		this.route=msApp.baseUrl()+"/subinbmarketing"
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
			this.MsSubInbMarketingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubInbMarketingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subinbmarketingFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubInbMarketingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubInbMarketingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#subinbmarketingTbl').datagrid('reload');
		msApp.resetForm('subinbmarketingFrm');
		$('#subinbmarketingFrm [id="buyer_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		let self=this;
		row.route=this.route;
		row.formId=this.formId;
		let style=this.MsSubInbMarketingModel.get(index,row);
		style.then(function (response) {
			let Presponse=response
			self.getTeamMember (response.data.fromData.team_id)
			.then(function(){
				msApp.set(index,row,Presponse.data)
			})
			$('#subinbmarketingFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
		.catch(function (error) {
			console.log(error);
		});

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
		return '<a href="javascript:void(0)"  onClick="MsSubInbMarketing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
    
    getTeamMember (team_id){
		let data={};
		data.team_id=team_id;
		let team=msApp.getJson('teammember',data)
		.then(function (response) {
			    $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
                    $('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					/* if(value.type_id==1 || value.type_id==2){
						
					} */
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return team;
	}

	BuyerBranchWindowOpen(){
		$('#buyerbranchsearchWindow').window('open');
		MsSubInbMarketing.getBuyerBranch();
	}

	getBuyerBranch()
	{
		let buyer_id=$('#subinbmarketingFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getbuyerbranch?buyer_id="+buyer_id);
		data.then(function (response) {	
			$('#buyerbranchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showBuyerBranchGrid(data){
		let self = this;
		$('#buyerbranchsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#subinbmarketingFrm [name=buyer_branch_id]').val(row.id);
				$('#subinbmarketingFrm [name=name]').val(row.contact_person);
				$('#subinbmarketingFrm [name=address]').val(row.address);
				$('#subinbmarketingFrm [name=email]').val(row.email);
				$('#subinbmarketingFrm [name=country]').val(row.country);
				$('#subinbmarketingFrm [name=designation]').val(row.designation);
				$('#buyerbranchsearchWindow').window('close');
				$('#buyerbranchsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	
}
window.MsSubInbMarketing=new MsSubInbMarketingController(new MsSubInbMarketingModel());
MsSubInbMarketing.showGrid();
MsSubInbMarketing.showBuyerBranchGrid([]);

 $('#subinboundtabs').tabs({
	onSelect:function(title,index){
	 let sub_inb_marketing_id = $('#subinbmarketingFrm  [name=id]').val();
	 let production_area_id = $('#subinbmarketingFrm  [name=production_area_id]').val();

	 var data={};
	  data.sub_inb_marketing_id=sub_inb_marketing_id;

	 if(index==1){
		 if(sub_inb_marketing_id===''){
			 $('#subinboundtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#subinbeventFrm  [name=sub_inb_marketing_id]').val(sub_inb_marketing_id);
		 MsSubInbEvent.get(sub_inb_marketing_id);
	 }
	 if(index==2){
		if(sub_inb_marketing_id===''){
			$('#subinboundtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#subinbserviceFrm  [name=sub_inb_marketing_id]').val(sub_inb_marketing_id);
		MsSubInbService.showGrid(sub_inb_marketing_id);
		//  if(production_area_id==10){
		//  	$('.aop').hide();
		//  	$('.dyeing').hide();
        //     $('.knitting').show();
		//  }
		//  if(production_area_id==20){
		//  	$('.aop').hide();
		//  	$('.dyeing').show();
        //     $('.knitting').hide();
		//  }
		//  if(production_area_id==25){
		//  	$('.aop').show();
		//  	$('.dyeing').hide();
        //     $('.knitting').hide();
		//  }
	}
	if(index==3){
		if(sub_inb_marketing_id===''){
			$('#subinboundtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#subinbimageFrm  [name=sub_inb_marketing_id]').val(sub_inb_marketing_id);
		MsSubInbImage.showGrid(sub_inb_marketing_id);
	}
}
}); 
