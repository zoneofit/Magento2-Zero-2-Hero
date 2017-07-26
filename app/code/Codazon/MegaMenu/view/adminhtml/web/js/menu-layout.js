;(function($){
	$.extend( $.ui, {
		plugin: {
			add: function( module, option, set ) {
				var i,
					proto = $.ui[ module ].prototype;
				for ( i in set ) {
					proto.plugins[ i ] = proto.plugins[ i ] || [];
					proto.plugins[ i ].push( [ option, set[ i ] ] );
				}
			},
			call: function( instance, name, args ) {
				var i,
					set = instance.plugins[ name ];
				if ( !set || !instance.element[ 0 ].parentNode || instance.element[ 0 ].parentNode.nodeType === 11 ) {
					return;
				}
				for ( i = 0; i < set.length; i++ ) {
					if ( instance.options[ set[ i ][ 0 ] ] ) {
						set[ i ][ 1 ].apply( instance.element, args );
					}
				}
			}
		},
		contains: $.contains,
		hasScroll: function( el, a ) {
			if ( $( el ).css( "overflow" ) === "hidden") {
				return false;
			}
			var scroll = ( a && a === "left" ) ? "scrollLeft" : "scrollTop",
				has = false;
	
			if ( el[ scroll ] > 0 ) {
				return true;
			}
			el[ scroll ] = 1;
			has = ( el[ scroll ] > 0 );
			el[ scroll ] = 0;
			return has;
		},
		isOverAxis: function( x, reference, size ) {
			return ( x > reference ) && ( x < ( reference + size ) );
		},
		isOver: function( y, x, top, left, height, width ) {
			return $.ui.isOverAxis( y, top, height ) && $.ui.isOverAxis( x, left, width );
		}
	});
	
	$.fn.menuLayout = function(options){
		var defaultConfig = {
			menuParentClass: 'menu',
			depthClass: 'menu-item-depth-',
			transportClass: 'menu-item-transport',
			placeholderClass: 'sortable-placeholder',
			editClass: 'item-edit',
			deleteClass: 'item-delete',
			cancelClass: 'item-cancel',
			ungroupClass: 'item-ungroup',
			activeClass: 'menu-item-edit-active',
			inactiveClass: 'menu-item-edit-inactive',
			deletingClass: 'deleting',
			addToMenuClass: 'add-to-menu',
			hideChildrenClass:'hide-children',
			items: '.menu-item',
			itemHandle: '.menu-item-handle',
			itemSettings: '.menu-item-settings',
			itemBtn: '.menu-btn',
			typeItemsList:'#type-items',
			minDepth: 0,
			maxDepth: 11,
			step: 40,
			isRTL: false,
			negateIfRTL: 1,
			targetTolerance: 0,
			menuItemTypes: [],
			menuItems: [],
			phrase: {
				untitled: 'Untitled',
				maximumColumns: 'Maximum of columns is ',
				preview: 'Preview',
				warning: 'Warning',
				removeItemWarning:'Are you sure to delete this item? All its children will be also removed.'
			},
			previewUrl: null,
			previewBtn: '#preview-btn',
			previewForm: '#edit_form',
			previewArea: '#preview-area',
			expandBtn: '#expand-btn',
			collapseBtn: '#collapse-btn',
			contentField: 'input[name="content"]',
			alert: false,
			confirm: false,
			modal: false,
			windowId: 'cdzmenu',
			mediaUrl: '',
			imagePlaceholder: '',
			imageIconChar: 'file-image-o',
			imageIconStyle: 'color:#aaa6a0',
			spinner: '#menu-spinner'
		}
		
		var autoNum = 0;
		var lastSearch = '';
		var conf = $.extend({},defaultConfig,options);
		var $spinner = $(conf.spinner);
		
		window.getTmplById = function(id,data){
			return $('<div></div>').append($('#'+id).tmpl(data));
		}
		window.uniqid = function(prefix){
			if(typeof prefix !== 'string') prefix = '';
			return prefix + Math.floor(Math.random()*10000);	
		}
		String.prototype.equalsTo = function(string) {
			return (this == string);
		};
		$.extend(jQuery.tmpl.tag, {
			"for": {
				_default: {$2: "var i=1;i<=1;i++"},
				open: 'for ($2){',
				close: '};'
			}
		});
		return this.each(function(id,el){
			var $this = $(this);
			$this.addClass(conf.menuParentClass);
			$.fn.extend({
				menuDepthClass: function(){
					var $item = $(this);
					var matchExp = new RegExp(conf.depthClass+'\\d','g');
					if($item.attr('class')){
						var depthClass = $item.attr('class').match(matchExp);
						return (depthClass === null)?null:depthClass[0];
					}else{
						return null;	
					}
				},
				menuItemDepth: function(){
					var $item = $(this);
					var margin = conf.isRTL ? this.eq(0).css('margin-right') : this.eq(0).css('margin-left');
					return pxToDepth( margin && -1 != margin.indexOf('px') ? margin.slice(0, -2) : 0 );
					
					/*var $item = $(this);
					var depthClass = $item.menuDepthClass();
					return (depthClass == null)?0:parseInt(depthClass.replace(conf.depthClass,''));*/
				},
				filterDepth: function(depth){
					var $item = $(this);
					var $prev = $item.prev();
					var $next = $item.next();
					if($prev.length == 0){
						depth = conf.minDepth;
					}else{
						prevDepth = $prev.menuItemDepth();
						if(depth > prevDepth) depth = prevDepth + 1;
						if($next.length > 0){
							nextDepth = $next.menuItemDepth();
							if(nextDepth > prevDepth){
								depth = nextDepth;
							}
						}
						if(depth > conf.maxDepth) depth = conf.maxDepth;
						if(depth < conf.minDepth) depth = conf.minDepth;
					}
					return depth;
				},
				getChildren: function(){
					var $item = $(this);
					var check = false;
					var depth = $item.menuItemDepth();
					var $next = $item.next();
					var $children = $();
					while(($next.menuItemDepth() > depth) && ($next.length)){
						$children.push($next);
						$next = $next.next(conf.items);
					}
					return $children;
				},
				updateNoChildrenClass: function(){
					return this.each(function(){
						$menu = $(this);
						$(conf.items,$menu).each(function(){
							var $item = $(this);
							if($item.getChildren().length == 0){
								$item.addClass('no-children');
							}else{
								$item.removeClass('no-children');
							}
						});	
					});
				},
				updateDepthClass: function(depth, oldDepth){
					return this.each(function(){
						var $item = $(this),
						oldDepth = oldDepth || $item.menuItemDepth();
						$item.removeClass(conf.depthClass + oldDepth).addClass(conf.depthClass + depth);
					});
				},
				shiftDepthClass : function(change) {
					return this.each(function(){
						var $item = $(this),
							depth = $item.menuItemDepth();
						$item.updateDepthClass(depth + change);
					});
				},
				removeMenuItem: function(){
					return this.each(function(){
						var $item = $(this),
						removeItem = function(){
							$children = $item.getChildren();
							$item.addClass(conf.deletingClass).animate({
								opacity : 0,
								height: 0
							}, 350, function(){
								$item.remove();
								//$children.shiftDepthClass(-1);
								$children.each(function(){
									$(this).remove();
								});
								$this.updateNoChildrenClass();
							});
							
						}
						cdzmenu.confirm(conf.phrase.warning,conf.phrase.removeItemWarning,{confirm: removeItem});
					});
				},
				resetFieldId: function(){
					return this.each(function(){
						var $item = $(this);
						$('.menu-field',$item).each(function(index, element) {
							var $field = $(this);
							var type = $field.data('type');
							var oldId = $field.attr('id');
							newId = uniqid(type+'_'),
							$parent = $field.parents('.menu-item-field');
							if(oldId){
								$field.attr('id',newId);
								$('.content-btn',$parent).each(function(){
									var $btn = $(this);								
									var onclick = $btn.attr('onclick');
									if(onclick){
										onclick = onclick.replace(oldId,newId);
										$btn.attr('onclick',onclick);
									}
								});
							}
							if(type == 'icon'){
								$('.preview-icon',$parent).attr('id','preview-'+newId);	
							}
							if(type == 'category'){
								cdzInstantiateChooser(newId);
							}
                        });
					});
				},
				eventOnClickEditLink: function(){
					return this.each(function(){
						var $btn = $(this), $settings, $item;
						$item = $btn.parents(conf.items).first();
						$settings = $(conf.itemSettings,$item);
						if($item.hasClass(conf.activeClass)){
							$settings.slideUp(200);
							$item.removeClass(conf.activeClass).addClass(conf.inactiveClass);
						}else{
							$settings.slideDown(200);
							$item.addClass(conf.activeClass).removeClass(conf.inactiveClass);
						}
					});
				},
				eventOnClickCancelLink: function(){
					return this.each(function(){
						var $btn = $(this);
						$btn.eventOnClickEditLink();
					});
				},
				eventOnClickDeleteLink: function(){
					return this.each(function(){
						var $btn = $(this), $item = $btn.parents(conf.items).first();
						$item.removeMenuItem();
					});
				},
				eventOnclickUngroupLink: function(){
					return this.each(function(){
						var $btn = $(this), $item = $btn.parents(conf.items).first();
						var depth = $item.menuItemDepth(), childDepth = depth + 1;
						var $next = $item.next();
						var $children = $item.getChildren();
						var length = $children.length;
						$item.toggleClass(conf.hideChildrenClass);
						if(length > 0){
							$children.each(function(i,el){
								var $child = $(this);
								if($item.hasClass(conf.hideChildrenClass)){
									$child.fadeOut(200);
									$child.addClass(conf.hideChildrenClass);
								}else{
									if($child.menuItemDepth() == (depth+1)){
										$child.fadeIn(200);
										//$child.removeClass(conf.hideChildrenClass);
									}
								}
							});							
						}else{
							$item.removeClass(conf.hideChildrenClass);
						}
					});
				},
				eventOnClickAddLink: function(){
					return this.each(function(){
						var $btn = $(this), $item = $btn.parents(conf.items).first();
						var $cloneItem = $item.clone();
						$this.append($cloneItem);
						$('.'+conf.addToMenuClass,$cloneItem).remove();
						$cloneItem.eventOnClickAllLinks();
						$cloneItem.resetFieldId();
						
						//$cloneItem.removeClass(conf.activeClass).addClass(conf.inactiveClass);
						$(conf.itemSettings,$cloneItem).hide();
						$cloneItem.hide();
						$cloneItem.fadeIn(300);
						var itemTop = $cloneItem.offset().top,
						itemHeight = $cloneItem.height(),
						winHeight = $(window).height(), winTop = $(window).scrollTop();
						if( (winTop + winHeight) < itemTop ){
							$('html,body').animate({scrollTop: itemTop - 10},300);
						}
						$this.sortable('refresh');
						$this.updateNoChildrenClass();
						$('.content-row',$cloneItem).cdzAccordion();
					});
				},
				eventOnClickAllLinks: function(){
					return this.each(function(){
						var $item = $(this);
						$(conf.itemBtn,$item).each(function(){
							var $btn = $(this);
							if($btn.hasClass(conf.editClass)){
								$btn.click(function(){
									$btn.eventOnClickEditLink();
								});
							}else if($btn.hasClass(conf.cancelClass)){
								$btn.click(function(){
									$btn.eventOnClickCancelLink();
								});
							}else if($btn.hasClass(conf.deleteClass)){
								$btn.click(function(){
									$btn.eventOnClickDeleteLink();
								});
							}else if($btn.hasClass(conf.addToMenuClass)){
								$btn.click(function(){
									$btn.eventOnClickAddLink();
								});
							}else if($btn.hasClass(conf.ungroupClass)){
								$btn.click(function(){
									$btn.eventOnclickUngroupLink();
								});
							}
						});
					});
				},
				updateGroup: function(){
					return this.each(function(){
						
					});
				}
			});
			
			//Variables
			
			var transport = '.'+conf.transportClass;
			var currentDepth = 0, originalDepth, minDepth, maxDepth,
			$prev, $next, prevBottom, nextThreshold, helperHeight, $transport, maxChildDepth,
			menuMaxDepth = initialMenuMaxDepth(),
			menuEdge = $this.offset().left;
			
			function initialMenuMaxDepth(){
				var maxDepth = 0;
				$this.find(' > '+conf.items).each(function(){
					var $item = $(this);
					if(maxDepth < $item.menuItemDepth()){
						maxDepth = 	$item.menuItemDepth();
					}
				});
				return maxDepth;
			};
			function updateSharedVars(ui) {
				var depth;
				$prev = ui.placeholder.prevAll( conf.items+':visible' ).first();
				$next = ui.placeholder.nextAll( conf.items+':visible' ).first();
				if( $prev[0] == ui.item[0] ) $prev = $prev.prevAll( conf.items +':visible' ).first();
				if( $next[0] == ui.item[0] ) $next = $next.nextAll( conf.items +':visible').first();
								
				prevBottom = ($prev.length) ? $prev.offset().top + $prev.height() : 0;
				nextThreshold = ($next.length) ? $next.offset().top + $next.height() / 3 : 0;
				minDepth = ($next.length) ? $next.menuItemDepth() : 0;
				if( $prev.length ){
					maxDepth = ( (depth = $prev.menuItemDepth() + 1) > conf.maxDepth ) ? conf.maxDepth : depth;
				}else{
					maxDepth = 0;
				}
			}
			function updateCurrentDepth(ui, depth) {
				ui.placeholder.updateDepthClass(depth, currentDepth);
				currentDepth = depth;
			}
			function updateMenuMaxDepth(depthChange) {
				var depth, newDepth = menuMaxDepth;
				if ( depthChange === 0 ) {
					return;
				} else if ( depthChange > 0 ) {
					depth = maxChildDepth + depthChange;
					if( depth > menuMaxDepth )
						newDepth = depth;
				} else if ( depthChange < 0 && maxChildDepth == menuMaxDepth ) {
					while( ! $(conf.depthClass + newDepth, $this).length && newDepth > 0 )
						newDepth--;
				}
				menuMaxDepth = newDepth;
			}
			function depthToPx(depth){
				return depth * conf.step;
			};
			function pxToDepth(px){
				return Math.floor(px / conf.step);
			}
			
			function initSortable(){
				$this.sortable({
					items: conf.items,
					handle: conf.itemHandle,
					placeholder: conf.placeholderClass,
					start: function(event, ui){
						$this.addClass('sorting');
						//$(conf.items,$this).show().removeClass(conf.hideChildrenClass);
						
						var $item = ui.item;
						if($item.next()[0] == ui.placeholder[0]){
							$item.next().addClass($item.menuDepthClass());
						}
						
						var $parent = ( $item.next()[0] == ui.placeholder[0] ) ? $item.next() : $item;
						
						var $children = $parent.getChildren();
						
						$transport = ui.item.children(transport);
						$children.each(function(id,el){
							var $child = $(this);
							$child.appendTo($transport);
						});
						
						originalDepth = ui.item.menuItemDepth();
						updateCurrentDepth(ui, originalDepth);
						
						var height = $transport.outerHeight();
						height += ( height > 0 ) ? (ui.placeholder.css('margin-top').slice(0, -2) * 1) : 0;
						height += ui.helper.outerHeight();
						helperHeight = height;
						height -= 2; // Subtract 2 for borders
						ui.placeholder.height(height);
						
						maxChildDepth = originalDepth;
						$children.each(function(){
							var depth = $(this).menuItemDepth();
							maxChildDepth = (depth > maxChildDepth) ? depth : maxChildDepth;
						});
						width = ui.helper.find(conf.itemHandle).outerWidth();
						width += depthToPx(maxChildDepth - originalDepth);
						width -= 2; // Subtract 2 for borders
						ui.placeholder.width(width);
						
						var $tempHolder = ui.placeholder.next(conf.items);
						$tempHolder.css( 'margin-top', helperHeight );
						
						
						ui.placeholder.detach();
						$this.sortable('refresh');
						ui.item.after(ui.placeholder);
						$tempHolder.css('margin-top', 0);
						updateSharedVars(ui);
					},
					stop: function(event, ui){
						var depthChange = currentDepth - originalDepth;
						var $children = $transport.children().insertAfter(ui.item);
						ui.placeholder.remove();
						if ( 0 !== depthChange ) {
							ui.item.updateDepthClass( currentDepth );
							$children.shiftDepthClass( depthChange );
							updateMenuMaxDepth( depthChange );
						}
						ui.item[0].style.top = 0;
						if ( conf.isRTL ) {
							ui.item[0].style.left = 'auto';
							ui.item[0].style.right = 0;
						}
						$this.removeClass('sorting');
						$this.updateNoChildrenClass();
					},
					change: function(e, ui){
						if( ! ui.placeholder.parent().hasClass(conf.menuParentClass) )
							($prev.length) ? $prev.after( ui.placeholder ) : $this.prepend( ui.placeholder );
						updateSharedVars(ui);
					},
					sort: function(e,ui){
						var offset = ui.helper.offset(),
						edge = conf.isRTL ? offset.left + ui.helper.width() : offset.left,
						depth = conf.negateIfRTL * pxToDepth( edge - menuEdge );
						
						if ( depth > maxDepth || offset.top < ( prevBottom - conf.targetTolerance ) ) {
							depth = maxDepth;
						} else if ( depth < minDepth ) {
							depth = minDepth;
						}
						if( depth != currentDepth ){
							updateCurrentDepth(ui, depth);
						}
						if( nextThreshold && offset.top + helperHeight > nextThreshold ) {
							$next.after( ui.placeholder );
							updateSharedVars( ui );
							$this.sortable( 'refreshPositions' );
						}
					}
				});
			};
			
			var $menuTypeTmpl = $('#menu-item-type-tmpl'), $menuTypeList = $(conf.typeItemsList);
			var $itemContent = $('#menu-item-content-tmpl');
			var menuTypesObject;
			function attacheEventToButtons(){
				$(conf.items,$this).eventOnClickAllLinks();
			};
			function menuItemTypeTmpl(){
				$(conf.menuItemTypes).each(function(id,typeItem){
					var $typeItem = $menuTypeTmpl.tmpl(typeItem);
					$typeItem.appendTo($menuTypeList);
					if(typeItem.name != 'heading'){
						$itemContent.tmpl(typeItem.content).appendTo($('.menu-item-fields',$typeItem));
					}
				});
				$('[data-type="category"]',$menuTypeList).each(function(){
					var $field = $(this);
					cdzInstantiateChooser($field.attr('id'));
				});
				$(conf.items,$menuTypeList).eventOnClickAllLinks();
				$(conf.items,$menuTypeList).draggable({
					cancel: ".type-heading",
					connectToSortable: $this,
					helper: "clone",
					revert: "invalid",
					handle: conf.itemHandle,
					stop: function(event, ui) {
						ui.helper.css({width:'',height:'',right:'',bottom:'',top:'', zIndex: ''});
						$('.'+conf.addToMenuClass,ui.helper).remove();
						ui.helper.eventOnClickAllLinks();
						ui.helper.resetFieldId();
						ui.helper.removeClass('ui-draggable');
						$(conf.itemHandle,ui.helper).removeClass('ui-draggable-handle');
						$('.content-row',ui.helper).cdzAccordion();
					}
				});
			};
			function reformatTypesArray(typeArray){
				var cloneTypeArray = JSON.parse(JSON.stringify(typeArray));
				var typeOject = {};
				$(cloneTypeArray).each(function(i,el){
					var key = el.name;
					typeOject[key] = el;
					var content = {};
					$(typeOject[key].content).each(function(id1,el1){
						var ctKey = el1.name;
						content[ctKey] = el1;
					});
					//typeOject[key]['content'] = content;
				});
				return typeOject;
			}
			function filterImage(value){
				if( (typeof value !== 'undefined') && (value != '')){
					var patt = new RegExp('url="\\S+"','g');
					if( patt.test(value)){
						var src = conf.mediaUrl + (value).match(patt)[0].replace(/"/g,'').replace(/url=/,"");
					}else{
						var src = value;
					}
				}else{
					var src = conf.imagePlaceholder;	
				}
				return src;
			}
			function loadMenu(menuItems){
				$spinner.show();
				if(!menuItems){
					menuItems = conf.menuItems;
				}
				$this.empty();
				var menuTypesObject = reformatTypesArray(conf.menuItemTypes);
				
				$(menuItems).each(function(id,el){
					var typeItem = menuTypesObject[el.item_type];
					if(typeof typeItem !== 'undefined'){
						var $item = $menuTypeTmpl.tmpl(menuTypesObject[el.item_type]);
						$item.appendTo($this);
						if(typeof el.content.content !== 'undefined'){
							$(typeItem.content).each(function(ctId,ctEl){
								if(ctEl.name == 'content'){
									console.log(typeof el.content.content);
									if(el.content.content.constructor === Array){
										ctEl.columns = el.content.content.length;
									}else{
										ctEl.columns = 1;
									}
								}
							});
						}
						$item.updateDepthClass(el.depth);
						$itemContent.tmpl(typeItem.content).appendTo($('.menu-item-fields',$item));
						$('.type__editor',$item).each(function(){
							var $fieldEditor = $(this);
							$('.content-col',$fieldEditor).each(function(fid,fel){
								var $col = $(this);
								$('.column-number',$col).text(fid+1);
								$('[data-name="content"]',$col).data('columns', fid );
							});
						});
											
						$item.eventOnClickAllLinks();
						$('.add-to-menu',$item).remove();
						$('.menu-field',$item).each(function(){
							var $field = $(this);
							var type = $field.data('type');
							var name = $field.data('name');
							var value = el.content[name];
							switch(type){
								case 'image':
									$field.val(value);
									if($field.parent().find('.preview-image').length && (value != '')){
										var $preview = $field.parent().find('.preview-image');
										var src = filterImage(value);
										$preview.data('href',src);
										$preview.find('img').attr('src',src);
									}

									break;
								case 'text':
									$field.val(value);
									if(name == 'label'){
										if(value!=''){
											var label = value;
										}else{
											var label = conf.phrase.untitled;		
										}
										$('.menu-item-bar .link-title',$item).text(label);
									}
									break;
								case 'icon':
									$field.val(value);
									break;
								case 'dropdown':
									$('option[value="'+value+'"]',$field).attr('selected','selected');
									break;
								case 'textarea':
								case 'editor':
									if(value.constructor === Array){
										$field.val(value[$field.data('columns')]);
									}else{
										$field.val(value);
									}
									break;
								case 'category':
									$field.val(value);
									cdzInstantiateChooser($field.attr('id'));
									break;
								default:
									$field.val(value);
									break;
							}
						});
						$('.field-icon_type',$item).each(function(){
							var $iconType = $(this), iconType = $iconType.val(),
							$typeParent = $iconType.parents('.menu-item-field'),
							$fontParent = $typeParent.next(),									
							$imageParent = $typeParent.next().next();
							if(iconType == 0){
								var $iconFont = $fontParent.find('.field-icon_font');
								var value = $iconFont.val();
								if(value != ''){
									attachIconToItemHeading($iconFont,value,0);
								}
								$fontParent.show();
								$imageParent.hide();
							}else{
								var $iconImage =  $imageParent.find('.field-icon_img');
								var value = $iconImage.val();
								attachIconToItemHeading($iconImage,value,1);
								$fontParent.hide();
								$imageParent.show();
							}
						});
						$('.field-layout',$item).each(function(){
							var $layout = $(this), $parent = $layout.parents('.menu-item-field');
							var layout = $layout.val().split(',');
							var $row = $parent.next().find('.content-row');
							changeColumnWidth($row,layout);
							var $previewLayout = $('.preview-layout',$parent).addClass('layout-'+layout.join('-'));
							var previewHtml = '';
							$(layout).each(function(id,el){
								previewHtml += '<span class="layout-col layout-col-'+el+'"></span>';
							});
							$previewLayout.html(previewHtml);
						});
						collapseMenu();
					}
				});
				$spinner.hide();
			};
			function menuItemToJson(){
				var menuItems = [];
				$(conf.items,$this).each(function(){
					var $item = $(this), $fieldsList = ('.menu-item-fields',$item), itemObject = {};
					itemObject.depth = $item.menuItemDepth();
					itemObject.item_type = $item.data('itemtype');
					itemObject.content = {};
					$('.menu-item-field',$fieldsList).each(function(){
						var $fieldwrap = $(this);
						var $field = $('.menu-field',$fieldwrap);
						if($field.length > 1){
							fieldCol = $field.first().data('name');
							itemObject.content[fieldCol] = [];
							$field.each(function(id,el){
								var $f = $(this);
								itemObject.content[fieldCol].push($f.val());
							});
						}else{
							itemObject.content[$field.data('name')] = $field.val();
						}
					});
					menuItems.push(itemObject);
				});
				return menuItems;
			};
			var $previewBtn = $(conf.previewBtn), $previewForm = $(conf.previewForm), previewUrl = conf.previewUrl,
			$previewArea = $(conf.previewArea), $expandBtn = $(conf.expandBtn), $collapseBtn = $(conf.collapseBtn);
			function collapseMenu(){
				$(conf.items,$this).each(function(){
					var $item = $(this);
					if(!$item.hasClass(conf.depthClass+'0')){
						$item.hide();
					}
					var $children = $item.getChildren();
					if($children.length){
						$item.addClass(conf.hideChildrenClass);								
					}
				});
				$this.updateNoChildrenClass();
			}
			function expandMenu(){
				$(conf.items,$this).show().removeClass(conf.hideChildrenClass);
				$this.updateNoChildrenClass();
			}
			function menuActions(){
				$expandBtn.on('click',function(){
					expandMenu();
				});
				$collapseBtn.on('click',function(){
					collapseMenu();
				});
				$previewBtn.on('click',function(){
					if(previewUrl != null){
						var menuJson = cdzmenu.menuItemToJson();
						$(conf.contentField,$previewForm).val(JSON.stringify(menuJson));
						var menuType = $('#menu_type').val();
						var dialogWindow = $previewArea.modal({
							title: conf.phrase.preview,
							type: 'slide',
							buttons: [],
							opened: function () {
								$previewArea.addClass('loading');
								$.ajax({
									url: previewUrl,
									showLoader: true,
									context: $('body'),
									data: $previewForm.serialize(),
									success: function(res){
										$previewArea.removeClass('loading').addClass('loaded');
										if(menuType == 1){ //vertical
											$('.horizontal-area',$previewArea).hide();
											$('.vertical-area',$previewArea).show();
											$('.vertical-preview',$previewArea).html(res);
										}else{ // horiziontal
											$('.horizontal-area',$previewArea).show();
											$('.vertical-area',$previewArea).hide();
											$('.horizontal-preview',$previewArea).html(res);
										}
										//$previewArea.html(res);
										('.cdz-menu',$previewArea).first().trigger('contentUpdated')
										$previewArea.fadeIn(500);
									},
									error: function(XMLHttpRequest, textStatus, errorThrown){
										
									}
								}).always(function(){ });
							},
							closed: function (e, modal) {
								//$previewArea.empty();
								$('.horizontal-area, .vertical-area',$previewArea).hide();
								$('.horizontal-preview, .vertical-preview',$previewArea).html('');
								$previewArea.removeClass('loaded');
							}
						});
						dialogWindow.modal('openModal');
					}
				});
			};
			function submitForm(){
				$previewForm.on('beforeSubmit',function(){
					if( document.activeElement.id == 'duplicate' ){
						$previewForm.append('<input type="hidden" name="duplicate" value="1" />');
					}
					var menuJson = cdzmenu.menuItemToJson();
					$(conf.contentField,$previewForm).val(JSON.stringify(menuJson));
				});
			};
			function attachIconToItemHeading(textEl,value, type){
				var $textEl = $(textEl);
				if(typeof type === 'undefined'){
					var type = 0;
				}
				var $parent = $textEl.parents('.menu-item').first();
				var $previewIcon = $('.menu-item-handle .preview-icon',$parent);
				if(type == 0){
					$previewIcon.html('<i class="fa fa-'+value+'" ></i>');
					if($textEl.parent().find('.preview-icon').length){
						var $preview = $textEl.parent().find('.preview-icon');
						$('i',$preview).removeAttr('class').addClass('fa fa-'+value);
					}
				}else{
					value = filterImage(value);
					if($textEl.data('name') == 'icon_img'){
						$previewIcon.html('<a class="preview-image" onclick="cdzmenu.viewfull(this)" href="javascript:void(0)" data-href="'+value+'"><img src="'+value+'" /></a>');
					}
					if($textEl.parent().find('.preview-image').length){
						var $preview = $textEl.parent().find('.preview-image');
						$preview.data('href',value);
						$('img',$preview).attr('src',value);
					}
				}
			}
			function changeColumnWidth($row,layout){
				var totalPart = sum(layout);
				$('.content-col',$row).each(function(id,el){
					var $col = $(this);
					var width = layout[id]*100/totalPart;
					$col.css({width: width +'%'});
				});
			}
			function sum(numArray){
				var total = 0;
				$(numArray).each(function(id,el){
					total += parseInt(numArray[id]);
				});
				return total;
			}
			initSortable();
			menuItemTypeTmpl();
			attacheEventToButtons();
			loadMenu();
			menuActions();
			submitForm();
			
			$.fn.cdzAccordion = function(options){
				/*var defaultConfig = {
					header: '.content-heading .title',
					body: '.content-body',
					parent: '.content-col'	
				},
				apiConfig = $.extend({},defaultConfig,options)
				return this.each(function(){
					var $this = $(this);
					$(apiConfig.header,$this).each(function(){
						var $title = $(this);
						if(typeof $title.data('cdzAccordion') == 'undefined'){
							var $parent = $title.parents(apiConfig.parent).first(), 
							$body = $parent.find(apiConfig.body);
							$title.on('click.toggleBody',function(){
								if($parent.hasClass('active')){
									$body.slideUp();
									$parent.removeClass('active');
								}else{
									$body.slideDown();
									$parent.addClass('active');
								}
							});
							$title.data('cdzAccordion',true);	
						}
					});					
				});*/
			}
			$('.content-row').cdzAccordion();
			
			
			var $imageModal = $('<div class="img-modal"><img src="" /></div>');
			$imageModal.modal({
				title: '',
				modalClass: '_image-box',
				clickableOverlay: true,
				buttons: []
			});
			
			var cdzmenu = {
				maxColNum: 6,
				columnRow: '.content-row',
				columnCol: '.content-col',
				columnNumSpan: '.column-number',
				contentBody: '.content-body',
				editorTmpl: '#menu-item-content-type-editor-tmpl',
				menuItemField: '.menu-item-field',
				menuItemToJson: menuItemToJson,
				refresColumnNumber: function($row){
					/*var self = this;
					$(self.columnCol,$row).each(function(id,el){
						var $col = $(this);
						$(self.columnNumSpan,$col).text(id+1);
						$('[data-name="content"]',$col).data('columns', id );
					});*/
				},
				addNewColumn: function(btn){
					var self = this;
					var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
					var $row = $(self.columnRow,$fieldParent);
					if($row.find(self.columnCol).length < this.maxColNum){
						$(self.editorTmpl).tmpl({type:'editor',name:'content',loop:1}).appendTo($row);
						$row.cdzAccordion();
						//$row.find(self.columnCol).last().removeClass('active').find(self.contentBody).hide();
						this.refresColumnNumber($row);
					}else{
						this.alert(conf.phrase.maximumColumns+self.maxColNum);
					};
				},
				deleteColumn: function(btn){
					var self = this;
					var $btn = $(btn), $row = $btn.parents(self.columnRow).first();
					var $col = $btn.parents(self.columnCol).first();
					$col.find(self.contentBody).slideUp(300,function(){
						$col.remove();
						self.refresColumnNumber($row);
					});
				},
				removeIcon: function(btn){
					var self = this;
					var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
					$('[data-type="icon"]',$fieldParent).val('').trigger('change');
					$('.preview-icon i',$fieldParent).removeAttr('class');
				},
				removeImage: function(btn){
					var self = this;
					var $btn = $(btn), $fieldParent = $btn.parents(self.menuItemField);
					$('[data-type="image"]',$fieldParent).val('').trigger('change');
				},
				attachLabel: function(text){
					var self = this;
					var $text = $(text);
					var currentText = $text.val();
					if(currentText == ''){
						currentText = conf.phrase.untitled;	
					}
					var $item = $text.parents(conf.items);
					$('.menu-item-bar .link-title',$item).text(currentText);
				},
				alert: function(content){
					if(typeof conf.alert == 'function'){
						conf.alert({content: content});
					}else{
						alert(content);
					}	
				},
				confirm: function(title,content,actions){
					if(typeof conf.confirm == 'function'){
						return conf.confirm({
							title: title,
							content: content,
							actions: {
								confirm: (typeof actions.confirm !== 'undefined')?actions.confirm:function(){},
								cancel: (typeof actions.cancel !== 'undefined')?actions.cancel:function(){},
								always: (typeof actions.always !== 'undefined')?actions.cancel:function(){}
							}
						});
					}else{
						return confirm(content);	
					}
				},
				switchIconChooser: function(dropdown){
					var self = this, $dropdown = $(dropdown),
					$parent = $dropdown.parents('.menu-item-field');
					
					var $icon = $parent.next();
					var $image = $parent.next().next();
					var $iconField = $('.field-icon_font',$icon);
					var $imageField = $('.field-icon_img',$image);
					if(dropdown.value == 1){
						$icon.hide();
						$image.show();
						var src = filterImage($imageField.val());
						attachIconToItemHeading($imageField,src,1);
					}else{
						$icon.show();
						$image.hide();
						attachIconToItemHeading($iconField,$iconField.val(),0);
					}
				},
				viewfull: function(obj){
					var src = $(obj).data('href');
					$('img',$imageModal).attr('src',src);
					$imageModal.modal('openModal');
				},
				attachIconToItemHeading: attachIconToItemHeading,
				changeContentLayout: function(btn,layout){
					var self = this; $btn = $(btn), colNum = layout.length,
					$parent = $btn.parents('.menu-item-field').first(),
					$row = $parent.next().find(self.columnRow).first();
					var oldColNum = $(self.columnCol,$row).length;
					if(colNum > oldColNum){
						var addNum = colNum - oldColNum;
						$(self.editorTmpl).tmpl({type:'editor',name:'content',loop:addNum}).appendTo($row);
					}else if(colNum < oldColNum){
						var removeNum = oldColNum - colNum;
						for(var i=0; i<removeNum; i++){
							$(self.columnCol,$row).last().remove();
						}
					}
					changeColumnWidth($row,layout);
					this.toggleLayoutPanel($btn);
					$('[data-type="layout"]',$parent).val(layout);
					$('.preview-layout',$parent).html($btn.html());
					$('.preview-layout',$parent).attr('class','preview-layout layout-'+layout.join('-'))
				},
				toggleLayoutPanel: function(btn,toggleType){
					var $btn = $(btn);
					var $layout = $btn.parents('.content-layout-wrap').first();
					if(typeof toggleType === 'undefined'){
						toggleType = 0;	
					}
					if(toggleType == 0){
						$layout.toggleClass('open');
						$('.content-layout-chooser',$layout).fadeToggle('fast');
					}else if(toggleType == 1){
						$layout.addClass('open');
						$('.content-layout-chooser',$layout).fadeIn('fast');
					}else if(toggleType == 2){
						$layout.removeClass('open');
						$('.content-layout-chooser',$layout).fadeOut('fast');
					}
				}
			}
			eval('window.'+conf.windowId+' = cdzmenu');
		});
	};
})(jQuery);