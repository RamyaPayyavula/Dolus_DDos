<?php
$devices = $info['devices'];
$policies = $info['policies'];

?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Blacklist</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />

        <style>
            .info-box-number{
                font-size:32px;
            }
            span.label{
                font-size:12px;
            }
        </style>


    </head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- POLICY TABLE -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-6 text-left" style="padding-top:5px">
                                    <h3 class="box-title">Policy Table</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-primary" onclick="addNewPolicy()">Add New Policy</button>
                                </div>
                                <p id="addingPolicy"></p>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <h4 id="update_success" class='text-center hidden' style="background-color:#00a65a; margin-top:0; padding:10px; color:white">Successfully Updated</h4>
                        <h4 id="update_fail" class='text-center hidden' style="background-color:#e44343; margin-top:0; padding:10px; color:white">Failed to Update</h4>

                        <!-- form start -->
                        <div id="policyTableBody" class="box-body">
                            <table id="policyTable" class="table table-responsive table-striped">
                                <thead>
                                <tr>
                                    <th>Device</th>
                                    <th width="65%">Filter Policies</th>
                                    <th width="5%">Loaded</th>
                                    <th width="5%">Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                 if(count($policies)>0){
                                    for ($i=0; $i<count($policies); $i++){

                                        $devID = $policies[$i]["deviceID"];
                                        $polID = $policies[$i]["policyID"];
                                        $pols = $policies[$i]["policy"];
                                        $loaded = $policies[$i]["loaded"];

                                        echo "<tr policy-id='".$polID."'><td>";
                                        echo "<select class='form-control device'>";
                                        echo "<option value='na'>Select One</option>";
                                        for($j=0; $j<count($devices);$j++){

                                            $id = $devices[$j]["deviceID"];
                                            $name = $devices[$j]["name"];
                                            $ip = $devices[$j]["ipv4"];
                                            $mac = $devices[$j]["mac"];
                                            if($devID == $id){
                                                echo "<option value='".$id."' selected>".$name." (".$ip.")"."</option>";
                                            } else {
                                                echo "<option value='".$id."'>".$name."(".$ip.")"."</option>";
                                            }
                                        }
                                        echo "</select></td>";
                                        echo "<td><textarea class='form-control policy' rows='4' placeholder='Enter Filter Policies'>".$pols."</textarea></td>";
                                        echo "<td class='text-center loaded'>".$loaded."</td>";
                                        echo "<td><button class='btn btn-danger' onclick='removePolicy($(this))'><span class='fa fa-times'></span></button></td>";
                                        echo "</tr>";
                                    }
                                } else {
//                                    $utilities = new utilities();
//                                    $polID = strtoupper($utilities->randomNumber());
                                     $polID = 1;
                                    echo "<tr policy-id='".$polID."'><td>";
                                    echo "<select class='form-control device'>";
                                    echo "<option value='na'>Select One</option>";

                                    for($i=0; $i<count($devices);$i++){

                                        echo "<option value='".$devices[$i]["deviceID"]."'>".$devices[$i]["name"]."</option>";
                                    }
                                    echo "</select></td>";
                                    echo "<td><textarea class='form-control policy' placeholder='Enter Filter Policies' rows='4'></textarea></td>";
                                    echo "<td class='text-center loaded'>0</td>";
                                    echo "<td><button class='btn btn-danger' onclick='removePolicy($(this))'><span class='fa fa-times'></span></button></td></tr>";
                                }
                                ?>
                                <!-- JQUERY -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" onclick="updatePolicy()" class="btn btn-success pull-right">Update Policy</button>
                            <!-- <button type="button" onclick="insertNetKAT()" class="btn btn-success pull-right">Insert NetKAT</button> -->
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col-md-12 -->
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-line-chart"></i>
                            <h3 class="box-title">Network Graph</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-xs-12 col-md-8">
                                <div class="row" style="margin-bottom:10px">
                                    <div id="mynetwork"></div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-primary" onclick="draw()">Show Network</button>
                                    <input type="hidden" id="switchesJSON"/>
                                    <input type="hidden" id="networkJSON"/>
                                </div>
                            </div>
                            <div id="network-info-box" class="col-xs-12 col-md-4">
                                <table id="network-info" class="table table-responsive table-bordered">
                                    <thead>
                                    <tr>
                                        <th>No Data</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Found !</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>

</div>
<!-- ./wrapper -->

<script>
    function addNewPolicy() {
        var div = document.createElement("div");
        div.style.marginTop ="20px";
        div.style.marginRight = "100px";
        div.style.marginLeft = "100px";
        div.style.marginBottom = "20px";
        div.style.height = "100px";
        div.innerHTML = "<textarea name=\"message\" rows=\"5\" cols=\"100\"></textarea><input type=\"submit\">";
        document.getElementById("addingPolicy").appendChild(div);
    }
</script>
</body>
</html>
@endsection