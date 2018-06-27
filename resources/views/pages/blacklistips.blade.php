<?php
$blacklistips = $info['blacklistIps'];

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
            <!-- END OF CHART -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Blacklisted IPs</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="attackHistory" class="table table-bordered table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">IP Address</th>
                                        <th class="text-center">Mac Address</th>
                                        <th class="text-center">Blacklisted On</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody" class="text-center">

                                    <?php
                                    // Append Body
                                    if(count($blacklistips) > 0){
                                        for($i=0; $i<count($blacklistips);$i++){
                                            $ipAddress = $blacklistips[$i]["ipAddress"];
                                            $macAddress = $blacklistips[$i]["macAddress"];
                                            $blacklistedOn = $blacklistips[$i]["blacklistedOn"];
                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$ipAddress."</td>";
                                            echo "<td>".$macAddress."</td>";
                                            echo "<td>".$blacklistedOn."</td>";
                                             echo "</tr>";
                                        }
                                    }
                                    ?> </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->
        </section>
        <!-- /.content -->
    <!-- /.content-wrapper -->
</div>

</body>
</html>
@endsection