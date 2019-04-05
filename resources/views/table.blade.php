<style>
    .files > li {
        float: left;
        width: 150px;
        border: 1px solid #eee;
        margin-bottom: 10px;
        margin-right: 10px;
        position: relative;
    }

    .file-icon {
        text-align: left;
        font-size: 25px;
        color: #666;
        display: block;
        float: left;
    }

    .action-row {
        text-align: center;
    }

    .file-name {
        font-weight: bold;
        color: #666;
        display: block;
        overflow: hidden !important;
        white-space: nowrap !important;
        text-overflow: ellipsis !important;
        float: left;
        margin: 7px 0px 0px 10px;
    }

    .file-icon.has-img>img {
         max-width: 100%;
         height: auto;
         max-height: 30px;
     }

</style>


<script src="{{url("/vendor/laravel-admin-ext-media/angular.min.js")}}"></script>
<script src="{{url("/vendor/laravel-admin-ext-media/media-app.js")}}"></script>
<script>
    var list= {!! json_encode($list) !!};
</script>
<script>
    var url={!! json_encode($url) !!};
    var nav={!! json_encode($nav) !!};
</script>


<div class="row" ng-app="mediaApp" ng-controller="mediaController" >
    <!-- /.col -->
    <div class="col-md-12">
        <div class="box box-primary">

            <div class="box-body no-padding">

                <div class="mailbox-controls with-border">
                    <div class="btn-group">
                        <a href="" type="button" class="btn btn-default btn media-reload" ng-click="media_refresh()" title="Refresh">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a type="button" class="btn btn-default btn file-delete-multiple" ng-click="deleteSelected()" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                    <!-- /.btn-group -->
                    <label class="btn btn-default btn">
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;{{ trans('admin.upload') }}
                        <form   method="post" class="file-upload-form" enctype="multipart/form-data">
                            <input type="file" ng-change="uploadfile(file)" select-ng-files ng-model="file" class="hidden file-upload" multiple>
                            {{ csrf_field() }}
                        </form>
                    </label>

                    <!-- /.btn-group -->
                    <a class="btn btn-default btn" data-toggle="modal" ng-click="openModal('newFolderModal')">
                        <i class="fa fa-folder"></i>&nbsp;&nbsp;{{ trans('admin.new_folder') }}
                    </a>

                    <div class="btn-group">
                        <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'table']) }}" class="btn btn-default active"><i class="fa fa-list"></i></a>
                        <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'list']) }}" class="btn btn-default"><i class="fa fa-th"></i></a>
                    </div>

                    {{--<form action="{{ $url['index'] }}" method="get" pjax-container>--}}
                    <div class="input-group input-group-sm pull-right goto-url" style="width: 250px;">
                        <input type="text" name="path" class="form-control pull-right" ng-model="path">

                        <div class="input-group-btn">
                            <button ng-click="newDir(path)" class="btn btn-default"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                    {{--</form>--}}

                </div>

                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer" ng-cloak>
                <ol class="breadcrumb" style="margin-bottom: 10px;">

                    <li><a ng-click="newDir('')"><i class="fa fa-th-large"></i> </a></li>
                    <li ng-repeat="item in nav"><a ng-click="newDir(item.url)">@{{item.name}}</a></li>
                </ol>

                @if (!empty($list))
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th width="40px;">
                            <span class="file-select-all">
                            {{--<input type="checkbox" ng-model="selectedAll" ng-init="selectedAll=false" value=""/>--}}
                                <input type="checkbox" ng-click="toggleAll()" ng-init="selectedAll=false" value=""/>
                            </span>
                            </th>
                            <th>{{ trans('admin.name') }}</th>
                            <th></th>
                            <th width="200px;">{{ trans('admin.time') }}</th>
                            <th width="100px;">{{ trans('admin.size') }}</th>
                        </tr>
                        <tr ng-repeat="item in list | pageFilter:currentPage*pageSize | limitTo:pageSize as results" ng-mouseenter="item.btn_group=true" ng-mouseleave="item.btn_group=false">
                            <td style="padding-top: 15px;">
                            <span class="file-select">
                            <input type="checkbox" ng-model="item.delete" ng-checked="item.delete" value="" ng-value="item.name"/>
                            </span>
                            </td>
                            <td ng-if="!item.isDir">
                                <div ng-bind-html="item.preview | to_trusted"></div>
                                <a target="_blank" href="@{{item.link}}" class="file-name" title="@{{item.name}}">
                                    @{{ item.icon }} @{{ basename(item.name) }}
                                </a>
                            </td>
                            <td ng-if="item.isDir">
                                <div ng-bind-html="item.preview | to_trusted"></div>
                                <a  ng-click="newDir(item.name)"  class="file-name" title="@{{item.name}}">
                                    @{{ item.icon }} @{{ basename(item.name) }}
                                </a>
                            </td>


                            <td class="action-row" >
                                <div class="btn-group btn-group-xs" ng-if="!item.isDir" ng-init="item.btn_group=false" ng-show="item.btn_group">
                                    <a class="btn btn-default file-rename" data-toggle="modal" data-target="#moveModal" data-name="@{{ item.name }}"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-default file-delete" ng-click="deleteFile(item.name)"><i class="fa fa-trash"></i></a>
                                    <a target="_blank" href="@{{ item.download }}" class="btn btn-default"><i class="fa fa-download"></i></a>
                                    <a class="btn btn-default" data-toggle="modal" ng-click="getUrl(item.url)" ><i class="fa fa-internet-explorer"></i></a>
                                </div>
                            </td>
                            <td>@{{ item.time }}&nbsp;</td>
                            <td>@{{ item.size }}&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                <div class="pull-right" ng-cloak>
                    <button class="btn btn-default btn" ng-click="currentPage=0">
                        First
                    </button>
                    <button class="btn btn-default btn" ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1">
                        Previous
                    </button>
                    <button class="btn btn-default btn" ng-show="currentPage-3>=0" ng-click="currentPage=currentPage-2">
                        @{{currentPage-2}}
                    </button>
                    <button class="btn btn-default btn" ng-show="currentPage-2>=0" ng-click="currentPage=currentPage-2">
                        @{{currentPage-1}}
                    </button>
                    <button class="btn btn-default btn" ng-show="currentPage-1>=0" ng-click="currentPage=currentPage-1">
                        @{{currentPage}}
                    </button>
                    <button class="btn btn-default btn" ng-disabled="true">
                        @{{currentPage+1}}
                    </button>
                    <button class="btn btn-default btn" ng-hide="currentPage+2>numberOfPages()" ng-click="currentPage=currentPage+1">
                        @{{currentPage+2}}
                    </button>
                    <button class="btn btn-default btn" ng-hide="currentPage+3>numberOfPages()" ng-click="currentPage=currentPage+2">
                        @{{currentPage+3}}
                    </button>
                    <button class="btn btn-default btn" ng-hide="currentPage+4>numberOfPages()" ng-click="currentPage=currentPage+2">
                        @{{currentPage+4}}
                    </button>
                    <button class="btn btn-default btn" ng-disabled="currentPage >= list.length/pageSize - 1" ng-click="currentPage=currentPage+1">
                        Next
                    </button>
                    <button class="btn btn-default btn" ng-disabled="numberOfPages()<1" ng-click="currentPage=numberOfPages()-1">
                        Last
                    </button>
                </div>
            </div>
            <!-- /.box-footer -->
            <!-- /.box-footer -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
    <div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="moveModalLabel">Rename & Move</h4>
                </div>
                <form id="file-move">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Path:</label>
                            <input type="text" class="form-control" name="new" />
                        </div>
                        <input type="hidden" name="path"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="urlModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="urlModalLabel">Url</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" ng-value="url" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newFolderModalLabel">New folder</h4>
                </div>
                <form id="new-folder" ng-submit="submitNewFolder()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" ng-model="folder_name" />
                        </div>
                        {{--<input type="hidden" name="dir" value="dir"/>--}}
                        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

