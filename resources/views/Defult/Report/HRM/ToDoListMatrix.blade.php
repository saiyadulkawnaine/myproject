{{-- <h2>Gate In Entry Report - Goods Only</h2> --}}
<h3 align="center">To Do List</h3>
<h5 align="center">{{ $start_date }}&nbsp;&ndash;&nbsp;{{ $end_date }}</h5>
<table border="1" cellspacing="1" cellpadding="2">
    <thead>
        <tr>
            <th width="40px" align="center" class="text-center">1</th>
            <th width="150px" align="center" class="text-center">2</th>
            <th width="60px" align="center" class="text-center">3</th>
            <th width="70px" align="center" class="text-center">4</th>
            <th width="60px" align="center" class="text-center">5</th>
            <th width="70px" align="center" class="text-center">6</th>
            <th width="70px" align="center" class="text-center">7</th>
            <th width="100px" align="center" class="text-center">8</th>
            <th width="150px" align="center" class="text-center">9</th>
            <th width="150px" align="center" class="text-center">10</th>
        </tr>
        <tr>
            <th width="40px" align="center" class="text-center">SL</th>
            <th width="150px" align="center" class="text-center">Task</th>
            <th width="60px" align="center" class="text-center">Time</th>
            <th width="70px" align="center" class="text-center">Execution<br/> Date</th>
            <th width="60px" align="center" class="text-center">Priority</th>
            <th width="70px" align="center" class="text-center">Actually<br/>Started</th>
            <th width="70px" align="center" class="text-center">Actually<br/>Ended</th>
            <th width="100px" align="center" class="text-center">Result</th>
            <th width="150px" align="center" class="text-center">Impact</th>
            <th width="150px" align="center" class="text-center">Barrier</th>
        </tr>
    </thead>
    
        
    <tbody>
        @foreach ($category as $user_id=>$row)
        <tr>
            <td colspan="11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>{{ $empArr[$user_id]['user_name'] }}</strong></td>
        </tr>
        <?php
            $i=1;
        ?>
        @foreach ($row as $item)
        <tr>
            <td width="40px" align="center" class="text-center"style="<?php echo $item->status ?>">{{ $i }}</td>
            <td width="150px">{{ $item->task_desc }}</td>
            <td width="60px" align="center" class="text-center">{{ $item->task_time }}</td>
            <td width="70px" align="center" class="text-center">{{ $item->exec_date }}</td>
            <td width="60px" align="center" class="text-center">{{ $item->priority_id }}</td>
            <td width="70px" align="center" class="text-center">{{ $item->start_date }}</td>
            <td width="70px" align="center" class="text-center">{{ $item->end_date }}</td>
            <td width="100px" align="center" class="text-center">{{ $item->result_desc }}</td>
            <td width="150px">{{ $item->impact_desc }}</td>
            <td width="150px">{{ $item->barrier_desc }}</td>  
        </tr>  
        <?php
        $i++;
    ?> 
        @endforeach
        
    </tbody>

    
    @endforeach
</table>