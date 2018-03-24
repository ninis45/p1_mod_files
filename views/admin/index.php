<section ng-controller="CtrlFiles">
    <div class="row">
            <div class="col-md-12">
                <section class="title">
                	<h4 id="file-title">
                		<?php echo lang('files:files_title') ?>
                	</h4>
                
                	<div id="file-toolbar">
                		<!--div id="file-buttons">
                			<ul class="button-menu-source buttons">
                				<li class="button animated fadeIn" data-applies-to="pane root-pane"	data-menu="refresh"><?php echo lang('files:refresh') ?></li>
                				<li class="button animated fadeIn" data-applies-to="folder" data-menu="open"><?php echo lang('files:open') ?></li>
                				<li class="button animated fadeIn" data-role="create_folder"	data-applies-to="pane root-pane" data-menu="new-folder"><?php echo lang('files:new_folder') ?></li>
                				<li class="button animated fadeIn" data-role="upload" data-applies-to="folder pane" data-menu="upload"><?php echo lang('files:upload') ?></li>
                				<li class="button animated fadeIn" data-role="edit_file" data-applies-to="file" data-menu="rename"><?php echo lang('files:rename') ?></li>
                				<li class="button animated fadeIn" data-role="edit_folder" data-applies-to="folder" data-menu="rename"><?php echo lang('files:rename') ?></li>
                				<li class="button animated fadeIn" data-role="download_file" data-applies-to="file" data-menu="download"><?php echo lang('files:download') ?></li>
                				<li class="button animated fadeIn" data-role="synchronize" data-applies-to="folder" data-menu="synchronize"><?php echo lang('files:synchronize') ?></li>
                				<li class="button animated fadeIn" data-role="upload delete_file" data-applies-to="file" data-menu="replace"><?php echo lang('files:replace') ?></li>
                				<li class="button animated fadeIn" data-role="delete_file" data-applies-to="file" data-menu="delete"><?php echo lang('files:delete') ?></li>
                				<li class="button animated fadeIn" data-role="delete_folder" data-applies-to="folder" data-menu="delete"><?php echo lang('files:delete') ?></li>
                				<li class="button animated fadeIn" data-applies-to="folder file pane"	data-menu="details"><?php echo lang('files:details') ?></li>
                			</ul>
                		</div-->
                		
                	</div>
                </section>
            </div>
    </div>
    <div class="row"  >
    
    
        
    	<div class="col-md-4">
            
        		<ul id="folders-sidebar"  class="list-unstyled" data-slim-scroll data-scroll-height="450px">
        			<li class="folder places" data-id="<?=$id_parent?>"><a href="javascript:;" data-ng-click="folder_contents(<?=$id_parent?>)" ><?php echo lang('files:places') ?></a></li>
        			<li class="folder" ui-folder  ng-repeat="folder in folders_sidebar" ng-include="'items_renderer.html'" >
                    
                        
                        
                    </li>
                    <?php /*if ( ! $folders) : ?>
        				<li class="no_data"><?php echo lang('files:no_folders_places') ?></li>
        			<?php elseif ($folder_tree) : ?>
        				<?php echo tree_builder($folder_tree, '<li class="folder" data-id="{{ id }}" data-name="{{ name }}"><div></div><a href="javascript:;" data-ng-click="folder_contents({{ id }})">{{ name }}</a>{{ children }}</li>') ?>
        			<?php endif*/ ?>
        		</ul>
    	   
        </div>
        <div class="col-md-8" ui-tree="options">
                <div>
                    
                        <input type="text" id="file-search" name="file-search" ng-model="searchFile" value="" placeholder="<?php echo lang('files:search_message') ?>"/>
                    
                    
                </div>
                <div class="divider"></div>
                <ul class="folders-center" context-menu="menu_main" ui-tree-nodes ng-model="folders" data-slim-scroll data-scroll-height="450px">
                     <li  ui-tree-node   tooltip-placement="top" uib-tooltip="{{ folder.name }}" class="files-tooltip {{ folder.el_type }}" ng-class="{ selected: folder.selected }" ng-click="seleccionar($index)"   data-id="{{ folder.id }}" data-name="{{ folder.name }}"  ng-repeat="folder in folders | filter:searchFile"  context-menu="menu_{{ folder.menu }}">
                         <img ng-if="folder.img" src="{{ folder.img }}" />
                         
                         <span class="name-text" >
                            <input type="text" ng-model="folder.name"  ng-if="folder.on_editable" value="Carpeta sin nombre"  ng-blur="save_name($index)" /> 
                            {{ folder.name }}
                            
                            
                         
                         </span>
                     </li>
                     
                </ul>
        </div>
    </div>

</section>

<script type="text/ng-template" id="items_renderer.html">
                <div>
                    <span>{{folder.children}}</span>
                </div>
            </script>
            <script type="text/ng-template" id="items_renderer.html">
                <div>
                 <span class="ui-tree-icon" >   
                       <a class="angular-ui-tree-icon" data-nodrag data-ng-show="folder.children.length > 0" ng-click="toggle(this)">
                           <span class="angular-ui-tree-icon-collapse" ng-class="{'collapsed': collapsed, 'uncollapsed': !collapsed}"></span>
                       </a>
                  </span>
                  
                       <a class="name" href="#" data-ng-click="folder_contents(folder.id)">{{folder.name}}</a>
                </div>
                <ul   ng-model="folder.children" ng-class="{hidden: collapsed}">
                    <li  ng-repeat="folder in folder.children"  ng-include="'items_renderer.html'">
                    </li>
                </ul>
