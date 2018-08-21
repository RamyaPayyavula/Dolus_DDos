<?php
$servers = $info['servers'];

?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Servers</title>

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
        <!-- NAVBAR -->

        <!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Servers</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="refreshServerStatus()" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="serverTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>Server UID</th>
                                        <th class='text-center'>Server Name</th>
                                        <th class='text-center'>Server IP</th>
                                        <th class='text-center'>Server Created On</th>
                                        <th class='text-center'>Reputation Value</th>
                                        <th class='text-center'>Bid Value</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    // Append Body
                                    if(count($servers) > 0){
//                                        $bidVal = floatval(file_get_contents("/resources/assets/databid_value.txt"));
                                        $bidVal=0.35;

                                        for($i=0; $i<count($servers);$i++){
                                            $serverUID = $servers[$i]["serverUID"];
                                            $serverName = $servers[$i]["serverName"];
                                            $serverIP = $servers[$i]["serverIP"];
                                            $serverCreatedOn = $servers[$i]["serverCreatedOn"];
                                            $reputationValue = $servers[$i]["reputationValue"];
                                            $bidValue = $servers[$i]["bidValue"];

                                            $repLabel = "label-success";
                                            $bidLabel = "label-success";

                                            // Set reputation label
                                            if($reputationValue > 0 && $reputationValue<0.50){
                                                $repLabel = "label-danger";
                                            } else if($reputationValue>=0.50 && $reputationValue<0.80){
                                                $repLabel = "label-warning";
                                            } else if($reputationValue>=0.80 && $reputationValue<=1.00){
                                                $repLabel = "label-success";
                                            }

                                            // Set bid label
                                            if($bidValue > 0 && $bidValue<$bidVal){
                                                $bidLabel = "label-danger";
                                            } else if($bidValue>=0.50 && $bidValue<0.80){
                                                $bidLabel = "label-warning";
                                            } else if($bidValue>=0.80 && $bidValue<=1.00){
                                                $bidLabel = "label-success";
                                            }

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$serverUID."</td>";
                                            echo "<td>".$serverName."</td>";
                                            echo "<td>".$serverIP."</td>";
                                            echo "<td>".$serverCreatedOn."</td>";
                                            echo "<td><span class='label ".$repLabel."'>".$reputationValue."</span></td>";
                                            echo "<td><span class='label ".$bidLabel."'>".$bidValue."</span></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart-o"></i>

                            <h3 class="box-title">Number of Servers Used per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="bar-chart" style="height: 300px;"></div>

                            <div id="chart-div"></div>
                            {!! $lava->render('BarChart', 'servers', 'chart-div') !!}


                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{--<!-- ./wrapper -->--}}

    <?php //include_once("../includes/js.php"); ?>



    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"/>
    <link href="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap4.min.js">


    {{--</script>--}}
    </body>
    </html>
@endsection
