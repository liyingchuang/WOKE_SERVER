@extends('_layouts.master')
@section('content')
    <div class="panel-info">
        <div class="panel-heading">
            iOS版本控制
        </div>
    </div>
    <div class="panel-footer">
        <div class="panel-body">
            <form action="" class="form-horizontal form-bordered">
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-md-2 control-label">当前版本号:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <input type="text" class="form-control required safe-input" id="" name=""   value="{{ $info->version_number }}" required="" data-bv-notempty-message="" disabled>
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-md-2 control-label">新版本号:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <input type="text" class="form-control required safe-input" id="version_number" name="version_number"   value="" required="" data-bv-notempty-message="">
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-md-2 control-label"></label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <div style="padding-left:87%">
                        <button type="submit" formaction="{{ URL::to('manage/version/ios') }}" class="btn btn-info "><span class="glyphicon glyphicon-pencil"> 确认</span></button>
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
            </form>
        </div>
    </div>

@stop