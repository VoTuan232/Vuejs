<style>
     .admin-body {
        margin-left: 196px;
        margin-top: 37px;
     }

    .panel-body .btn:not(.btn-block) { width:120px;margin-bottom:10px; }

    .panel-heading {
        background-color: #337ab7;
    }

    .panel-primary {
        border-color: #337ab7 !important;
    }

    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }

    .panel-body {
        padding: 15px;
    }
</style>  

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-offset-2 admin-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="nav-icon fas fa-bookmark blue"></i> Website Manager</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                              <a href="" class="btn btn-danger btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>News</a>
                              <a href="" class="btn btn-warning btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Storages</a>
                              <a href="" class="btn btn-primary btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Posts</a>
                              <a href="#" class="btn btn-primary btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Comments</a>
                            </div>
                            <div class="col-xs-6 col-md-6">
                              <a href="" class="btn btn-success btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Users</a>
                              <a href="" class="btn btn-info btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Categories</a>
                              <a href="" class="btn btn-primary btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Authorization</a>
                              <a href="" class="btn btn-primary btn-lg" role="button"><i class="nav-icon fas fa-tachometer-alt white"></i> <br/>Tags</a>
                            </div>
                        </div>
                        <a href="" class="btn btn-success btn-lg btn-block" role="button"><span class="glyphicon glyphicon-globe"></span> News Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
