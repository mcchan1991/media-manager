var mediaApp = angular.module('mediaApp', []);
mediaApp.controller('mediaController', function mediaController($scope, $http){
    $scope.list=Object.values(list);
    $scope.nav=Object.values(nav);
    $scope.url=url;
    $scope.currentPage = 0;
    $scope.pageSize = 20;
    $scope.selectedDeleted=[];
    $scope.dir="";

    $scope.numberOfPages=function(){
        return Math.ceil($scope.list.length/$scope.pageSize);
    };

    $scope.media_refresh=function(){
        $http.get($scope.url.refresh)
            .then(function(response) {
                console.log(response.data.list);
                $scope.list = Object.values(response.data.list);
                $scope.nav = Object.values(response.data.nav);
            });
    };


    $scope.basename=function(str){
        var base = new String(str).substring(str.lastIndexOf('/') + 1);
        return base;
    };

    $scope.uploadfile=function(file){
        var fileForm = new FormData();
        //Take the first selected file
        for (var i = 0; i < file; i++) {
            fileForm.append("files[]", file[i]);
        }
        fileForm.append("dir", $scope.dir);
        $http.post($scope.url.upload, fileForm, {
            withCredentials: true,
            headers: {'Content-Type': undefined },
            transformRequest: angular.identity
        }).then(function(response) {
            console.log(response);
            if(response.status=="200"){
                $scope.media_refresh();
            }
        });
    };

    $scope.getUrl=function(url){
        $("#urlModal").modal();
        $scope.url=url;
    }

    $scope.submitNewFolder=function(){
        var url = new URL($scope.url.new_folder);

        url.searchParams.set('dir', "/"+$scope.dir);
        url.searchParams.set('name', $scope.folder_name);
        if($scope.folder_name!=null){
            $http({
                url: url.href,
                method: "POST"
            }).then(function(response) {
                if(response.status=="200"){
                    $scope.media_refresh();
                }
            })
        }
        $("#newFolderModal").modal("hide");
    };

    $scope.selectedAll=function($checked){
        $("#"+id).modal();
    };

    $scope.toggleAll = function() {
        var toggleStatus = !$scope.selectedAll;
        angular.forEach($scope.list, function(itm){ itm.delete = toggleStatus; });
        $scope.selectedAll=!$scope.selectedAll;
    };

    $scope.newDir=function(newdir){
        var url = new URL($scope.url.refresh);
        $scope.dir=newdir;
        url.searchParams.set('path', "/"+newdir);
        $http.get(url.href)
            .then(function(response) {
                $scope.list = Object.values(response.data.list);
                $scope.nav = Object.values(response.data.nav);
            });
    };

    $scope.deleteSelected=function(){
        $scope.files=[];
        var url = new URL($scope.url.delete);
        $scope.list.map(function(item){
            if(item.delete){
                url.searchParams.append('files[]', item.name);
            }
        });

        $http({
            url: url.href,
            method: "POST"
        }).then(function(response) {
            if(response.status=="200"){
                $scope.media_refresh();
            }
        })
    };

    $scope.deleteFile=function(item){
        $scope.files=[];
        var url = new URL($scope.url.delete);
        url.searchParams.set('files[]', item);
        $http({
            url: url.href,
            method: "POST"
        }).then(function(response) {
            if(response.status=="200"){
                $scope.media_refresh();
            }
        })
    };

    $scope.openModal=function(id){
        $("#"+id).modal();

    }


});

angular.module('mediaApp').filter('to_trusted', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

angular.module('mediaApp').filter('pageFilter', function() {
    return function(input, start) {
        start = +start;
        return input.slice(start);
    }
});

angular.module("mediaApp").directive("selectNgFiles", function() {
    return {
        require: "ngModel",
        link: function postLink(scope,elem,attrs,ngModel) {
            elem.on("change", function(e) {
                var files = elem[0].files;
                ngModel.$setViewValue(files);
            })
        }
    }
});