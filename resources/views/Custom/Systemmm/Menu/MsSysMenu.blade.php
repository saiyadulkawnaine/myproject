<style>
@-webkit-keyframes swing
{
    15%
    {
        -webkit-transform: translateX(5px);
        transform: translateX(5px);
    }
    30%
    {
        -webkit-transform: translateX(-5px);
       transform: translateX(-5px);
    } 
    50%
    {
        -webkit-transform: translateX(3px);
        transform: translateX(3px);
    }
    65%
    {
        -webkit-transform: translateX(-3px);
        transform: translateX(-3px);
    }
    80%
    {
        -webkit-transform: translateX(2px);
        transform: translateX(2px);
    }
    100%
    {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
}
@keyframes swing
{
    15%
    {
        -webkit-transform: translateX(5px);
        transform: translateX(5px);
    }
    30%
    {
        -webkit-transform: translateX(-5px);
        transform: translateX(-5px);
    }
    50%
    {
        -webkit-transform: translateX(3px);
        transform: translateX(3px);
    }
    65%
    {
        -webkit-transform: translateX(-3px);
        transform: translateX(-3px);
    }
    80%
    {
        -webkit-transform: translateX(2px);
        transform: translateX(2px);
    }
    100%
    {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
}



.swing
{
        -webkit-animation: swing 1s ease;
        animation: swing 3s ease;
        -webkit-animation-iteration-count: 1;
        animation-iteration-count: 1;
}

@keyframes four {
		   0% {Border-radius:0px;} 
		   100% {
			   border-radius:0px; transform: 
       		   rotateX(360deg) rotateY(360deg);
	           } 
	       }

#four {
        Animation: four 1s ease 0s  normal;
        Border-radius:0px;}
		
		.animated {
            
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
         }
         
         @-webkit-keyframes rollIn {
            0% { 
               opacity: 0; 
               -webkit-transform: translateX(-100%) rotate(-120deg); 
            }
            100% { 
               opacity: 1; 
               -webkit-transform: translateX(0px) rotate(0deg); 
            }
         }
         
         @keyframes rollIn {
            0% { 
               opacity: 0; 
               transform: translateX(-100%) rotate(-120deg); 
            }
            100% { 
               opacity: 1; 
               transform: translateX(0px) rotate(0deg); 
            }
         }
         .rollIn {
            -webkit-animation-name: rollIn;
            animation-name: rollIn;
         }


</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;
-webkit-box-shadow: 0 0 2px 2px #ccc;
box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'east',split:true,title:'Menu Permission',hideCollapsedContent:false" style="width:280px;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:false,footer:'#ft'" style="padding:0px;background: rgba(0, 0, 0, 0) linear-gradient(to left, #f8f8f8 10px, #eeeeee 100%) repeat-x scroll 0 0;">
                <div id="ft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton c1" data-options="plain:true,iconCls:'icon-save'" style="height:25px;border-radius:1px" onclick="permissions('<?php echo url('/')."/menu_permission";?>','POST')">Save</a>
                </div>
                <ul id="tbl_permission" url="<?php  echo url('/')."/menu_permission"; ?>" ><strong>Select a Menu to see its event</strong></ul>
            </div>
        </div>
    </div>
    <div data-options="region:'center', border:false">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="tbl_x" style="width:100%" url='<?php  echo url('/')."/menu"; ?>'>
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'router'" width="150">Router</th>
                           
                        </tr>
                    </thead>
                </table>
                
            </div>
            <div data-options="region:'north',border:true,title:'Add New Menu',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
                    <div id="container">
                         <div id="body">
                           <code>
                                <form id="menuform">
                                    <div class="row">
                                        <div class="col-sm-2">Name *:</div>
                                        <div class="col-sm-3">
                                        <input type="text" name="name" id="name" value=""/>
                                        <input type="hidden" name="id" id="id" value=""/>
                                        </div>
                                        <div class="col-sm-2">Router:</div>
                                        <div class="col-sm-5"><input type="text" name="router" id="router" value="" /></div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-2">Root Manu *:</div>
                                        <div class="col-sm-3">{!! Form::select('root_menu', $menu_arr,'',array('id'=>'root_menu')) !!} </div>
                                        <div class="col-sm-2">Js Link:</div>
                                        <div class="col-sm-5"><input type="text" name="js_link" id="js_link" value="" /></div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-2">Sort: </div>
                                        <div class="col-sm-3"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                                        <div class="col-sm-2">CSS Link:</div>
                                        <div class="col-sm-5"><input type="text" name="css_link" id="css_link" value="" /></div>
                                    </div>
                                </form>
                          </code>
                       </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onClick="MsMenu.crud('<?php echo url('/')."/menu";?>','POST')">Save</a>
                       <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" onClick="MsMenu.crud('<?php echo url('/')."/menu";?>','DELETE')" >Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/menu/MsMenuController.js"></script>
