		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center', border:false">
                <div class="easyui-layout" data-options="fit:true" id="group_lay">
                    <div data-options="region:'center',border:true,title:'Add New Role',footer:'#ft2'" style="padding:2px">
                    <div id="container">
                                 <div id="body">
                                   <code>
                                           <form id="roleFrm">
                                            <div class="row">
                                                <div class="col-sm-2 req-text">Name</div>
                                                <div class="col-sm-3">
                                                <input type="text" name="name" id="name" value=""/>
                                                <input type="hidden" name="id" id="id" value=""/>
                                                <input type="hidden" name="permission_id" id="permission_id" value=""/>
                                                </div>
                                                <div class="col-sm-2 req-text">Slug</div>
                                                <div class="col-sm-5"><input type="text" name="slug" id="slug" value="" /></div>
                                            </div>
                                            <div class="row middle">
                                                <div class="col-sm-2">Level</div>
                                                <div class="col-sm-3">
                                                <input type="text" name="level" id="level" value=""/>
                                                </div>
                                                <div class="col-sm-2">Description</div>
                                                <div class="col-sm-5"><input type="text" name="description" id="description" value="" /></div>
                                            </div>
                                             <div class="middle">
                                                <div class="col-sm-12" style="text-align:center; font-weight:bold; background:#F3F3F3;margin-bottom:2px;">Permissions</div>
                                            </div>
                                            <div class="row middle">
                                                <div class="col-sm-2">Abailable</div>
                                                <div class="col-sm-3">
                                                <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction(this.id,'lstBox1')">
                                                </div>
                                                <div class="col-sm-2">Permited</div>
                                                <div class="col-sm-5"><input type="text" placeholder="Search.." id="myInput2" onkeyup="filterFunction(this.id,'lstBox2')"></div>
                                            </div>
                                            <div class="row middle" id="permission_dropDown">
                                                <div class="col-sm-5">
                                                {!! Form::select('lstBox1', $permission_arr,'',array('id'=>'lstBox1','multiple'=>'multiple','style'=>'height:150px','class'=>'LeftRight')) !!}
                                                </div>
                                                <div class="col-sm-2" style="text-align:center">
                                                <br />
                                                <input type='button' id='btnAllRight' value='>>' class="btn btn-default" style="width:40px" onclick="msApp.moveAllToRight(event,'lstBox1','lstBox2')" />
                                                <br />
                                                <input type='button' id='btnRight' value='>' class="btn btn-default" style="width:40px" onclick="msApp.moveToRight(event,'lstBox1','lstBox2')" />
                                                <br />
                                                <input type='button' id='btnLeft' value='<' class="btn btn-default" style="width:40px" onclick="msApp.moveToLeft(event,'lstBox1','lstBox2')"/>
                                                <br />
                                                <input type='button' id='btnAllLeft' value='<<' class="btn btn-default" style="width:40px" onclick="msApp.moveAllToLeft(event,'lstBox1','lstBox2')" />
                                                </div>
                                                <div class="col-sm-5">
                                                <select multiple  id="lstBox2" style="height:150px" class="LeftRight">
                                                </select>
                                                </div>
                                            </div>
                                        </form>
                                  </code>
                               </div>
                            </div>
                            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRole.submit()">Save</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('roleFrm')" >Reset</a>

                            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRole.remove()" >Delete</a>
                            </div>


                    </div>
                    <div data-options="region:'south',border:true,title:'List',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true" style="height:200px; padding:2px">
                      <table id="roleTbl">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="80">ID</th>
                                    <th data-options="field:'name'" width="100">Name</th>
                                    <th data-options="field:'slug'" width="100">Slug</th>
                                    <th data-options="field:'description'" width="150">Desctiption</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
			</div>
		</div>
	<script type="text/javascript" src="<?php echo url('/');?>/js/user-accounts/MsRoleController.js"></script>
    <script>
	function filterFunction(serchBy,serchTo) {
    var input, filter, ul, li, a, i;
    input = document.getElementById(serchBy);
    filter = input.value.toUpperCase();
    div = document.getElementById(serchTo);
    a = div.getElementsByTagName("option");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}
	</script>
