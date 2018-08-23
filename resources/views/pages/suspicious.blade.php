<?php
$devices = $info['devices'];

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
            .flot-x-axis .flot-tick-label {
                white-space: nowrap;
                transform: translate(-9px, 0) rotate(-70deg);
                text-indent: -100%;
                transform-origin: top right;
                text-align: right !important;
            }

        </style>


    </head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- NAVBAR -->


<!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- SUSPECT SCORES BAR GRAPH -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Suspiciousness Score Trend by Device</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="device" class="form-control">
                                        <?php


                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["device_id"];
                                                $deviceName = $devices[$i]["name"];
                                                if(strcasecmp($deviceName, "user1")==0){
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."' selected>".$deviceName."</option>";
                                                } else {
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."'>".$deviceName."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="adapt" class="col-md-4 col-xs-12 hidden">
                                    <button class="btn btn-danger" onclick="updatePolicy()">ADAPT</button>
                                </div>
                            </div>
                            <div class="row" style="padding:15px; padding-bottom:0">
                                <div id="bar-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- SUSPECT SCORES GRAPH -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Suspiciousness Graph</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row" style="padding:15px">
                                <div id="suspiciousness" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Distinct IP connections per 15 seconds</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="device" class="form-control">
                                        <?php


                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["device_id"];
                                                $deviceName = $devices[$i]["name"];
                                                if(strcasecmp($deviceName, "user1")==0){
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."' selected>".$deviceName."</option>";
                                                } else {
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."'>".$deviceName."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="adapt" class="col-md-4 col-xs-12 hidden">
                                    <button class="btn btn-danger" onclick="updatePolicy()">ADAPT</button>
                                </div>
                            </div>
                            <div class="row" style="padding:15px; padding-bottom:0">
                                <div id="bar-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Total Flows per 15 seconds</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="device" class="form-control">
                                        <?php


                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["device_id"];
                                                $deviceName = $devices[$i]["name"];
                                                if(strcasecmp($deviceName, "user1")==0){
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."' selected>".$deviceName."</option>";
                                                } else {
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."'>".$deviceName."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="adapt" class="col-md-4 col-xs-12 hidden">
                                    <button class="btn btn-danger" onclick="updatePolicy()">ADAPT</button>
                                </div>
                            </div>
                            <div class="row" style="padding:15px; padding-bottom:0">
                                <div id="bar-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Total Bytes transfered per 15 seconds</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12" style="margin-bottom:5px">
                                    <select id="device" class="form-control">
                                        <?php


                                        // Append Body
                                        if(count($devices) > 0){
                                            for($i=0; $i<count($devices);$i++){
                                                $deviceID = $devices[$i]["device_id"];
                                                $deviceName = $devices[$i]["name"];
                                                if(strcasecmp($deviceName, "user1")==0){
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."' selected>".$deviceName."</option>";
                                                } else {
                                                    echo "<option device-id='".$deviceID."' value='".$deviceName."'>".$deviceName."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="adapt" class="col-md-4 col-xs-12 hidden">
                                    <button class="btn btn-danger" onclick="updatePolicy()">ADAPT</button>
                                </div>
                            </div>
                            <div class="row" style="padding:15px; padding-bottom:0">
                                <div id="bar-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->


</div>
<!-- ./wrapper -->
</body>
</html>
@endsection