</script>
<script type="text/ng-template" id="myModalDetails.html">
                            <div class="modal-header">
                                <h3><?php echo lang('files:details') ?></h3>
                            </div>
                            <div class="modal-body">
                            <?php echo form_open();?>
                                 <dl class="dl-horizontal">
                    				<dt><?php echo lang('files:id') ?>:</dt> 
                   					<dd class="id">{{ detail.id }}</dd>
                    				
                    				<dt><?php echo lang('files:name') ?>:</dt> 
                   					<dd class="name">{{ detail.name }}</dd>
                    				
                    
                    				
                    			</dl>
                                <div class="divider divider-dashed"></div>
                    			<dl class="dl-horizontal">
                    				<dt ng-if="detail.slug"><?php echo lang('files:slug') ?>:</dt> 
                   					<dd ng-if="detail.slug" class="slug">{{ detail.slug }}</dd>
                    				
                    				<dt ng-if="!detail.slug"><?php echo lang('files:path') ?>:</dt> 
                    				<dd ng-if="!detail.slug">	<input readonly="readonly" type="text" class="path form-control" ng-model="detail.path"/></dd>
                    				
                    				<dt><?php echo lang('files:added') ?>:</dt> 
                   					<dd class="added">{{ detail.formatted_date }}</dd>
                    				
                    				<dt ng-if="detail.width"><?php echo lang('files:width') ?>:</dt> 
                   					<dd ng-if="detail.width"class="width">{{ detail.width }}px</dd>
                    				
                    				<dt ng-if="detail.height"><?php echo lang('files:height') ?>:</dt> 
                   					<dd ng-if="detail.height" class="height">{{ detail.height }}px</dd>
                    				
                    				<dt ng-if="!detail.slug"><?php echo lang('files:filename') ?>:</dt> 
                   					<dd ng-if="!detail.slug" class="filename">{{ detail.filename }}</dd>
                    				
                    				<dt ng-if="detail.filesize"><?php echo lang('files:filesize') ?>:</dt> 
                   					<dd ng-if="detail.filesize" class="filesize">{{ detail.filesize }}</dd>
                    			
                    				<dt ng-if="!detail.slug"><?php echo lang('files:download_count') ?>:</dt> 
                   					<dd ng-if="!detail.slug" class="download_count">{{ detail.download_count }}</dd>
                                    
                                    <dt ng-if="detail.menu=='folder'"><?php echo lang('files:location') ?>:</dt> 
					                <dd ng-if="detail.menu=='folder'"><?php echo form_dropdown('location', $locations, '', 'class="location form-control" ng-model="detail.location"') ?></dd>
				
				                    <dt ng-if="detail.menu=='folder'"><?php echo lang('files:bucket') ?>:</dt> 
					                <dd ng-if="detail.menu=='folder'"><?php echo form_input('bucket', '', 'class="container amazon-s3 form-control"') ?>
					                <a ng-if="detail.menu=='folder'" class="container-button button"><?php echo lang('files:check_container') ?></a></dd>
				
				                    <dt ng-if="detail.menu=='folder'"><label><?php echo lang('files:container') ?>:</dt> 
					                <dd ng-if="detail.menu=='folder'"><?php echo form_input('container', '', 'class="container rackspace-cf form-control" ng-model="detail.container"') ?>
		                              <a class="container-button button"><?php echo lang('files:check_container') ?></a>
				                    </dd>
                    				
                    			
                    				
                    				
                    			</dl>
                    
                    			<dl class="dl-horizontal" ng-if="!detail.slug">
                    				<dt><?php echo lang('files:alt_attribute') ?>:</dt>
                    				<dd>	<input type="text" class="alt_attribute form-control" ng-model="detail.alt_attribute" /></dd>
                    				
                    				<dt><?php echo lang('files:keywords') ?>:</dt>
                    				<dd>	<?php echo form_input('keywords', '', 'ng-model="detail.keywords" id="keyword_input" class="form-control"') ?></dd>
                    			
                    				<dt><?php echo lang('files:description') ?>:</dt> 
                    				<dd><textarea class="form-control description" ng-model="detail.description"></textarea></dd>
                    				
                    			</dl>
                            <?php echo form_close();?>
                            </div>
                            <div class="modal-footer">
                                <button ui-wave class="btn btn-flat" ng-click="cancelDetail()">Cancelar</button>
                                <button ui-wave class="btn btn-flat btn-primary"  ng-click="updateDetail()">Guardar</button>
                            </div>
</script>
<script type="text/ng-template" id="myModalUpload.html">
                            <div class="modal-header">
                                <h3>Subir archivos</h3>
                            </div>
                            <div class="modal-body" id="files-uploader">
                            
                                <div class="alert alert-danger" ng-if="replace_id"><?php echo lang("files:replace_warning")?></div>
                                <button class="file_upload" ngf-select="uploadFiles($files, $invalidFiles)" multiple
                                  accept="*/*" ngf-max-height="10000" ngf-max-size="256MB">
                                 Selecciona los archivos</button>
                                 
                                 <ul id="files-uploader-queue" class="ui-corner-all">
                                    <li class="ui-progressbar ui-widget ui-widget-content ui-corner-all" ng-repeat="f in files"  style="font:smaller">
                                     
                                      
                                       <h4>{{f.name}} {{f.$errorParam}}</h4>
                                       <md-progress-linear md-mode="determinate"  value="{{f.progress}}"></md-progress-linear>
                                       <div class="divider divider-lg divider-dashed">{{f.result.message}}</div>
                                       
                                      
                                    </li>
                                    <li ng-repeat="f in errFiles" style="font:smaller">{{f.name}} {{f.$error}} {{f.$errorParam}}
                                    </li> 
                                  </ul>
                                  
                                  <span ng-bind-html="errorMsg"></span>
                            </div>
                            <div class="modal-footer">
                                
                                <button ui-wave class="btn btn-flat btn-primary" ng-click="ok()">Aceptar</button>
                            </div>
    </script>
