<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilsewingcapacitytabs">
    <div title="Capacity" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sewingcapacityTbl" style="width:100%">
                <thead>
                <tr>
                <th data-options="field:'id'" width="50">ID</th>
                <th data-options="field:'company_code'" width="60">Company</th>
                <th data-options="field:'location_name'" width="80">Location</th>
                <th data-options="field:'year'" width="50" align="right">Year</th>
                <th data-options="field:'basic_smv'" width="60" align="right">Basic SMV</th>
                <th data-options="field:'working_hour'" width="60" align="right">Work Hour</th>
                <th data-options="field:'mkt_eff_percent'" width="80" align="right">MKT Effi. (%)</th>
                <th data-options="field:'prod_eff_percent'" width="80" align="right">Prod. Effi. (%)</th>
                <th data-options="field:'prodsource'" width="80" >Prod. Source</th>
                </tr>
                </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Capacity',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#sewingcapacityFrmFt'" style="width:450px; padding:2px">
                <form id="sewingcapacityFrm">
                <div id="container">
                <div id="body">
                <code>
                <div class="row">
                <div class="col-sm-5 req-text">Company </div>
                <div class="col-sm-7">
                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                <input type="hidden" name="id" id="id" value=""/>
                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-5">Location  </div>
                <div class="col-sm-7">{!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}</div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">Production Source  </div>
                <div class="col-sm-7">{!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}</div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">Year  </div>
                <div class="col-sm-7">
                    {!! Form::select('year', $year,'',array('id'=>'year')) !!}
                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">MKT Efficiency (%)  </div>
                <div class="col-sm-7"><input type="text" name="mkt_eff_percent" id="mkt_eff_percent" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">Product Efficiency (%)  </div>
                <div class="col-sm-7"><input type="text" name="prod_eff_percent" id="prod_eff_percent" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">Basic SMV  </div>
                <div class="col-sm-7"><input type="text" name="basic_smv" id="basic_smv" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-5 req-text">Working Hour  </div>
                <div class="col-sm-7"><input type="text" name="working_hour" id="working_hour" class="number integer"/></div>
                </div>
                </code>
                </div>
                </div>
                <div id="sewingcapacityFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSewingCapacity.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sewingcapacityFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSewingCapacity.remove()" >Delete</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSewingCapacity.pdf()" >PDF</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    
    <div title="Date" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Date',footer:'#sewingcapacitydateFrmFt'" style="padding:2px">
                <form id="sewingcapacitydateFrm">
                <input type="hidden" name="sewing_capacity_id" id="sewing_capacity_id" value=""/>
                <code id="sewingcapacitydatematrix">
                </code>
                <div id="sewingcapacitydateFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSewingCapacityDate.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sewingcapacitydateFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSewingCapacityDate.remove()" >Delete</a>
                </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSewingCapacityController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSewingCapacityDateController.js"></script>

<script type="text/javascript">
    $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
    this.value = this.value.replace(/[^0-9\.]/g, '');
    }
    });   
</script>

