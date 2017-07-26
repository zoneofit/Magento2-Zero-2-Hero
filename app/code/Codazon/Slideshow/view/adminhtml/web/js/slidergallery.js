/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint jquery:true*/
define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'baseImage'
], function ($, mageTemplate) {
    'use strict';

    /**
     * Product gallery widget
     */
    $.widget('mage.sliderGallery', {
        options: {
            imageSelector: '[data-role=image]',
            template: '[data-template=image]',            
            initialized: false
        },

        /**
         * Gallery creation
         * @protected
         */
        _create: function () {
            
            this.options.images = this.options.images || this.element.data('images');

            this.imgTmpl = mageTemplate(this.element.find(this.options.template).html());

            this._bind();

            $.each(this.options.images, $.proxy(function (index, imageData) {
                this.element.trigger('addItem', imageData);
            }, this));

            this.options.initialized = true;
        },

        /**
         * Bind handler to elements
         * @protected
         */
        _bind: function () {
            var events = {
                openDialog: '_onOpenDialog',
                addItem: '_addItem',
                removeItem: '_removeItem',                
                setPosition: '_setPosition',
                resort: '_resort',
                'mouseup [data-role=delete-button]': function (event) {
                    var $imageContainer;

                    event.preventDefault();
                    $imageContainer = $(event.currentTarget).closest(this.options.imageSelector);
                    this.element.find('[data-role=dialog]').trigger('close');
                    this.element.trigger('removeItem', $imageContainer.data('imageData'));
                }
            };

            this._on(events);
            this.element.sortable({
                distance: 8,
                items: this.options.imageSelector,
                tolerance: "pointer",
                cancel: 'input, button, .uploader',
                update: $.proxy(function () {
                    this.element.trigger('resort');
                }, this)
            });
        },

        

        /**
         * Find element by fileName
         * @param {Object} data
         * @returns {Element}
         */
        findElement: function (data) {
            return this.element.find(this.options.imageSelector).filter(function () {
                return $(this).data('imageData').file === data.file;
            }).first();
        },

        /**
         * Add image
         * @param event
         * @param imageData
         * @private
         */
        _addItem: function (event, imageData) {
            var count = this.element.find(this.options.imageSelector).length,
                element;

            imageData = $.extend({
                file_id: Math.random().toString(33).substr(2, 18),
                disabled: imageData.disabled ? imageData.disabled : 0,
                position: count + 1
            }, imageData);

            element = this.imgTmpl({
                data: imageData
            });

            element = $(element).data('imageData', imageData);
            if (count === 0) {
                element.prependTo(this.element);
            } else {
                element.insertAfter(this.element.find(this.options.imageSelector + ':last'));
            }

           
        },

        /**
         * Remove Image
         * @param {jQuery.Event} event
         * @param imageData
         * @private
         */
        _removeItem: function (event, imageData) {
            var $imageContainer = this.findElement(imageData);
            imageData.isRemoved = true;
            $imageContainer.addClass('removed').hide().find('.is-removed').val(1);
        },

        

        /**
         * Resort images
         * @private
         */
        _resort: function () {
            this.element.find('.position').each($.proxy(function (index, element) {
                var value = $(element).val();

                if (value != index) {
                    this.element.trigger('moveElement', {
                        imageData: $(element).closest(this.options.imageSelector).data('imageData'),
                        position: index
                    });
                    $(element).val(index);
                }
            }, this));
        },

        /**
         * Set image position
         * @param event
         * @param data
         * @private
         */
        _setPosition: function (event, data) {
            var $element = this.findElement(data.imageData);
            var curIndex = this.element.find(this.options.imageSelector).index($element);
            var newPosition = data.position + (curIndex > data.position ? -1 : 0);

            if (data.position != curIndex) {
                if (data.position === 0) {
                    this.element.prepend($element);
                } else {
                    $element.insertAfter(
                        this.element.find(this.options.imageSelector).eq(newPosition)
                    );
                }
                this.element.trigger('resort');
            }
        }
    });

    // Extension for mage.productGallery - Add advanced settings block
    $.widget('mage.sliderGallery', $.mage.sliderGallery, {
        options: {
            dialogTemplate: '.dialog-template'
        },

        _create: function () {
            this._super();
            var template = this.element.find(this.options.dialogTemplate);
            if (template.length) {
                this.dialogTmpl = mageTemplate(template.html());
            }
        },

        /**
         * Bind handler to elements
         * @protected
         */
        _bind: function () {
            this._super();
            var events = {};
            events['click [data-role=close-panel]'] = $.proxy(function () {
                this.element.find('[data-role=dialog]').trigger('close');
            }, this);
            events['mouseup ' + this.options.imageSelector] = function (event) {
                if (!$(event.currentTarget).is('.ui-sortable-helper')) {
                    $(event.currentTarget).addClass('active');
                    var itemId = $(event.currentTarget).find('input')[0].name.match(/\[([^\]]*)\]/g)[2];
                    $('#item_id').val(itemId);
                    var imageData = $(event.currentTarget).data('imageData');
                    var $imageContainer = this.findElement(imageData);
                    if ($imageContainer.is('.removed')) {
                        return;
                    }
                    this.element.trigger('openDialog', [imageData]);
                }
            };
            this._on(events);
            this.element.on('sortstart', $.proxy(function () {
                this.element.find('[data-role=dialog]').trigger('close');
            }, this));
        },

        /**
         *
         * Click by image handler
         *
         * @param e
         * @param imageData
         * @private
         */
        _onOpenDialog: function(e, imageData) {
            if (imageData.media_type && imageData.media_type != 'image') {
                return;
            }
            this._showDialog(imageData);
        },

        /**
         * Show dialog
         * @param imageData
         * @private
         */
        _showDialog: function (imageData) {
            var $imageContainer = this.findElement(imageData);
            var dialogElement = $imageContainer.data('dialog');
            if (!this.dialogTmpl) {
                alert('System problem!');
                return;
            }

            var $template = this.dialogTmpl({ data: imageData });
            dialogElement = $($template);
            dialogElement.modal({
                'type': 'slide',
                title: $.mage.__('Image Detail'),
                buttons: [],
                opened: function() {
                    dialogElement.trigger('open');
                },
                closed: function(e) {
                    dialogElement.trigger('close');
                }
            });
            dialogElement
                .data('imageContainer', $imageContainer)
                .on('open', $.proxy(function (event) {
                    $(event.target)
                        .find('[data-role=type-selector]')
                        .each($.proxy(function (index, select) {
                            var $select = $(select);
                            $select.val(imageData.transition);                                                                             
                            }, this));

                }, this))
                .on('close', $.proxy(function (event) {
                    $imageContainer.removeClass('active');
                    $imageContainer.data('dialog', null);
                }, this));

            $imageContainer.data('dialog', dialogElement);


            var _changeDescription = function(e) {
                var target = jQuery(e.target);
                var targetName = target.attr('name');
                var desc = target.val();
                jQuery('input[type="hidden"][name="'+ targetName + '"]').val(desc);
                imageData.caption = desc;
                imageData.label_default = desc;
            };
            
            var _changeLink = function(e) {
                var target = jQuery(e.target);
                var targetName = target.attr('name');
                var desc = target.val();                
                jQuery('input[type="hidden"][name="'+ targetName + '"]').val(desc);
                imageData.link = desc;
                imageData.link_default = desc;
            };

            dialogElement.on('change', '[data-role=type-selector]', function (e) {
				var target = jQuery(e.target);
				var transition = this.value;                                
				var targetName = target.attr('name');
				jQuery('input[type="hidden"][name="'+ targetName + '"]').val(transition);
				imageData.transition = transition;
                imageData.transition_default = transition;
            });
            dialogElement.on('change', '[data-role=type-selector]', $.proxy(this._notifyType, this));
            dialogElement.on('change', '[data-role=visibility-trigger]', $.proxy(function(e) {
                this._changeVisibility(e, imageData);
            }, this));
            dialogElement.on('change', '#image-caption', _changeDescription);
            dialogElement.on('change', '#image-link', _changeLink);
            dialogElement.modal('openModal');
        },

        /**
         * Change visibility
         *
         * @param event
         * @private
         */
        _changeVisibility: function (event, imageData) {
            var $checkbox = $(event.currentTarget);
            var $imageContainer = $checkbox.closest('[data-role=dialog]').data('imageContainer');
            $imageContainer.toggleClass('hidden-for-front', $checkbox.is(':checked'));
            var checked = $checkbox.is(':checked') ? 1 : 0;
            $imageContainer.find('[name*="disabled"]').val(checked);
            imageData.disabled = checked;
        },

        /**
         * Set image
         * @param event
         * @private
         */
        _notifyType: function (event) {
            var $checkbox = $(event.currentTarget);
            var $imageContainer = $checkbox.closest('[data-role=dialog]').data('imageContainer');            
        }
    });

    return $.mage.sliderGallery;
});
