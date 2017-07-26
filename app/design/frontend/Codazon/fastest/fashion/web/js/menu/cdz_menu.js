/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "matchMedia",
    "jquery/ui",
    "jquery/jquery.mobile.custom",
    "mage/translate"
], function ($, mediaCheck) {
    'use strict';

    /**
     * Menu Widget - this widget is a wrapper for the jQuery UI Menu
     */
    $.widget('cdz.cdzmenu', {
        options: {
            responsive: false,
            expanded: false,
            delay: 300
        },
        _create: function () {
            var self = this;
            this._super();
			
            $(window).on('resize', function () {
                self.element.find('.submenu-reverse').removeClass('submenu-reverse');				
            });
        },

        _init: function () {
            this._super();
            this.delay = this.options.delay;
		//this._addIcon();
            if (this.options.expanded === true) {
                this.isExpanded();
            }
			
            if (this.options.responsive === true) {
                mediaCheck({
                    media: '(max-width: 768px)',
                    entry: $.proxy(function () {
                        this._toggleMobileMode();
                    }, this),
                    exit: $.proxy(function () {
                        this._toggleDesktopMode();
                    }, this)
                });
				
            }

            this._assignControls()._listen();
            this._toggleClick();
        },
        _addIcon: function(){
            var subMenus = this.element.find('li.level-top');
            $.each(subMenus, $.proxy(function (index, item) {  
		var iconElement = "<span class='cdz-menu-icon-"+(index+1)+"'>"+"</span>";					
		if($(item).hasClass("parent"))
		{
			var toggleElemnt = "<span class='cdz-menu-toggle'>toggle</span>";
			$(item).prepend(toggleElemnt);
		}					
		$(item).prepend(iconElement);
            }));
            
        },
        _assignControls: function () {
            this.controls = {
                toggleBtn: $('[data-action="toggle-nav"]'),
                swipeArea: $('.nav-sections')
            };

            return this;
        },

        _listen: function () {
            var controls = this.controls;
            var toggle = this.toggle;
			controls.toggleBtn.unbind();
			controls.swipeArea.unbind();
            this._on(controls.toggleBtn, {'click': toggle});
            this._on(controls.swipeArea, {'swipeleft': toggle});
        },

        toggle: function () {

            if ($('html').hasClass('nav-open')) {           
                setTimeout(function () {
                   $('html').removeClass('nav-open');
                    $('html').removeClass('nav-before-open');
                }, 500);
            } else {
                $('html').addClass('nav-before-open');
                setTimeout(function () {
                    $('html').addClass('nav-open');
                }, 42);
            }
        },

        //Add class for expanded option
        isExpanded: function () {
            var subMenus = this.element.find(this.options.menus),
                expandedMenus = subMenus.find('ul');

            expandedMenus.addClass('expanded');
        },

        _activate: function (event) {
            window.location.href = this.active.find('> a').attr('href');
            this.collapseAll(event);
        },

        _keydown: function (event) {

            var match, prev, character, skip, regex,
                preventDefault = true;

            function escape(value) {
                return value.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
            }

            if (this.active.closest('ul').attr('aria-expanded') != 'true') {

                switch (event.keyCode) {
                    case $.ui.keyCode.PAGE_UP:
                        this.previousPage(event);
                        break;
                    case $.ui.keyCode.PAGE_DOWN:
                        this.nextPage(event);
                        break;
                    case $.ui.keyCode.HOME:
                        this._move("first", "first", event);
                        break;
                    case $.ui.keyCode.END:
                        this._move("last", "last", event);
                        break;
                    case $.ui.keyCode.UP:
                        this.previous(event);
                        break;
                    case $.ui.keyCode.DOWN:
                        if (this.active && !this.active.is(".ui-state-disabled")) {
                            this.expand(event);
                        }
                        break;
                    case $.ui.keyCode.LEFT:
                        this.previous(event);
                        break;
                    case $.ui.keyCode.RIGHT:
                        this.next(event);
                        break;
                    case $.ui.keyCode.ENTER:
                    case $.ui.keyCode.SPACE:
                        this._activate(event);
                        break;
                    case $.ui.keyCode.ESCAPE:
                        this.collapse(event);
                        break;
                    default:
                        preventDefault = false;
                        prev = this.previousFilter || "";
                        character = String.fromCharCode(event.keyCode);
                        skip = false;

                        clearTimeout(this.filterTimer);

                        if (character === prev) {
                            skip = true;
                        } else {
                            character = prev + character;
                        }

                        regex = new RegExp("^" + escape(character), "i");
                        match = this.activeMenu.children(".ui-menu-item").filter(function () {
                            return regex.test($(this).children("a").text());
                        });
                        match = skip && match.index(this.active.next()) !== -1 ?
                            this.active.nextAll(".ui-menu-item") :
                            match;

                        // If no matches on the current filter, reset to the last character pressed
                        // to move down the menu to the first item that starts with that character
                        if (!match.length) {
                            character = String.fromCharCode(event.keyCode);
                            regex = new RegExp("^" + escape(character), "i");
                            match = this.activeMenu.children(".ui-menu-item").filter(function () {
                                return regex.test($(this).children("a").text());
                            });
                        }

                        if (match.length) {
                            this.focus(event, match);
                            if (match.length > 1) {
                                this.previousFilter = character;
                                this.filterTimer = this._delay(function () {
                                    delete this.previousFilter;
                                }, 1000);
                            } else {
                                delete this.previousFilter;
                            }
                        } else {
                            delete this.previousFilter;
                        }
                }
            } else {
                switch (event.keyCode) {
                    case $.ui.keyCode.DOWN:
                        this.next(event);
                        break;
                    case $.ui.keyCode.UP:
                        this.previous(event);
                        break;
                    case $.ui.keyCode.RIGHT:
                        if (this.active && !this.active.is(".ui-state-disabled")) {
                            this.expand(event);
                        }
                        break;
                    case $.ui.keyCode.ENTER:
                    case $.ui.keyCode.SPACE:
                        this._activate(event);
                        break;
                    case $.ui.keyCode.LEFT:
                    case $.ui.keyCode.ESCAPE:
                        this.collapse(event);
                        break;
                    default:
                        preventDefault = false;
                        prev = this.previousFilter || "";
                        character = String.fromCharCode(event.keyCode);
                        skip = false;

                        clearTimeout(this.filterTimer);

                        if (character === prev) {
                            skip = true;
                        } else {
                            character = prev + character;
                        }

                        regex = new RegExp("^" + escape(character), "i");
                        match = this.activeMenu.children(".ui-menu-item").filter(function () {
                            return regex.test($(this).children("a").text());
                        });
                        match = skip && match.index(this.active.next()) !== -1 ?
                            this.active.nextAll(".ui-menu-item") :
                            match;

                        // If no matches on the current filter, reset to the last character pressed
                        // to move down the menu to the first item that starts with that character
                        if (!match.length) {
                            character = String.fromCharCode(event.keyCode);
                            regex = new RegExp("^" + escape(character), "i");
                            match = this.activeMenu.children(".ui-menu-item").filter(function () {
                                return regex.test($(this).children("a").text());
                            });
                        }

                        if (match.length) {
                            this.focus(event, match);
                            if (match.length > 1) {
                                this.previousFilter = character;
                                this.filterTimer = this._delay(function () {
                                    delete this.previousFilter;
                                }, 1000);
                            } else {
                                delete this.previousFilter;
                            }
                        } else {
                            delete this.previousFilter;
                        }
                }
            }

            if (preventDefault) {
                event.preventDefault();
            }
        },

        _toggleMobileMode: function () {
            $(this.element).off('mouseenter mouseleave');
			var subMenus = this.element.find('li.level-top.parent');  
			$.each(subMenus, $.proxy(function (index, item) {  
				$(item).addClass("collapse");				
			}));			
			           
        },
		
	_toggleClick: function(event){
		var subMenus = this.element.find('li.level-top.parent');  
		$("span.cdz-menu-toggle").click(function (e) {
			e.preventDefault();								
			var target = $(e.target).parent();				
			$.each(subMenus.not(target), $.proxy(function (index, item) {  					
				if($(item).hasClass("expand")){												
					$(item).removeClass("expand").addClass("collapse");											
				}
			}));			
			if (target.hasClass('parent') && target.children(".groupmenu-drop").length) {
				var dropdownMenu = target.children(".groupmenu-drop");					
				if(target.hasClass("expand")){						
					target.removeClass("expand").addClass("collapse");						
					//dropdownMenu.slideUp("fast");						
				}
				else if(target.hasClass("collapse")){						
					target.removeClass("collapse").addClass("expand");											
				}
			}
		});
		},

        _toggleDesktopMode: function () {			
            this._on({
                // Prevent focus from sticking to links inside menu after clicking
                // them (focus should always stay on UL during navigation).
                "mousedown li.level-top > a": function (event) {
                    event.preventDefault();
                },
                /*"click .ui-state-disabled > a": function (event) {
                    event.preventDefault();
                },*/
                
            });

                  
        },
        _delay: function(handler, delay) {
            var instance = this,
                handlerProxy = function () {
                return (typeof handler === "string" ? instance[handler] : handler)
                    .apply(instance, arguments);
            };
            
            return setTimeout(handlerProxy, delay || 0);
        }
    });

    return $.cdz.cdzmenu;
});

