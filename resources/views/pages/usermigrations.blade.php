<?php
$userMigrations = $info['usermigrations'];

?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Users</title>

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
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">User Migration History</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="userTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>User Migration ID</th>
                                        <th class='text-center'>User IP</th>
                                        <th class='text-center'>Orginal Server IP</th>
                                        <th class='text-center'>Migrated Server IP</th>
                                        <th class='text-center'>Migration Start Time</th>
                                        <th class='text-center'>Migration Start Time</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody" class="text-center">
                                    <?php

                                    // Append Body
                                    if(count($userMigrations) > 0){
                                        for($i=0; $i<count($userMigrations);$i++){
                                            $userMigrationUID = $userMigrations[$i]["userMigrationUID"];
                                            $userIP = $userMigrations[$i]["userIP"];
                                            $originalServerIP = $userMigrations[$i]["originalServerIP"];
                                            $migratedServerIP = $userMigrations[$i]["migratedServerIP"];
                                            $migrationStartTime = $userMigrations[$i]["migrationStartTime"];
                                            $migrationStopTime = $userMigrations[$i]["migrationStopTime"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$userMigrationUID."</td>";
                                            echo "<td>".$userIP."</td>";
                                            echo "<td>".$originalServerIP."</td>";
                                            echo "<td>".$migratedServerIP."</td>";
                                            echo "<td>".$migrationStartTime."</td>";
                                            echo "<td>".$migrationStopTime."</td>";
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

                            <h3 class="box-title">Number of User Migrations per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="bar-chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->

        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->
</div>

<!-- ./wrapper -->

</body>
</html>
@endsection

