<!-- <link rel="stylesheet" type="text/css" href="<?php echo url('/');?>/js/dp/main.css?v=2021.2.5007"> -->
<script type="text/javascript" src="<?php echo url('/');?>/js/dp/daypilot-all.min.js"></script>
<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Board" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <div id="dp"></div>
                
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#planingboardFrmFt'" style="width:350px; padding:2px">
                <form id="planingboardFrm">
                    <div id="container">
                        <div id="body">
                            <code>                              
                                <div class="row middle">
                                <div class="col-sm-4">Resource</div>
                                <div class="col-sm-8">
                                    <input type="text" name="resource" id="resource">
                                </div>
                                <div class="col-sm-4">Start Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="start_date" id="start_date" class="datepicker">
                                </div>
                                <div class="col-sm-4">End Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="end_date" id="end_date" class="datepicker">
                                </div>
                                <div class="col-sm-4">Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name">
                                </div>
                            </div> 
                            </code>
                        </div>
                    </div>
                    <div id="planingboardFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="createPlan()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('planingboardFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlaningBoard.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="planingboardWindow" class="easyui-window" title="Board" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#planingboardwindowft'" style="width:300px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="planingboardwFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Resource</div>
                                <div class="col-sm-8">
                                    <input type="text" name="resource" id="resource">
                                </div>
                                <div class="col-sm-4">Start Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="start_date" id="start_date">
                                </div>
                                <div class="col-sm-4">End Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="end_date" id="end_date">
                                </div>
                                <div class="col-sm-4">Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="planingboardwindowft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="closewn()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#planingboardTblft'" style="padding:10px;">
           
        </div>
       
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Planing/MsPlaningBoardController.js"></script> 
<!-- <script type="text/javascript" src="<?php echo url('/');?>/js/dp/app.js?v=2021.2.5007"></script>-->
<script> 

var dp = new DayPilot.Scheduler("dp");

dp.treeEnabled = true;

dp.heightSpec = "Max";

dp.height = 500;

dp.scale = "Day";

dp.startDate = "2019-01-01";
dp.days = 365;

dp.dynamicLoading = true;
dp.dynamicEventRendering = "Disabled";

//dp.treePreventParentUsage = true;

dp.onScroll = function (args) {
    var start = args.viewport.start.addDays(-30);
    var end = args.viewport.end.addDays(30);

    //console.log("args.viewport", args.viewport);

    DayPilot.Http.ajax({
    url: msApp.baseUrl()+"/planingboard/loadplan?start=" + start.toString() + "&end=" + end.toString(),
    success: function (ajax) {
    args.events = ajax.data;
    args.loaded();
    }
    })
};

dp.timeHeaders = [
    {groupBy: "Month", format: "MMMM yyyy"},
    {groupBy: "Day", format: "d"}
];

dp.contextMenu = new DayPilot.Menu({
    items: [
        {
            text: "Edit", onClick: function (args) {
                dp.events.edit(args.source);
            }
        },
        {
            text: "Delete", onClick: function (args) {
                dp.events.remove(args.source);
            }
        },
        {text: "-"},
        {
            text: "Select", onClick: function (args) {
                dp.multiselect.add(args.source);
            }
        }
    ]
});


/*dp.resources = [
    {
        name: "Locations", id: "G1", expanded: true, children: [
            {name: "Room 1", id: "A"},
            {name: "Room 2", id: "B"},
            {name: "Room 3", id: "C"},
            {name: "Room 4", id: "D"}
        ]
    },
    {
        name: "People", id: "G2", expanded: true, children: [
            {name: "Person 1", id: "E"},
            {name: "Person 2", id: "F"},
            {name: "Person 3", id: "G"},
            {name: "Person 4", id: "H"}
        ]
    },
    {
    name: "Tools", id: "G3", expanded: true, children: [
            {name: "Tool 1", id: "I"},
            {name: "Tool 2", id: "J"},
            {name: "Tool 3", id: "K"},
            {name: "Tool 4", id: "L"}
        ]
    },
    {
    name: "Other Resources", id: "G4", expanded: true, children: [
            {name: "Resource 1", id: "R1"},
            {name: "Resource 2", id: "R2"},
            {name: "Resource 3", id: "R3"},
            {name: "Resource 4", id: "R4"}
        ]
    },
];*/




/*dp.events.list = [];

for (var i = 0; i < 12; i++) {
    var duration = Math.floor(Math.random() * 6) + 1; // 1 to 6
    var durationDays = Math.floor(Math.random() * 6) + 1; // 1 to 6
    var start = Math.floor(Math.random() * 6) + 2; // 2 to 7

    var e = {
        start: new DayPilot.Date("2019-06-05T12:00:00").addDays(start),
        end: new DayPilot.Date("2019-06-05T12:00:00").addDays(start).addDays(durationDays).addHours(duration),
        id: DayPilot.guid(),
        resource:"R39", //String.fromCharCode(65 + i),
        text: "Event " + (i + 1),
        bubbleHtml: "Event " + (i + 1),
        barColor: barColor(i),
        barBackColor: barBackColor(i)
    };

    dp.events.list.push(e);
}*/

dp.eventMovingStartEndEnabled = true;
dp.eventResizingStartEndEnabled = true;
dp.timeRangeSelectingStartEndEnabled = true;

// event moving
dp.onEventMoved = function (args) {
    dp.message("Moved: " + args.e.text());
};

/*dp.onEventMoving = function (args) {
    if (args.e.resource() === "A" && args.resource === "B") {  // don't allow moving from A to B
        args.left.enabled = false;
        args.right.html = "You can't move an event from Room 1 to Room 2";

        args.allowed = false;
    } 
    else if (args.resource === "B") {  // must start on a working day, maximum length one day
        while (args.start.getDayOfWeek() === 0 || args.start.getDayOfWeek() === 6) {
            args.start = args.start.addDays(1);
        }
        args.end = args.start.addDays(1);  // fixed duration
        args.left.enabled = false;
        args.right.html = "Events in Room 2 must start on a workday and are limited to 1 day.";
    }

    if (args.resource === "C") {
        var except = args.e.data;
        var events = dp.rows.find(args.resource).events.all();

        var start = args.start;
        var end = args.end;
        var overlaps = events.some(function (item) {
            return item.data !== except && DayPilot.Util.overlaps(item.start(), item.end(), start, end);
        });

        while (overlaps) {
            start = start.addDays(1);
            end = end.addDays(1);

            overlaps = events.some(function (item) {
                return item.data !== except && DayPilot.Util.overlaps(item.start(), item.end(), start, end);
            });
        }

        if (args.start !== start) {
            args.start = start;
            args.end = end;

            args.left.enabled = false;
            args.right.html = "Start automatically moved to " + args.start.toString("d MMMM, yyyy");
        }

    }
};*/

// event resizing
dp.onEventResized = function (args) {
    dp.message("Resized: " + args.e.text());
};

// event creating
dp.onTimeRangeSelected = function (args) {
    $('#planingboardWindow').window('open');
    $('#planingboardwFrm  [name=resource]').val(args.resource)
    $('#planingboardwFrm  [name=start_date]').val(args.start)
    $('#planingboardwFrm  [name=end_date]').val(args.end)
};



dp.onEventMove = function (args) {
    if (args.ctrl) {
        var newEvent = new DayPilot.Event({
        start: args.newStart,
        end: args.newEnd,
        text: "Copy of " + args.e.text(),
        resource: args.newResource,
        id: DayPilot.guid()  // generate random id
        });
        dp.events.add(newEvent);

        // notify the server about the action here

        args.preventDefault(); // prevent the default action - moving event to the new location
    }
};

dp.onEventClick = function (args) {
    DayPilot.Modal.alert(args.e.data.text);
};

dp.init();
loadResources();
dp.scrollTo("2019-07-01 00:00:00");

function barColor(i) {
    var colors = ["#3c78d8", "#6aa84f", "#f1c232", "#cc0000"];
    return colors[i % 4];
}

function barBackColor(i) {
    var colors = ["#a4c2f4", "#b6d7a8", "#ffe599", "#ea9999"];
    return colors[i % 4];
}

function closewn(){
    $('#planingboardWindow').window('close');
    let resource=$('#planingboardwFrm  [name=resource]').val();
    let start=$('#planingboardwFrm  [name=start_date]').val();
    let end =$('#planingboardwFrm  [name=end_date]').val();
    let name= $('#planingboardwFrm  [name=name]').val();

    dp.clearSelection();
    //var name = modal.result;
    if (!name) return;
    var e = new DayPilot.Event({
    start:start,
    end:end,
    id: DayPilot.guid(),
    resource:resource*1,
    text: name
    });
    dp.events.add(e);
    //alert(dp.events.toString());
    console.log("dp.events", dp.events.all());

    dp.message("Created");
}

function createPlan(){
    //2021-06-05T12:00:00
    let resource=$('#planingboardFrm  [name=resource]').val();
    let start=$('#planingboardFrm  [name=start_date]').val();
    let end =$('#planingboardFrm  [name=end_date]').val();
    let name= $('#planingboardFrm  [name=name]').val();
    end=msApp.addDays(new Date(end),1);
    //start=start+"T00:00:00";
    //end=end+"T00:00:00";
    dp.clearSelection();
    //var name = modal.result;
    if (!name) return;
    var e = new DayPilot.Event({
    start:start,
    end:end,
    id: DayPilot.guid(),
    resource:resource,
    text: name
    });
    dp.events.add(e);

    dp.message("Created");

}



function loadResources() {
dp.rows.load(msApp.baseUrl()+"/planingboard/loadresource");
}

  


//===============================================


    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });

    
	
	
</script>