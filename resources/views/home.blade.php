<?php

$qvms = $info['qvms'];
$servers = $info['servers'];
$users = $info['users'];

?>

@extends('layouts.master')

<title>MTD | Dashboard</title>
@section('content')

    <div class="hold-transition skin-blue sidebar-mini">

        <div class="wrapper">

            <!-- Content Wrapper. Contains page content -->
        {{--<div class="content-wrapper">--}}
        <!-- Main content -->
            <section class="content">

                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.servers')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Currently Available Servers</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($servers); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.users')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Current Users</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($users); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href={{ route('pages.qvms')}}>
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">TOTAL QVMs</span>
                                    <!-- VALUE OBTAINED FROM SIDEBAR.PHP -->
                                    <span class="info-box-number"><?php echo($qvms); ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>

                <!-- BANDWIDTH UTILIZATION -->
                <div class="row">
                    <div class="col-xs-12">
                        <!-- interactive chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Bandwidth Utilization</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>
                                <!-- <div class="box-tools pull-right">
                                  Real time
                                  <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                                    <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                                  </div>
                                </div> -->
                            </div>
                            <div class="box-body">
                                <div id="overallBandwidth" style="height: 300px;"></div><!--interactive-->
                            </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- CURRENT NETWORK -->
                <div class="row">
                    <div class="col-xs-6">
                        <!-- interactive chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Root Switch</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>
                                </div>

                                <!-- <div class="box-tools pull-right">
                                  Real time
                                  <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                                    <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                                  </div>
                                </div> -->
                            </div>
                            <div class="box-body">
                                <div id="switch2" style="height: 300px;"></div><!--interactive-->
                            </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-xs-6">
                        <!-- interactive chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-line-chart"></i>

                                <h3 class="box-title">Slave Switch</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" onclick="location.reload();" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                    </button>
                                </div>

                                <!-- <div class="box-tools pull-right">
                                  Real time
                                  <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                                    <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                                  </div>
                                </div> -->
                            </div>
                            <div class="box-body">
                                <div id="switch1" style="height: 300px;"></div><!--interactive-->
                            </div>
                        </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        {{--</div>--}}
        <!-- /.content-wrapper -->
        </div>

    </div>

@endsection
