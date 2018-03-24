(function () {
    'use strict';
    angular.module('app.files')
    .directive('uiFolder',['UiTreeHelper',uiFolder])
    .factory('UiTreeHelper',['$window','$document',UiTreeHelper])
    .controller('TreeNodeController',['$scope',TreeNodeController])
    .controller('ModalInstanceCtrl', ['$scope', '$uibModalInstance', '$cookies','$timeout','$treeData','Upload','replace_id', ModalInstanceCtrl])
    .controller('DetailsInstanceCtrl',['$scope','$http','$uibModalInstance','logger','folder',DetailsInstanceCtrl])
    .controller('CtrlFiles', ['$scope','$window','$http','$cookies','$sce','logger','Upload','$timeout','$uibModal','$treeData', CtrlFiles]);
    
    
    function UiTreeHelper($window,$document)
    {
        return {
            
            
            setNodeAttribute: function (scope, attrName, val) {
            if (!scope.$modelValue) {
              return null;
            }
            var data = this.nodesData[scope.$modelValue.$$hashKey];
            if (!data) {
              data = {};
              this.nodesData[scope.$modelValue.$$hashKey] = data;
            }
            data[attrName] = val;
          },

          getNodeAttribute: function (scope, attrName) {
            if (!scope.$modelValue) {
              return null;
            }
            var data = this.nodesData[scope.$modelValue.$$hashKey];
            if (data) {
              return data[attrName];
            }
            return null;
          },
        }
    }
    function TreeNodeController($scope)
    {
         $scope.toggle = function () {
            $scope.collapsed = !$scope.collapsed;
            
            
        };
    }
    function uiFolder(UiTreeHelper)
    {
        return {
          priority: -1,
          restrict: 'A',
          controller: 'TreeNodeController',
          link: function(scope, element, attrs){
            
            
            
            scope.collapsed = !!UiTreeHelper.getNodeAttribute(scope, 'collapsed');
            
            scope.$watch(attrs.collapsed, function (val) {
              if ((typeof val) == 'boolean') {
                scope.collapsed = val;
              }
            });
            
            scope.$watch('collapsed', function (val) {
              UiTreeHelper.setNodeAttribute(scope, 'collapsed', val);
              attrs.$set('collapsed', val);
            });

          
          }
        }
    }
    function ModalInstanceCtrl($scope, $uibModalInstance,$cookies, $timeout,$treeData,Upload,replace_id) {
           
           
           
            //console.log(folder);
            $scope.replace_id = replace_id;
            $scope.uploadFiles = function(files, errFiles) {
                $scope.files = files;
                $scope.errFiles = errFiles;
                angular.forEach(files, function(file) {
                    file.upload = Upload.upload({
                        url: SITE_URL+'admin/files/upload',
                        data: {
                            
                            name:file.name,
                            file: file,
                            csrf_hash_name:$cookies.get(pyro.csrf_cookie_name),
                            folder_id: $scope.current_level,
                            width:'0',
                            height:'0',
                            ratio:'1',
                            alt_attribute:'',
                            replace_id:replace_id,
                            
                        }
                    });
        
                    file.upload.then(function (response) {
                        $timeout(function () {
                            file.result = response.data;
                        });
                    }, function (response) {
                        if (response.status > 0)
                            $scope.errorMsg = response.status + ': ' + response.data;
                    }, function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 * 
                                                 evt.loaded / evt.total));
                    });
                });
            }
            
            $scope.ok = function() {
                $treeData.folder_contents($scope.current_level);/* Verificar funcionamiento */
                $uibModalInstance.close();
            };
    
    }
    function DetailsInstanceCtrl($scope,$http,$uibModalInstance,logger,folder)
    {
          
         
          $scope.detail     = folder;
          $scope.old_item   = folder;
          
          
          $scope.cancelDetail = function() {
                $uibModalInstance.dismiss("cancel");
          };
          $scope.updateDetail=function()
          {
                 var new_description = $scope.detail.description,
        			new_keywords     = $scope.detail.keywords,
        			new_alt_attribute = $scope.detail.alt_attribute,
                    url_post  = '',
                    post_data = {};
                    
                    if(folder.menu === 'folder')
                    {
                        url_post = 'admin/files/save_location';
                        post_data = {
            			
                            
                            folder_id:	folder.id,
            				location:	folder.location,
            				container:	folder.container
            			};
                    }
                    else
                    {
                        url_post = 'admin/files/save_description';
                        post_data = {
            				file_id : folder.id,
            				description : new_description,
            				keywords : new_keywords,
            				old_hash : folder.keywords_hash,
            				alt_attribute : new_alt_attribute
            			};
                    }
        		     // end varold
                    
                    
                    
                    
                    $http.post(SITE_URL+url_post,post_data).then(function(response){
                        
                        var result = response.data;
                        
                        
                        
                        if(result.status)
                        {
                            logger.logSuccess(result.message);
                        }
                        else
                        {
                            logger.logError(result.message);
                        }
                        
                        $uibModalInstance.close();
                        
                    });
        
        		
               
               
          }
    }
    function CtrlFiles($scope,$window,$http,$cookies,$sce,logger,Upload,$timeout,$uibModal,$treeData)
    {
        
        
        $treeData.folder_contents(parent_id);
        
        $scope.collapsed = true;
        
        $scope.options = {
            
            dropped: function(scope) {
                
                var model_values = scope.source.nodesScope.$modelValue,
                    order = { 'folder' : {}, 'file' : {} },
                    form_data    = {};
               
                    
                    
                    
                
               
                angular.forEach(model_values,function(item,index){
                    
                         
                    var tipo = item.menu;
                  
                    
                    order[item.menu][index] = item.id;
                    
                    
                    
                    
                });
                
               
                
               form_data={
                  
                  order : order
               };
                
                
                
                $http.post(SITE_URL+'admin/files/order',form_data).then(function(response){
                        
                        var result = response.data;
                        
                        if(result.status)
                        {
                            logger.logSuccess(result.message);
                        }
                        else
                        {
                            logger.logError(result.message);
                        }
                
                });
            }
            
        };
       
        
        $scope.folder_contents= function(id_parent){
            
            $treeData.folder_contents(id_parent);
        };
        
         $scope.seleccionar = function(index)
         {
                 //$scope.folders[index].selected = !$scope.folders[index].selected;
               
         }
         $scope.save_name = function($index)
         {
                
                var new_name = $scope.folders[$index].name,
                        type = $scope.folders[$index].menu,
                        post = { name: new_name },
                          id = $scope.folders[$index].id;
                
                post[type+'_id'] = id;
                
                if(new_name === $scope.old_name )
                {
                    $scope.folders[$index].on_editable = false;
                    return false;
                }
                
                
                $http.post(SITE_URL + 'admin/files/rename_'+type,post).then(function(response){
                    
                    
                    var result = response.data;
                    
                   if(result.status)
                   {
                        logger.logSuccess(result.message);
                        
                        $scope.folders[$index].name        = result.data.name;
                   }
                   else
                   {
                       logger.logError(result.message);
                   }
                                         
                    $scope.folders[$index].on_editable = false;
                    
                });
                
         }
        
        
        
        /***********Funciones para el menú**********/
             $scope.menu_file = [
                  ['Descargar', function ($itemScope, $event, color) {
                    
                        
                       $window.open('/files/download/'+$itemScope.folder.id);
                       
                     
                  }],
                  ['Cambiar nombre', function ($itemScope, $event, color) {
                     
                      $scope.old_name =  $scope.folders[$itemScope.$index].name;
                      $scope.folders[$itemScope.$index].on_editable = true;
                  }],
                  
                  ['Remplazar', function ($itemScope, $event, color) {
                     
                    
                      var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'myModalUpload.html',
                            controller: 'ModalInstanceCtrl',
                  
                            resolve: {
                                replace_id: function () {
                                    return $itemScope.folder.id;
                                }
                            }
                      });
                  }],
                  ['Eliminar archivo', function ($itemScope, $event,$index) {
                    
                        $treeData.delete_file($itemScope);
                     
                  }],
                  null,
                  ['Detalles', function ($itemScope, $event) {
                        
                        
                        
                         var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'myModalDetails.html',
                            controller: 'DetailsInstanceCtrl',
                            resolve: {
                                folder: function () {
                                    return $itemScope.folder;
                                }
                            }
                        });
                        
                        
                        
                     
                  }]
               ];
              $scope.menu_main = [
                 
                 
                  
                  
                   ['Nueva carpeta', function ($itemScope, $event, color) {
                     
                       
                       var new_folder = $treeData.new_folder();
                       
                       if(new_folder.id == true)
                       {
                           $scope.folders_sidebar.push(new_folder);
                       }
                       
                       
                       
                      // $scope.folders_sidebar = $treeData.new_folder();
                       
                      
                     
                      
                      
                     
                      
                      
                     
                  }],
                  ['Subir archivo', function ($itemScope, $event, color) {
                  	  
                          
                          
                          

                        var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'myModalUpload.html',
                            controller: 'ModalInstanceCtrl',
                            //size: size,
                            resolve: {
                               replace_id: function () {
                                 return false;
                               }
                            }
                        });
                        
                       
            
                        modalInstance.result.then(function (selectedItem) {
                           // $scope.selected = selectedItem;
                        }, function () {
                            
                            $treeData.folder_contents($scope.current_level); //verificar funcionamiento
                            
                        });
        
                  },function(){
                    
                    
                      if(!$scope.current_level)
                        return false;
                        
                      return true;
                  }]
              ];
         $scope.menu_folder = [
                  ['Abrir', function ($itemScope, $event, color) {
                  	   
                          
                          
                       $treeData.folder_contents($itemScope.folder.id);
                  }],
                  
                 
                  ['Cambiar nombre', function ($itemScope, $event, color) {
                     
                      $scope.old_name =  $scope.folders[$itemScope.$index].name;
                      $scope.folders[$itemScope.$index].on_editable = true;
                  }],
                  ['Eliminar', function ($itemScope, $event,$index) {
                      
                     
                      $treeData.delete_folder($itemScope);
                      
                    
                  }],
                  null,
                   ['Detalles', function ($itemScope, $event, color) {
                    
                      //$scope.last_click = $itemScope.folder;
                      //$scope.details($itemScope.folder);
                      //fnDetails($itemScope.folder);
                      
                        var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'myModalDetails.html',
                            controller: 'DetailsInstanceCtrl',
                            //size: size,
                            resolve: {
                                folder: function () {
                                    return $itemScope.folder;
                                }
                            }
                        });
                  }],
                  
              ];
         
    }

})();