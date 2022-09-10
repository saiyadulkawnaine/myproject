//require('./../jquery.easyui.min.js');
let MsAccTransPrntModel = require('./MsAccTransPrntModel');
require('./../datagrid-filter.js');
class MsAccTransPrntController {
	constructor(MsAccTransPrntModel)
	{
		this.MsAccTransPrntModel = MsAccTransPrntModel;
		this.formId='transprntFrm';
		this.dataTable='#transprntTbl';
		this.route=msApp.baseUrl()+"/acctransprnt"
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
			this.MsAccTransPrntModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccTransPrntModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsTransChld.resetForm ();
		MsTransChld.showGrid([]);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccTransPrntModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccTransPrntModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#transprntTbl').datagrid('reload');
		msApp.resetForm('transprntFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let transprnt=this.MsAccTransPrntModel.get(index,row);
		transprnt.then(function (response) {
			MsTransChld.showGrid(response.data.transchld);
			MsTransPrnt.setYear(response.data.accyear);
			msApp.set(index,row,response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#acctransprntsearchWindow').window('close');
	}

	getTrans(data){
		let trans=msApp.getJson("/acctransprnt",data);
		trans.then(function (response) {
			$('#transprntTbl').datagrid('loadData', response.data);

            //MsTransPrnt.showGrid(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
	}

	opentransPrntWindow()
	{
		let data={};
		let company_id=$('#transprntFrm  [name=company_id]').val();
		let acc_year_id=$('#transprntFrm  [name=acc_year_id]').val();
		let trans_date=$('#transprntFrm  [name=trans_date]').val();
		let trans_type_id=$('#transprntFrm  [name=trans_type_id]').val();
		let trans_no=$('#transprntFrm  [name=trans_no]').val();

		data.company_id=company_id;
        data.acc_year_id=acc_year_id;
        data.trans_date=trans_date;
        data.trans_type_id=trans_type_id;
        data.trans_no=trans_no;
        this.getTrans(data);

		$('#acctransprntsearchFrm  [name=company_id]').val(company_id);
		$('#acctransprntsearchFrm  [name=acc_year_id]').val(acc_year_id);
		$('#acctransprntsearchFrm  [name=trans_date]').val(trans_date);
		$('#acctransprntsearchFrm  [name=trans_type_id]').val(trans_type_id);

        
		
		$('#acctransprntsearchWindow').window('open');
    }
	transsearch(){
		let data={};
		let company_id=$('#acctransprntsearchFrm  [name=company_id]').val();
		let acc_year_id=$('#acctransprntsearchFrm  [name=acc_year_id]').val();
		let trans_date=$('#acctransprntsearchFrm  [name=trans_date]').val();
		let trans_type_id=$('#acctransprntsearchFrm  [name=trans_type_id]').val();
		let trans_no=$('#acctransprntsearchFrm  [name=trans_no]').val();
        data.company_id=company_id;
        data.acc_year_id=acc_year_id;
        data.trans_date=trans_date;
        data.trans_type_id=trans_type_id;
        data.trans_no=trans_no;
		this.getTrans(data);
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsTransPrnt.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatjournalpdf(value,row)
	{
		let bt= '<a href="javascript:void(0)"  onClick="MsTransPrnt.journalpdf('+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>JV</span></a> ';
        if(row.trans_type==1 || row.trans_type==2){
         bt+='<a href="javascript:void(0)"  onClick="MsTransPrnt.mrpdf('+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>MR</span></a>';
        }
         if(row.trans_type==4){
         bt+='<a href="javascript:void(0)"  onClick="MsTransPrnt.cqpdf('+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Chq</span></a>';
        }
        return bt;
	}

	journalpdf(id,e)
	{
		if(id==""){
			alert("Select a Journal");
			return;
		}
		if (!e) var e = window.event;                // Get the window event
		e.cancelBubble = true;                       // IE Stop propagation
		if (e.stopPropagation) e.stopPropagation();
		window.open(this.route+"/journalpdf?id="+id);
		$('#acctransprntsearchWindow').window('close');
	}

	mrpdf(id,e)
	{
		if(id==""){
			alert("Select a Journal");
			return;
		}
		if (!e) var e = window.event;                // Get the window event
		e.cancelBubble = true;                       // IE Stop propagation
		if (e.stopPropagation) e.stopPropagation();
		window.open(this.route+"/mrpdf?id="+id);
		$('#acctransprntsearchWindow').window('close');
	}

	cqpdf(id,e)
	{
		if(id==""){
			alert("Select a Journal");
			return;
		}
		if (!e) var e = window.event;                // Get the window event
		e.cancelBubble = true;                       // IE Stop propagation
		if (e.stopPropagation) e.stopPropagation();
		window.open(this.route+"/cqpdf?id="+id);
		$('#acctransprntsearchWindow').window('close');
	}
	transtypechange()
	{
		$('#transprntFrm  [name=instrument_no]').val('');
		$('#transprntFrm  [name=pay_to]').val('');
		$('#transprntFrm  [name=place_date]').val('');
		
	}

	setinchldnarration(narration)
	{
       $('#transchldFrm  [name=chld_narration]').val(narration);
	}

	getYear (company_id){
		let data={};
		data.company_id=company_id;
		let year=msApp.getJson('accyear/getBycompany',data)
		
		.then(function (response) {
			   /* $('select[name="acc_year_id"]').empty();
				$('select[name="acc_year_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
						$('select[name="acc_year_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });*/
                MsTransPrnt.setYear(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return year;
	}
	setYear(data)
	{
		//alert('ok');
		$('select[name="acc_year_id"]').empty();
		$('select[name="acc_year_id"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
			$('select[name="acc_year_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			if(value.is_current==1)
			{
				
				$('#transprntFrm  [name=acc_year_id]').val(value.id);
				
			}
		});
	}
	
}
window.MsTransPrnt=new MsAccTransPrntController(new MsAccTransPrntModel());
MsTransPrnt.showGrid([]);

