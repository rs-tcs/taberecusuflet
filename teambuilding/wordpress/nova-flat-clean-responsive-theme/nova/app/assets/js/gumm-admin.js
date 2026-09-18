jQuery(function($) {
$(document).ready(function(){
    $('.gumm-page-builder-editor').gummPageBuilder();
    
    $('#gumm-theme-options-wrap').bind('click', function(e){
        if ($(this).hasClass('state-progress')) {
            e.preventDefault();
            e.stopPropagation();
        }
    });

    // GENERIC AJAX CALSS:
    $('.gumm-ajax-delete').live('click', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(data, textStatus, XMLHttpRequest) {
                try {
                    if (data.status === undefined) {
                        return;
                    } else if (data.status == 'ko') {
                        gummbase.alert(data.msg, {type: 'error'});
                    }
                } catch(err){
                    console.log(err)
                }
            }
        });
    });
    
    $('form.gumm-ajax-save').live('submit', function(e){
        var theForm = $(this);
        e.preventDefault();
        $.ajax({
            url: theForm.attr('action'),
            data: theForm.serialize(),
            type: theForm.attr('method'),
            // dataType: 'json',
            success: function(data, textStatus, XMLHttpRequest) {
                console.log(data);
                try {
                    if (data.status === undefined) {
                        return;
                    } else if (data.status == 'ko') {
                        gummbase.alert(data.msg, {type: 'error'});
                    }
                } catch(err){
                    console.log(err)
                }
            }
        });
    });
    
    // ADMIN MENU
    $(document).on('click', 'ul#admin-menu a', function(e){
        e.preventDefault();
        var theTab = $(this).parent('li');
        var theTabId = theTab.data('tab-id');
        
        if (theTab.hasClass('current-item')) return false;
        
        theTab.addClass('current-item').siblings('.current-item').removeClass('current-item');
        
        var theTabContainer = $($(this).attr('href'));
        theTabContainer
            .addClass('current')
            .removeClass('hidden')
            .trigger('gummVisible')
            .siblings('.current').removeClass('current').addClass('hidden');
        
        // Call layouts controller via ajax to store user navigation
        $.ajax({
            url: ajaxurl,
            data: {
                gummcontroller: 'layouts',
                action: 'admin_store_user_navigation',
                gummnamed: {
                    tabId: theTabId
                }
            }
        });
    });
    
    // GUMM TOOLBARS
    $(document).on('click', '.inner-toolbar-menu a', function(e){
        e.preventDefault();
        var theToolbarTab = $(this).parent('li');
        if (theToolbarTab.hasClass('current-item')) return false;
        
        theToolbarTab.addClass('current-item').siblings('.current-item').removeClass('current-item');
        var theGroupId = theToolbarTab.data('option-group-id');
        
        var theGroupOptionsContainer = theToolbarTab.parent('ul').parent('div.inner-toolbar-menu').next('.gumm-options-groups-container');

        theGroupOptionsContainer.children('.current').removeClass('current').addClass('hidden');
        theGroupOptionsContainer.children('.gumm-option-group-' + theGroupId).addClass('current').removeClass('hidden').trigger('gummVisible');
        
        // Call layouts controller via ajax to store user navigation
        $.ajax({
            url: ajaxurl,
            data: {
                gummcontroller: 'layouts',
                action: 'admin_store_user_navigation',
                gummnamed: {
                    toolbarTabId: theGroupId
                }
            }
        });
        
    });
    
    // DESCRIPTION POPUPS
    $(document).on('click', '.description-popup-trigger', function(e){
        e.preventDefault();
        e.stopPropagation();
        var theInputId = $(this).attr('id').replace(/-desc-trigger/, '');
        var theInput = $('#' + theInputId);
        var theDescriptionContainer = $('#' + theInputId + '-desc-container');
        var theDivContainer = $(this).parents('.gumm-admin-option:first');
        
        if (theDescriptionContainer.is(':visible')) {
            theDescriptionContainer.animate({
                opacity: 0
            }, 150, function() {
                $(this).hide();
            });
            return;
        }
        
        var top = theDivContainer.position().top - (theDescriptionContainer.outerHeight() + 10);
        theDescriptionContainer.css({
            position: 'absolute',
            top: top,
            right: 52 - (theDescriptionContainer.width()/2),
            display: 'block',
            opacity: 0
        });
        
        var divContainerOffsetTop = $('#gumm-theme-options-wrap').offset().top;
        var descriptionContainerOffsetTop = theDescriptionContainer.offset().top;
        
        if (descriptionContainerOffsetTop < divContainerOffsetTop) {
            theDescriptionContainer.css({
                top: $(this).outerHeight() + 22
            });
            var theArrow = theDescriptionContainer.children('div').eq(0);
            theArrow.css({
                top: -(theDescriptionContainer.innerHeight() - 8),
                backgroundPosition: '0 0'
            });
        }
        
        
        theDescriptionContainer.animate({
            opacity: 1
        }, 150, function() {
            $(this).show();
        });
    });
    
    // GUMM FORMS: Radio Buttons
    $(document).on('click', '.admin-radio', function(e){
        e.preventDefault();
    });
    $(document).on('click', '.gumm-radio-input', function(e){
        e.preventDefault();
        
        var theCurrentInput = $(this).parents('.gumm-radio-fields').first().find('.gumm-radio-input.current');
        var theCurrentInputField = theCurrentInput.find('input');
        
        theCurrentInput.removeClass('current');
        if (theCurrentInputField.attr('type') === 'radio') {
            theCurrentInputField.attr('checked', false);
            $(this).addClass('current').find('input').attr('checked', true).attr('disabled', false).trigger('change');
        } else {
            theCurrentInputField.attr('disabled', 'disabled');
            $(this).addClass('current').find('input').attr('disabled', false).trigger('change');
        }
    });
    // $('.gumm-radio').live('click', function(e) {
    //     e.preventDefault();
    //     var theRadioButtons = $(this).parents('.gumm-radio-fieldset:first').find('.gumm-radio');
    //     theRadioButtons.removeClass('selected-radio');
    //     theRadioButtons.parents('.gumm-admin-option').removeClass('current-font current-option');
    //     theRadioButtons.parents('.gumm-radio-container').removeClass('selected-radio-container');
    //     
    //     $(this).addClass('selected-radio');
    //     $(this).parents('.gumm-admin-option:first').addClass('current-font current-option');
    //     $(this).parents('.gumm-radio-container:first').addClass('selected-radio-container');
    //     $(this).parents('.gumm-radio-fieldset:first').find('.gumm-radio-value').val($(this).attr('title'));
    // });
    
    // GUMM FORMS: Text & Hidden Inputs
    $('input.admin-font-input').live('change', function(e) {
        $(this).prev('h3').css({
           fontFamily: $(this).val() 
        });
    });
    
    // GUMM FORMS: Submission
    $(document).on('click', '.buttons-container .save', function(e) {
        e.preventDefault();
        // $('#gumm-theme-options-wrap').addClass('state-progress');
        
        var theForm = $(this).parents('form:first');
        
        var theData = false;
        if (!$(this).hasClass('save-all')) {
            var theOptionPanel = $(this).parents('.admin-options-group:first');
            
            theData = theOptionPanel.find('input, select, textarea').serialize();
            
            // console.log(theData);
            if (!theData) {
                gummbase.alert('Error: Nothing to save.', {type: 'error'});
                return;
            }
            
            if (theOptionPanel.data('depends-on') !== undefined) {
                var theDependsOnData = $("#" + theOptionPanel.data('depends-on')).find('input, select, textarea').serialize();
                if (theDependsOnData) theData += '&' + theDependsOnData;
            }
            if (theOptionPanel.data('dependant') !== undefined) {
                var theDependantData = $("#" + theOptionPanel.data('dependant')).find('input, select, textarea').serialize();
                if (theDependantData) theData += '&' + theDependantData;
            }
            
            var theSecurityFields = theForm.children('.gumm-security-fields').children('input').serialize();
            theData += '&' + theSecurityFields;
        } else {
            theData = theForm.serialize() + '&_mergeonsave=0';
        }
        $.ajax({
            url: ajaxurl,
            type: theForm.attr('method'),
            data: theData,
            dataType: 'json',
            success: function(data, textStatus, XMLHttpRequest) {
                gummbase.alert(data.msg, {type: 'success', flash: 2000});
            }
        });
        
    });
    
    // GUMM FORMS INPUTS
    $(document).on('click', '.gumm-clear-multiple-select', function(e){
        e.preventDefault();
        $(e.target).prev('select').children('option').prop('selected', false);
    });
    
    // MEDIA
    $(document).on('click', '.gumm-media-manager .embed-video-button', function(e){
        e.preventDefault();
        var popup = new $.gummPopup({
            url: $(this).attr('href'),
            width: 425,
            height: 490,
            buttonOkEnabled: false,
            onContentReady: function(contentEle) {
                var _self = this;
                var theInputArea = contentEle.find('#EmbedVideoCode');
                var theValue = theInputArea.val();
                var requestTimeOut = null;
                
                var thePreviewDataDiv = contentEle.find('.embed-video-data');
                theInputArea.on('keyup', function(e){
                    try{clearTimeout(requestTimeOut)}catch(err){}
                    if (theValue == $(this).val() || $(this).val().length < 1) return false;
                    
                    requestTimeOut = setTimeout(function(){
                        console.log('asd');
                        theValue = theInputArea.val();
                        
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                gummcontroller: 'media',
                                action: 'admin_embed_video_info',
                                embedCode: theValue
                            },
                            success: function(data, textStatus, XMLHttpRequest) {
                                thePreviewDataDiv.html(data);
                                _self.enableOk();
                            }
                        });
                    }, 500);
                });
                
            },
            onConfirm: function(gummui) {
                var theForm = $(this).find('#gumm-embed-video-form');
                var theMediaManagerContainer = gummui.caller.parents('.gumm-media-manager:first').find('.media-uploads-container:first');
                console.log(theForm);
                if (theForm.size() > 0) {
                    $.ajax({
                        url: theForm.attr('action'),
                        type: theForm.attr('method'),
                        data: theForm.serialize(),
                        success: function(data, textStatus, XMLHttpRequest) {
                            try{
                                var theContent = $(data);
                                theContent.css({opacity: 0});
                                theMediaManagerContainer.prepend(theContent);
                                theContent.animate({opacity: 1}, 600);
                            } catch(err){}
                        }
                    });
                }
            }
        }, null, $(this));
    });

    $('.media-edit-button').live('click', function(e){
        e.preventDefault();
        var thePopup = new $.gummPopup({
            width: 640,
            height: 680,
            url: $(this).attr('href'),
            onConfirm: function(gummui) {
                var theForm = gummui.content.find('form:first');
                theForm.submit();
            }
        }, null, $(this));
    });
    
    // $('.media-uploads-container .media-upload-item').hover(
    //     function(){$(this).children().children('.close-button').stop(true, true).show('fade', 100);},
    //     function(){$(this).children().children('.close-button').stop(true, true).hide('fade', 100);}
    // );
    
    // FONTS
    $(document).on('click', '.gumm-browse-fonts', function(e){
        e.preventDefault();
        
        var popup = new $.gummPopup({
            url: $(this).attr('href'),
            width: 700,
            height: 538,
            searchBar: true,
            onConfirm: function(gummui) {
                // console.log(gummui);
                var val = gummui.content.find('input.font-radio-input:not(:disabled)').val();
                if (val.length > 0) {
                    gummui.caller.parent().next().find('.font-family-preview').html(val).css({
                        fontFamily: val
                    });
                    gummui.caller.parent().children('input').val(val);
                    
                }
            }
        }, null, $(this));
    });
    
    // ICONS
    $(document).on('click', '.icons-manager-browse', function(e){
        e.preventDefault();
        
        var popup = new $.gummPopup({
            url: ajaxurl,
            urlData: {
                gummcontroller: 'layout_elements',
                action: 'gumm_admin_index_icons',
                gummnamed: {}
            },
            searchBar: true,
            width: 700,
            height: 538,
            beforeOpen: function(content){
                this.options.urlData.gummnamed.icon = this.callerElement.prev().val();
            },
            onOpen: function(contentElement) {
                var val = contentElement.find('input.icon-value').val();
                if (val) {
                    contentElement.animate({
                        scrollTop: contentElement.find('li.selected').position().top - 200
                    }, 400);
                }
                contentElement.gummIconsPicker();
            },
            onConfirm: function(gummui) {
                var val = gummui.content.gummIconsPicker('val');
                gummui.caller.siblings('.icons-manager-preview-icon').attr('class', 'icons-manager-preview-icon ' + val);
                gummui.caller.siblings('input.icon-manager-value').val(val);
            }
        }, null, $(this));
    });
    $(document).on('click', '.icons-manager-remove-icon', function(e){
        e.preventDefault();
        $(this).siblings('.icon-manager-value').val('');
        $(this).siblings('.icons-manager-preview-icon').attr('class', 'icons-manager-preview-icon');
    });
    
    // POST METABOXES
    $('#gumm-metabox-post-format').find('.post-format-checker').bind('click', function(e){
        e.preventDefault();
        var theMessageContainer = $(this).parents('#gumm-metabox-post-format').find('.post-format-message');
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(data, textStatus, XMLHttpRequest) {
                if (data.ok !== true) {
                    theMessageContainer.html(data.msg);
                    theMessageContainer.show();
                } else {
                    theMessageContainer.html('');
                    theMessageContainer.hide();
                }
            }
        });
    });
    
    initGummSliderInputs();
    
    // Track for hide/unhide metabox
    $('#gummbase_page_builder-hide').on('click', function(e){
        if ($(this).is(':checked')) {
            $('#gummbase_page_builder').trigger('gummVisible');
        }
    });
    
    // ############## //
    // PAGE METABOXES //
    // ############## //
    
    // Show/Hide page builder metaboxes depending on page template selected
    // var pageTemplateSelect = $('#page_template');
    // var pageBuilderMetaboxes = $('#gummbase_page_builder');
    // var dynamicTemplateVal = ['dynamic_template.php', 'template_home.php', 'template_contact.php'];
    // if (pageTemplateSelect.size() > 0 && pageBuilderMetaboxes.size() > 0) {
    //     if ($.inArray(pageTemplateSelect.val(), dynamicTemplateVal) >= 0) {
    //         pageBuilderMetaboxes.show();
    //     }
    //     pageTemplateSelect.bind('change', function(e){
    //         if ($.inArray($(this).val(), dynamicTemplateVal) >= 0) {
    //             pageBuilderMetaboxes.show('fade', 150);
    //         } else {
    //             pageBuilderMetaboxes.hide('fade', 150);
    //         }
    //     });
    // }
    // 
    // $('#gumm-meta-page-slider-advanced-inputs-trigger').bind('click', function(e){
    //     e.preventDefault();
    //     $('#gumm-meta-page-slider-advanced-inputs').slideToggle(150);
    // });
 
    // LAYOUT SIDEBARS EDITOR
    // var lmDraggableSidebarSettings = {
    //     revert: true,
    //     revertDuration: 0,
    //     zIndex: 100,
    //     cancel: '.close-button',
    //     distance: 0,
    //     helper: 'clone',
    //     // connectToSortable: '.lm-sidebar',
    //     start: function(event, ui) {
    //         $(this).css({opacity: 0.3});
    //         ui.helper.find('a').remove();
    //     },
    //     stop: function(event, ui) {
    //         $(this).removeAttr('style');
    //     }
    // };
    // $('.sidebar-manager-container > .lm-draggable-sidebar').draggable(lmDraggableSidebarSettings);
    // 
    // $('.lm-sidebar').droppable({
    //     accept: '.lm-draggable-sidebar',
    //     tolerance: 'pointer',
    //     hoverClass: 'hover',
    //     activeClass: 'active-sidebar',
    //     drop: function(event, ui) {
    //         var theDiv = $(this).children('div.sidebar-block-demo:first');
    //         
    //         if (theDiv.attr('title') == ui.draggable.attr('title')) return;
    //         
    //         $(this).children('div.draggable-placeholder:first').remove();
    //         
    //         var theData = theDiv.data('clone');
    //         if (theData !== undefined && theData !== false) {
    //             theData.ele.hide('explode', {pieces: 20}, 200, function(){$(this).remove()});
    //             theDiv.data('clone', false);
    //         }
    //         
    //         theDiv.html(ui.draggable.text());
    //         theDiv.attr('title', ui.draggable.attr('title'));
    //         
    //         theDiv.stop(true, true).css({
    //             // marginTop:0,
    //             opacity: 1,
    //             display: 'block'
    //         });
    //         
    //         $(this).children('input:first').val(ui.draggable.attr('title'));
    //     },
    //     over: function(event, ui) {
    //         var theDiv = $(this).children('div.sidebar-block-demo:first');
    //         
    //         if (theDiv.attr('title') == ui.draggable.attr('title')) return;
    //         
    //         var theManager = $(this).parents('.layout-sidebar-manager-wrap:first');
    //         var theCorrespondent = theManager.children('.lm-registered-sidebars').find('.lm-draggable-sidebar[title="' + theDiv.attr('title') + '"]');
    //         
    //         var theData = theDiv.data('clone');
    //         
    //         if (theData !== undefined && theData !== false) {
    //             var theClone = theData.ele;
    //         } else {
    //             var theClone = theDiv.clone();
    //             
    //             theData = {
    //                 ele: theClone,
    //                 offset: theDiv.offset(),
    //                 width: theCorrespondent.width(),
    //                 height: theCorrespondent.height(),
    //                 placeHolderWidth: theDiv.width(),
    //                 placeHolderHeight: theDiv.height()
    //             };
    //             
    //             $(theDiv).data('clone', theData);
    // 
    //             theClone.addClass('sidebar-block-demo-helper')
    //             $('body').append(theClone);
    // 
    //             theClone.css({
    //                 position: 'absolute',
    //                 top: theDiv.offset().top,
    //                 left: theDiv.offset().left,
    //                 width: theData.placeHolderWidth,
    //                 height: theData.placeHolderHeight,
    //                 opacity: 1
    //             });
    //         }
    //         
    //         theClone.stop().animate({
    //             left: theCorrespondent.offset().left,
    //             top: theCorrespondent.offset().top,
    //             width: theData.width,
    //             height: theData.height
    //         }, 500, function() {
    //             $(this).css({opacity: 0});
    //         });
    //         
    //         theDiv.stop().hide();
    //         
    //         
    //         var thePlaceHolder = $('<div class="draggable-placeholder"></div>');
    //         
    //         $(this).prepend(thePlaceHolder);
    // 
    //         thePlaceHolder.css({
    //             width: $(this).width(),
    //             height: theData.placeHolderHeight - 10,
    //             paddingTop: ui.helper.css('padding-top'),
    //             paddingBottom: ui.helper.css('padding-bottom'),
    //             border: '2px dashed #b1b1b1',
    //             marginLeft: -2,
    //             display: 'none',
    //             position: 'absolute'
    //         });
    //         thePlaceHolder.show('fade', 100);
    //     },
    //     out: function(event, ui) {
    //         var theDiv = $(this).children('.sidebar-block-demo:first');
    //         
    //         if (theDiv.attr('title') == ui.draggable.attr('title')) return;
    //         
    //         $(this).children('.draggable-placeholder').remove();
    //         
    //         var theData = $(theDiv).data('clone');
    //         
    //         
    //         var theClone = theData.ele;
    //         
    //         theClone.stop().css({opacity: 1}).animate({
    //             left: theData.offset.left,
    //             top: theData.offset.top,
    //             width: theData.placeHolderWidth,
    //             height: theData.placeHolderHeight
    //         }, 500, function() {
    //             theDiv.stop().show();
    //             theDiv.data('clone', false);
    //             $(this).remove();
    //         });
    //         
    //     }
    // });
    
    // $('.remove-custom-sidebar').live('click', function(e){
    //     e.preventDefault();
    //     
    //     var theSidebarId = $(this).parent().attr('title');
    //     $.ajax({
    //         url: $(this).attr('href'),
    //         dataType: 'json',
    //         success: function(data, textStatus, XMLHttpRequest) {
    //             // console.log(data);
    //             // return;
    //             if (data !== true) {
    //                 gummbase.alert('Error removing sidebar.', {type: 'error'});
    //                 return false;
    //             }
    //             var theItems = $(".lm-draggable-sidebar[title='" + theSidebarId + "']");
    // 
    //             theItems.each(function(i, ele){
    //                 var theSidebar = $(ele);
    //                 var theManager = theSidebar.parents('.layout-sidebar-manager-wrap:first');
    //                 var theIncludedSidebar = theManager.find('.layout-model').find('.sidebar-block-demo[title="' + theSidebarId + '"]');
    //                 if (theIncludedSidebar.length > 0) {
    //                     var theDefaultSidebar = theManager.find('.lm-draggable-sidebar').not(theSidebar).first();
    // 
    //                     theIncludedSidebar.hide('fade', 200, function(){
    //                         theIncludedSidebar.text(theDefaultSidebar.text());
    //                         theIncludedSidebar.attr('title', theDefaultSidebar.attr('title'));
    //                         theIncludedSidebar.parent().children('input:first').val(theDefaultSidebar.attr('title'));
    //                         theIncludedSidebar.show('fade', 500);
    //                     });
    //                 }
    // 
    //             });
    // 
    //             theItems.hide('drop', {direction: 'down'}, 200, function(){
    //                 $(this).remove();
    //             });
    //         }
    //     }); // Ajax call end
    //     
    // });
    
    // $('.gumm-add-new-sidebar').live('click', function(e){
    //     e.preventDefault();
    //     
    //     var popup = new $.gummPopup({
    //         url: $(this).attr('href'),
    //         width: 400,
    //         height: 300,
    //         onConfirm: function(gummui) {
    //             var theForm = $(this).find('#gumm-sidebars-add-form');
    //             if (theForm.size() > 0) {
    //                 $.ajax({
    //                     url: theForm.attr('action'),
    //                     type: theForm.attr('method'),
    //                     data: theForm.serialize(),
    //                     success: function(data, textStatus, XMLHttpRequest) {
    //                         try{
    //                             $('.layout-sidebar-manager-wrap .lm-registered-sidebars').prepend(data);
    //                             $('.sidebar-manager-container > .lm-draggable-sidebar').draggable(lmDraggableSidebarSettings);
    //                         } catch(err){
    //                             console.log(err);
    //                         }
    //                     }
    //                 });
    //             }
    //         }
    //     });
    // });
    
    // LAYOUT LAYERS EDITOR
    $(document).on('click', '.add-new-layer-background', function(e){
        e.preventDefault();
        
        var theLayerToCloneFrom = $(this).siblings('.layout-layer-editor:last');
        var theClone = theLayerToCloneFrom.clone();
        
        var theCloneId = parseInt(theLayerToCloneFrom.data('layer-id')) + 1;
        
        theClone.data('layer-id', theCloneId);
        theClone.css({opacity: 0});
        theLayerToCloneFrom.after(theClone);
        
        
        var url = $(this).attr('href') + '&gummnamed[layerId]=' + theCloneId;
        
        theClone.load(url, function(){
            theClone.animate({opacity: 1}, 150);
        });
        
    });
    
    $('.layout-layer-editor .close-button').live('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        var theEditor = $(this).parents('.layout-layer-editor:first');
        var theBackgroundEditor = theEditor.parent('.block-background-images-editor');
        if (theEditor.siblings('.layout-layer-editor:visible').size() > 0) {
            theEditor.hide('fade', 150, function(){
                var theFirstVisible = theBackgroundEditor.children('.layout-layer-editor:visible').first();
                if (!theFirstVisible.is(theEditor)) {
                    theFirstVisible.before(theEditor);
                    var theItems = theBackgroundEditor.find('.layout-layer-editor');
                    var num = theItems.size();
                    theItems.each(function(i, item){
                        item = $(item);
                        var theNumContainer = item.find('.layer-editor-layer-num');
                        var theNewLayerNum = num - item.index() + 1;
                        theNumContainer.text(theNewLayerNum);
                    });
                }
            });
        } else {
            gummbase.alert('Background cleared. Click save to preserve changes.', {type: 'success'});
        }
        
        theEditor.find('input, select, textarea').val('');
        theEditor.find('.background-image-preview > div').css({backgroundImage: ''}).next('.layout-editor-pattern-weight-slider').html('');
    });
    
    $('.block-background-images-editor').sortable({
        items: '.layout-layer-editor',
        // placeholder: 'layout-layer-editor-placeholder',
        update: function(event, ui) {
            var theItems = $(this).find('.layout-layer-editor');
            var num = theItems.size();
            theItems.each(function(i, item){
                item = $(item);
                var theNumContainer = item.find('.layer-editor-layer-num');
                var theNewLayerNum = num - item.index() + 1;
                theNumContainer.text(theNewLayerNum);
            });
        }
    });
    
    $(document).on('click', '.admin-clear-color', function(e){
        e.preventDefault();
        $(this).next('.color-palette').children('div').css({
            backgroundColor: 'inherit'
        });
        $(this).parent().children('input').val('');
        

    });
    
    $('.layout-layer-editor .browse-background').live('click', function(e){
        e.preventDefault();
        
        var popup = new $.gummPopup({
            url: $(this).attr('href'),
            width: 640,
            height: 680,
            resizable: false,
            addClass: 'quick-launch-popup',
            onOpen: function(contentElement) {
                contentElement.find('.quickLaunch').gummQuickLaunch();
            },
            onConfirm: function(gummui) {
                var theQuickLaunch = $(gummui.content).find('.quickLaunch');
                if (theQuickLaunch.size() > 0) {
                    var activeItemContent = theQuickLaunch.gummQuickLaunch('getActiveItemContent');
                    if (!activeItemContent) return;

                    var theUrl = activeItemContent.find('input.block-background-pattern-url').val();

                    var theEditor = gummui.caller.parents('.layout-layer-editor:first');

                    theEditor.find('.layout-editor-pattern-weight-slider').load(
                        ajaxurl,
                        {
                            gummcontroller: 'layouts',
                            action: 'gumm_admin_edit_background_pattern_weight',
                            gummnamed: {
                                patternUrl: theUrl
                            }
                        },
                        function() {}
                    );

                    theEditor.find('.single-upload-value').val(theUrl).trigger('change');
                }
            }
        }, null, $(this));
    });
    
    $(document).on('change', '.layout-layer-editor .single-upload-value', function(e){
        var theEditor = $(this).parents('.layout-layer-editor').first();
        var thePreview = theEditor.find('.background-image-preview').first().children().eq(0);
        thePreview.css({
            backgroundImage: "url('" + $(this).val() + "')"
        });
    });
    
    $(document).on('change', '.layout-layer-editor .background-image-preview input.gumm-slider-input', function(e){
        $(this).parents('.layout-layer-editor:first').find('.single-upload-value').val($(this).attr('title')).trigger('change');
    });
    
    $(document).on('mouseenter', '.layout-layer-editor .image-preview-container', function(e){
        $(this).find('.gumm-slider-input-wrapper').stop().animate({
            bottom: 10
        }, 150);
    });
    $(document).on('mouseleave', '.layout-layer-editor .image-preview-container', function(e){
        $(this).find('.gumm-slider-input-wrapper').stop().animate({
            bottom: -45
        }, 150);
    });
    
    // SKIN OPTIONS
    $(document).on('submit', '#gumm-skin-edit-form', function(e, gummui){
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(data, textStatus, XMLHttpRequest) {
                $('#bluebox-admin').load(ajaxurl, {gummcontroller: 'options', action: 'index'});
            }
        });
    });
    $(document).on('click', '.activate-skin', function(e){
        e.preventDefault();
        var $this = $(this);
        $this.removeClass('icon-eye-close inactive activate-skin').addClass('icon-spinner icon-spin');
        
        $.ajax({
            url: ajaxurl,
            data: {
                gummcontroller: 'skins',
                action: 'set_active',
                gummnamed: {id: $this.data('skin-id')}
            },
            success: function(data, textStatus, XMLHttpRequest) {
                $('#bluebox-admin').load(ajaxurl, {gummcontroller: 'options', action: 'index'});
            }
        });
        
    });

    $(document).on('click', '#gumm-add-new-skin', function(e){
        e.preventDefault();
        
        var popup = new $.gummPopup({
            width: 640,
            height: 580,
            url: $(this).attr('href'),
            onConfirm: function(gummui) {
                var theForm = $(this).find('form#gumm-skin-edit-form');
                if (theForm.size() > 0) {
                    theForm.trigger('submit', [gummui]);
                }
            }
        }, null, $(this));
        
    });
    
    $(document).on('click', '.gumm-edit-skin', function(e){
        e.preventDefault();
        var id = $(this).data('skin-id');
        
        var popup = new $.gummPopup({
            width: 640,
            height: 580,
            url: ajaxurl,
            urlData: {
                gummcontroller: 'skins',
                action: 'edit',
                gummnamed: {id: id}
            },
            onConfirm: function(gummui) {
                var theForm = $(this).find('form#gumm-skin-edit-form');
                if (theForm.size() > 0) {
                    theForm.trigger('submit', [gummui]);
                }
            }
        }, null, $(this));
    });
    $(document).on('click', '.gumm-remove-skin', function(e){
        e.preventDefault();
        var id = $(this).data('skin-id');
        var $this = $(this);
        $this.removeClass('gumm-remove-skin').addClass('icon-spinner icon-spin');
        
        $.ajax({
            url: ajaxurl,
            data: {
                gummcontroller: 'skins',
                action: 'delete',
                gummnamed: {id: id}
            },
            dataType: 'json',
            success: function(data, textStatus, XMLHttpRequest) {
                try {
                    if (data.status === undefined) {
                        return;
                    } else if (data.status == 'ko') {
                        gummbase.alert(data.msg, {type: 'error'});
                    } else if (data.status == 'ok') {
                        $('#bluebox-admin').load(ajaxurl, {gummcontroller: 'options', action: 'index'});
                    }
                } catch(err){}
            }
        });
    });
    
    // ADMIN WIDGETS
    $(document).on('click', 'a.best-offers-widget-add', function(e){
        e.preventDefault();
        var toClone = $(this).prev('.offer-inputs');
        var theClone = toClone.clone();
        var lastOfferNumber = theClone.data('offernumber');
        var currentOfferNumber = lastOfferNumber + 1;
        var iterationsNumber = lastOfferNumber;
        
        theClone.attr('data-offernumber', currentOfferNumber);
        theClone.find('.offer-no').text(currentOfferNumber);
        
        var _regexId = /(widget\-gumm\_offers\-\d+\-offers\.)\d+(\.[a-z]+)/i;
        var _regexName = /(widget\-gumm\_offers\[\d+\]\[offers\.)\d+(\.[a-z]+\])/i;
        theClone.children().each(function(i, ele){
            if ($(ele).is('label')) {
                var _labelFor = $(ele).attr('for').replace(_regexId, '$1' + iterationsNumber + '$2');
                $(ele).attr('for', _labelFor);
            } else if ($(ele).is('input')) {
                $(ele).val('');
                $(ele).attr('id', $(ele).attr('id').replace(_regexId, '$1' + iterationsNumber + '$2'));
                $(ele).attr('name', $(ele).attr('name').replace(_regexName, '$1' + iterationsNumber + '$2'));
            }
        });
        console.log(lastOfferNumber);
        $(this).before(theClone);
    });
    
    // INPUTS
    // $('#formatdiv.postbox').gummPostFormatRadio();
    $('.gumm-table-setup').gummTableSetup({rows: 3, cols: 3});
    
    $(document).on('click', '.gumm-add-content-tab-inputs', function(e){
        e.preventDefault();
        
        var theTabEditor = $($(this).data('tab-editor')).data('gummTabbedInput');
        if (theTabEditor !== undefined) {
            var theTabTitle = theTabEditor.navigationContainer.data('tab-label') + ' #' + (theTabEditor.tabsContainers.length + 1);
            var theTabContent = $('<div />');
            // var spinningIcon = $('<i class="icon-spinner icon-spin" />');
            // spinningIcon.css({
            //     fontSize: '24px',
            //     margin: '61px auto',
            //     width: '24px',
            //     height: '24px',
            //     display: 'block'
            // });
            // theTabContent.css({
            //     minHeight: 50
            // });
            var theButton = $(this);
            theButton.addClass('acting');
            
            var newTabIndex = theTabEditor.addTab(theTabTitle, theTabContent, false);
            theTabContent.load($(this).data('add-new-url'), function(){
                $(this).data('mce-init-height', 10);
                initGummWpEditor($(this), true);
                theButton.removeClass('acting');
                
                theTabEditor.goTo(newTabIndex);
            });
        }
    });
    $(document).on('click', '.gumm-delete-content-tab', function(e){
        e.preventDefault();
        
        var theTabEditor = $($(this).data('tab-editor')).data('gummTabbedInput');
        if (theTabEditor !== undefined) {
            theTabEditor.deleteCurrentTab();
        }
    });
    
    $('.gumm-time-zone-picker').gummTimeZonePicker();
});




// $( gumm.media.init );


});
// CUSTOM CALLBACKS

// Layout editor callbacks for ajaxupload
function layoutLayerEditor_onAjaxUploadComplete(params) {
    var response = params.response;

    if (response.error !== undefined || response.url === undefined) {
        gummbase.alert(response.error, {type: 'error'});
        return false;
    }
    var theButton = jQuery(this._button);
    
    theButton.parent().find('.single-upload-value').val(response.url).trigger('change');
}

function scEditorMagnifyingGlass_onAjaxUploadComplete(params) {
    var response = params.response;
    if (response.error !== undefined || response.url === undefined) {
        gummbase.alert(response.error, {type: 'error'});
        return false;
    }
    var theButton = jQuery(this._button);
    var theInput = theButton.next();
    theInput.val(response.url).trigger('change');
}

function themeOptions_onAjaxUploadComplete(params) {
    var response = params.response;
    if (response.error !== undefined || response.url === undefined) {
        gummbase.alert(response.error, {type: 'error'});
        return false;
    }
    
    var theButton = jQuery(this._button);
    
    var theWrap = theButton.parent().parent('.wrap-upload');
    var theInput = theWrap.find('input.text-input');;
    theInput.val(response.url).trigger('change');
    
    var widthInput = theWrap.find('.upload-width-input');
    var heightInput = theWrap.find('.upload-height-input');
    
    widthInput.val(response.width);
    heightInput.val(response.height);
    
    var thePreviewContainer = theWrap.find('.upload-preview-container');
    
    if (thePreviewContainer.size() > 0) {
        var theImg = thePreviewContainer.children('img');
        if (theImg.size() < 1) {
            theImg = jQuery('<img />');
            thePreviewContainer.append(theImg);
        }
        
        theImg.attr('src', response.url);
    }
}

(function( $ ){
  $(document).ajaxSuccess(function(e, XMLHttpRequest, ajaxOptions){
      // if (ajaxOptions.data !== undefined) {
          initGummSliderInputs();
          initGummTabbedInputs();
      // }
  });

  $.fn.gummPostFormatRadio = function( options ) {  

    // Create some defaults, extending them with any options that were provided
    var settings = $.extend( {
      'location'         : 'top',
      'background-color' : 'blue',
      'overwriteLabels': {
          'Chat': 'Poker Hand'
      }
    }, options);
    
    var postIdMatch = /post=(\d+)/g.exec(window.location);
    var pId = false;
    if (postIdMatch !== null) pId = postIdMatch[1];

    var _methods = {
        checkFormatAvailability: function(format) {
            if (!pId || !format) return;
            
            var theMessageContainer = $(this).find('.post-format-message');
            $.ajax({
                url: ajaxurl,
                data: {
                    gummcontroller: 'posts',
                    action: 'gumm_check_format_availability',
                    gummnamed: {
                        postId: pId,
                        postFormat: format
                    }
                },
                dataType: 'json',
                success: function(data, textStatus, XMLHttpRequest) {
                    if (data.ok !== true) {
                        theMessageContainer.html(data.msg);
                        theMessageContainer.show();
                    } else {
                        theMessageContainer.html('');
                        theMessageContainer.hide();
                    }
                }
            });
        }
    };
    
    return this.each(function() {        
        var innerContent = $(this).find('.inside');
        var theInputs = [];
        innerContent.find('input').each(function(i, ele){
            var label = $(ele).next('label');
            $.each(settings.overwriteLabels, function(l, val){
                if (l == label.text()) {
                    label.text(val);
                    return;
                }
            });
            theInputs.push({
                input: $(ele),
                label: label
            });
        });
        
        var theGummContainer = $('<div id="gumm-metabox-post-format" class="gumm-metabox-post-format">');
        var defaultFormat = false;
        $.each(theInputs, function(i, eles){
            var currentFormat = eles.label.text().toLowerCase();
            var theRadioContainer = $('<div class="post-format-icon gumm-radio-container"></div>');
            theRadioContainer.addClass(eles.label.text().toLowerCase());
            if (eles.input.attr('checked') == 'checked') {
                theRadioContainer.addClass('selected-radio-container');
                defaultFormat = currentFormat;
            }
            
            theRadioContainer.append(eles.label);
            
            var theRadioButton = $('<a href="#" class="gumm-radio"></a>');
            if (eles.input.attr('checked') == 'checked') theRadioButton.addClass('selected-radio');
            
            theRadioContainer.append(theRadioButton);
            
            theGummContainer.append(theRadioContainer);
            
            eles.label.bind('click', function(e){
                theRadioButton.trigger('click');
            });
            theRadioButton.bind('click', function(e){
                eles.input.trigger('click');
            });
            
            eles.input.bind('change', function(e){
                _methods.checkFormatAvailability.call(theGummContainer, currentFormat);
            });
            

        });
        var theAvailabilityMessage = '<p class="post-format-message hidden"></p>';
        theGummContainer.append('<div class="clear"></div>');
        theGummContainer.append(theAvailabilityMessage);
        innerContent.find('#post-formats-select').hide();
        innerContent.addClass('gumm-radio-fieldset');
        innerContent.prepend(theGummContainer);
        innerContent.append('<div class="clear"></div>');

        _methods.checkFormatAvailability.call(theGummContainer, defaultFormat);

    });

  };
})( jQuery );

(function (window, $, undefined) {
    window.initGummTabbedInputs = function() {
        $('.tabbed-input').gummTabbedInput();
    }
    
    window.initGummSliderInputs = function() {
        $('.gumm-slider-input').each(function(i, ele){
            initGummSliderInput($(ele));
        });
    }
    
    window.displayGummInputSliderLabel = function(event, ui) {
        var theLabel = $(this).prev('.gumm-slider-label');
        if (theLabel.length > 0) {
            var theHandle = $(this).children('.ui-slider-handle');
            var min = $(this).slider('option', 'min');
            var max = $(this).slider('option', 'max');
            var val = ui.value;
            var left = (val-min)/(max-min)*100;
            var top = 100 - left;
            
            var maxTitle = $(this).slider('option', 'maxTitle');
            var numberType = $(this).slider('option', 'numberType');
            var labelNumberValue = theLabel.children('.popover-content').children('.value');
            var labelNumberType = theLabel.children('.popover-content').children('.number-type');
            if (val == max && maxTitle) {
                labelNumberValue.text(maxTitle);
                labelNumberType.text('');
            } else {
                labelNumberValue.text(val);
                if (numberType)
                labelNumberType.text(numberType);
            }
        }

        if ($(this).slider('option', 'orientation') == 'vertical') {
            theLabel.css({
                top: top + '%'
            });
        } else {
            theLabel.css({
                left: left + '%',
                marginLeft: -(theLabel.width()/2)
            });
        }
    }
    
    window.initGummSliderInput = function(ele) {
        // If radio - crazy selection
        if ($(ele).is(':radio')) {
            var eles = $('[name="' + $(ele).attr('name') + '"]');
            ele = eles.filter(':checked');
            var theSlider = $(ele).parent().parent().children('.gumm-slider-container');
        } else {
            var theSlider = $(ele).prev();
        }
        // If initialized - continue
        if (theSlider.data('gumm-slider-input-initialized') === true) {
            return;
        }
        
        theSlider.data('gumm-slider-input-initialized', true);
        
        var maxTitle = $(ele).data('gumm-max-title'),
            maxValue = $(ele).data('gumm-max-value'),
            max = $(ele).data('gumm-max');
        var startValue = (maxValue && $(ele).val() == maxValue) ? max : $(ele).val();
        
        if ($(ele).is(':radio')) {
            eles.on('change', function(){
                var theValue = $(eles).filter(':checked').val();
                if (maxValue && theValue == maxValue) theValue = max;
                theSlider.slider('value', theValue);
            });
        } else {
            $(ele).on('change', function(){
                var theValue = $(ele).val();
                if (maxValue && theValue == maxValue) theValue = max;
                theSlider.slider('value', theValue);
            });
        }

        theSlider.slider({
            min: $(ele).data('gumm-min'),
            max: max,
            step: $(ele).data('gumm-step'),
            animate: $(ele).data('gumm-animate'),
            value: startValue,
            orientation: $(ele).data('gumm-orientation'),
            maxTitle: maxTitle,
            maxValue: maxValue,
            numberType: $(ele).data('gumm-number-type'),
            create: function(event, ui) {
                ui.value = $(this).slider('value');
                displayGummInputSliderLabel.apply(this, [event, ui]);
            },
            slide: function(event, ui) {
                var theInputs = $(this).parent().find('input.gumm-slider-input');

                var maxValue = $(this).slider('option', 'maxValue');
                var theValue = ui.value;
                if ($(this).slider('option', 'max') == theValue && maxValue) theValue = maxValue;
                if (theInputs.is(':radio')) {
                    theInputs.prop('checked', false);
                    // var _tI = theInputs.filter('[value=' + theValue + ']');
                    // console.log(_tI);
                    theInputs.filter('[value=' + theValue + ']').prop('checked', true).trigger('change');
                } else {
                    theInputs.val(theValue).trigger('change');
                }
                displayGummInputSliderLabel.apply(this, [event, ui]);
            },
            start: function(event, ui) {
                $(this).prev('.gumm-slider-label').css('display', 'block');
            },
            stop: function(event, ui) {
                $(this).prev('.gumm-slider-label').css('display', '');
            },
            change: function(event, ui) {
                displayGummInputSliderLabel.apply(this, [event, ui]);
            }
        });
    }
    
    window.destroyGummWpEditor = function(theElement) {
        var editorWrap = theElement.find('.gumm-rich-text-editor');
        editorWrap.each(function(i, ele){
            var richTextEditor = $(ele).find('textarea');
            var id = richTextEditor.attr('id');
            
            var ed = tinyMCE.getInstanceById(id);
            
            if (ed) {
                ed.remove();
            }
        });
    }
    window.initGummWpEditor = function(theElement, withQuicktags) {
        var editorWrap = theElement.find('.gumm-rich-text-editor');
        editorWrap.each(function(i, ele){
            var richTextEditor = $(ele).find('textarea');
            initTextareaGummWpEditor(richTextEditor, withQuicktags)
        });
    }
    window.initTextareaGummWpEditor = function(richTextEditor, withQuicktags) {
        var id = richTextEditor.attr('id');
        
        var thePreInitSelector = 'content';
        if (tinyMCEPreInit.mceInit[thePreInitSelector] === undefined) {
            thePreInitSelector = 'gummwpeditorhelper';
        }

        var theMceInit  = tinyMCEPreInit.mceInit[thePreInitSelector];
        var theQtInit   = tinyMCEPreInit.qtInit[thePreInitSelector];

        theMceInit.elements     = id;
        theMceInit.body_class   = id + ' post-type-page';
        
        theQtInit.id            = id;

        tinyMCEPreInit.mceInit[id]  = theMceInit;
        tinyMCEPreInit.qtInit[id]   = theQtInit;

        var ed = tinyMCE.getInstanceById(id);
        if (!ed) {
            if (withQuicktags === true) {
                quicktags({id : id});
                QTags._buttonsInit();
            } else {
                tinyMCE.init({
                    skin : "wp_theme"
                });
            } 
        } else {
            // Supposedly this should never get called,
            // as before this function is called, the editor must have been destroyed
            ed.remove();
            quicktags({id : id});
            QTags._buttonsInit();
        }
        switchEditors.go(id, 'tmce');
    }
    
    // TABLES EDITOR
	$.gummTableSetup = function gummTableSetup(options, callback, element) {
        this.element = $(element);
        this._create(options, callback);

	};
	$.gummTableSetup.settings = {
	    rows: 1,
	    cols: 1,
	    active: true
	};
	
	$.gummTableSetup.prototype = {
	    _create: function(options, callback) {
	        var instance = this;
	        this.navigatorElement = this.element.find('.dynamic:first');
	        this.inputElements = {
	            rows: this.element.find('input.gumm-table-setup-value.gumm-table-rows'),
	            cols: this.element.find('input.gumm-table-setup-value.gumm-table-cols')
	        };
            this.options = $.extend(true, {}, $.gummTableSetup.settings, options);
            var theCell = this.element.find('.single-square:first');
            this.options.cellOuterWidth = theCell.outerWidth();
            this.options.cellWidth = theCell.width();
            this.options.cellOuterHeight = theCell.outerHeight();
            this.options.cellHeight = theCell.height();
	        
	        this.element.find('.single-square').each(function(i, cellItem){
	            var clonedCell = $(cellItem).clone();
	            instance.element.append(clonedCell);
	            clonedCell.css({
	                position: 'absolute',
	                top: $(cellItem).position().top,
	                left: $(cellItem).position().left,
	                opacity: 0
	            });
	        });
	        
	        this._setDefaultCell();
            this._bindListeners();
	    },
	    _setDefaultCell: function() {
	        var rows = this.inputElements.rows.val();
	        var cols = this.inputElements.cols.val();
	        var r,c,counter = 0;
	        var cells = this.element.find('.single-square');

            var ele = false;
	        for (r=1; r<=this.options.rows; r++) {
	            for (c=1; c<=this.options.cols; c++) {
	                if (c == cols && r == rows) {
	                    ele = cells.eq(counter);
	                    break;
	                }
                    counter++;
	            }
	            if (ele) break;
	        }
            
            if (ele) this._select($(ele));
	    },
	    _bindListeners: function() {
	        var instance = this;

	        this.element.find('.single-square').bind('mouseenter', function(e){
	            if (instance.active()) instance._select($(this));
	        });
	        this.element.bind('click', function(e){
	            e.preventDefault();
                instance._toggleActive();
	        });
	    },
	    _select: function(cellItem) {
	        var width = cellItem.position().left + cellItem.width();
	        var height = cellItem.position().top + cellItem.height();

            this.navigatorElement.outerWidth(width);
            this.navigatorElement.outerHeight(height);
            this.navigatorElement.show();
            
            this._setValues();
	    },
	    _setValues: function() {
	        var rows = Math.round(this.navigatorElement.outerHeight() / this.options.cellHeight);
	        var cols = Math.round(this.navigatorElement.outerWidth() / this.options.cellWidth);
	        
	        this.inputElements.rows.val(rows);
	        this.inputElements.cols.val(cols);
	    },
	    _toggleActive: function() {
	        if (this.options.active) this.options.active = false;
	        else this.options.active = true;
	    },
	    active: function(active) {
	        if (active !== undefined) {
	            this.options.active = active;
	        }
	        
	        return this.options.active;
	    }
	    
	};
	
    $.fn.gummTableSetup = function _gummTableInit(options, callback) {
        this.each(function () {

            var instance = $.data(this, 'gummTableSetup');

            if (instance) {
                // update options of current instance
                // instance.update(options);

            } else {
                // initialize new instance
                $.data(this, 'gummTableSetup', new $.gummTableSetup(options, callback, this));
            }
            
        });
        
        return this;
    }
    
    // TABS EDITOR
    
	$.gummTabsEditor = function gummTabsEditor(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    };
    $.gummTabsEditor.settings = {
        addButton: '.gumm-add-tab',
        deleteButton: '.gumm-delete-tab',
        inputsWrap: '.gumm-tabs-inputs-wrapper',
        inputsShell: '.gumm-tabs-inputs-shell',
        initWithNoData: true
    };

    $.gummTabsEditor.prototype = {
        addButtonElement: null,
        inputsWrapElement: null,
        inputsShellElement: null,
        insertShortcodeElement: null,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummTabsEditor.settings, options);
            
            this.addButtonElement = this.element.find(this.options.addButton);
            this.inputsWrapElement = this.element.find(this.options.inputsWrap);
            this.inputsShellElement = this.element.find(this.options.inputsShell);
            this.inputsWrapElement.children().each(function(i, ele){
                _self._bindTabListeners($(ele));
            });
            
            if (this.options.initWithNoData) {
                var theClone = this.inputsShellElement.clone();
                theClone.addClass('active');

                this.inputsWrapElement.append(theClone.show());
                this._uniqueInputs(theClone);
                this._bindTabListeners(theClone);

                theClone.find('.gumm-tab-active-input').attr('checked', 'checked');
            }
            // theInput.attr('checked', 'checked');
            // theInput.change();
            
            // console.log(theInput);
            
            this._bindListeners();
        },
        addTabInputs: function() {
            var theClone = this.inputsShellElement.clone();
            var lastVisibleElement = this.inputsWrapElement.children(':visible').last();
            theClone.addClass('active');
            theClone.css({
                opacity: 0,
                display: 'block',
                position: 'relative',
                top: -lastVisibleElement.outerHeight(),
                width: lastVisibleElement.width()
            });
            this.inputsWrapElement.append(theClone);
            theClone.animate({
                opacity: 1,
                top: -50 - lastVisibleElement.outerHeight()
            }, 150).animate({
                top: 0
            });
            
            this._uniqueInputs(theClone);
            this._bindTabListeners(theClone);
        },
        _uniqueInputs: function(ele) {
            ele.find('input,textarea,select').each(function(i, inputEle){
                var theId = $(inputEle).attr('id') + uniqid();
                $(inputEle).prev('label').attr('for', theId);
                $(inputEle).attr('id', theId);
                if ($(inputEle).is('textarea') && $(ele).hasClass('load-tiny')) {
                    tinyMCE.execCommand('mceAddControl', false, theId);
                }
                $(inputEle).removeAttr('disabled');
            });
        },
        _bindTabListeners: function(tab) {
            tab.find(this.options.deleteButton).on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().remove();
            });
        },
        _bindListeners: function() {
            var instance = this;
            this.addButtonElement.bind('click', function(e){
                e.preventDefault();
                instance.addTabInputs();
            });
        }
    };
    
    $.fn.gummTabsEditor = function _gummTabsEditorInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummTabsEditor');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummTabsEditor', new $.gummTabsEditor(options, callback, this));
            }
            
        });
        return this;
    }
    
    // SKILL BARS EDITOR
    
	$.gummSkillBarsEditor = function gummSkillBarsEditor(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    };
    $.gummSkillBarsEditor.settings = {
        addButton: '.gumm-add-skill',
        deleteButton: '.gumm-delete-skill',
        inputsWrap: '.gumm-skill-bars-inputs-wrapper',
        inputsShell: '.gumm-skill-bars-inputs-shell',
        initWithNoData: true
    };

    $.gummSkillBarsEditor.prototype = {
        addButtonElement: null,
        inputsWrapElement: null,
        inputsShellElement: null,
        insertShortcodeElement: null,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummSkillBarsEditor.settings, options);
            
            this.addButtonElement = this.element.find(this.options.addButton);
            this.inputsWrapElement = this.element.find(this.options.inputsWrap);
            this.inputsShellElement = this.element.find(this.options.inputsShell);
            
            
            if (this.options.initWithNoData) {
                this.addSkillBarInputs();
            }
            
            this._bindListeners();
        },
        addSkillBarInputs: function() {
            var theClone = this.inputsShellElement.clone();
            var lastVisibleElement = this.inputsWrapElement.children(':visible').last();
            theClone.addClass('active');
            theClone.css({
                opacity: 0,
                display: 'block',
                position: 'relative',
                top: -lastVisibleElement.outerHeight(),
                width: lastVisibleElement.width()
            });
            this.inputsWrapElement.append(theClone);
            theClone.animate({
                opacity: 1,
                top: -50 - lastVisibleElement.outerHeight()
            }, 150).animate({
                top: 0
            });
            
            this._uniqueInputs(theClone);
            this._bindTabListeners(theClone);
        },
        _uniqueInputs: function(ele) {
            var _self = this;
            ele.find('input,textarea,select').each(function(i, inputEle){
                var theId = $(inputEle).attr('id') + uniqid();
                $(inputEle).prev('label').attr('for', theId);
                $(inputEle).attr('id', theId);
                $(inputEle).removeAttr('disabled');
                if ($(inputEle).is('textarea') && $(ele).hasClass('load-tiny')) {
                    tinyMCE.execCommand('mceAddControl', false, theId);
                } else if ( $(inputEle).hasClass('gumm-slider-input')) {
                    initGummSliderInput($(inputEle));
                }
            });
        },
        _bindTabListeners: function(tab) {
            tab.find(this.options.deleteButton).on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().remove();
            });
        },
        _bindListeners: function() {
            var instance = this;
            this.addButtonElement.on('click', function(e){
                e.preventDefault();
                instance.addSkillBarInputs();
            });
        }
    };
    
    $.fn.gummSkillBarsEditor = function _gummSkillBarsEditorInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummSkillBarsEditor');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummSkillBarsEditor', new $.gummSkillBarsEditor(options, callback, this));
            }
            
        });
        return this;
    }
    // END SKILL TABS EDITOR
    
    
    
    // BEGIN LIST EDITOR
    
	$.gummListEditor = function gummListEditor(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    };
    $.gummListEditor.settings = {
        addButton: '.gumm-add-list-item',
        deleteButton: '.gumm-delete-list-item',
        inputsWrap: '.gumm-list-inputs-wrapper',
        inputsShell: '.gumm-list-inputs-shell',
        initWithNoData: true
    };

    $.gummListEditor.prototype = {
        addButtonElement: null,
        inputsWrapElement: null,
        inputsShellElement: null,
        insertShortcodeElement: null,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummListEditor.settings, options);
            
            this.addButtonElement = this.element.find(this.options.addButton);
            this.inputsWrapElement = this.element.find(this.options.inputsWrap);
            this.inputsShellElement = this.element.find(this.options.inputsShell);
            
            
            if (this.options.initWithNoData) {
                this.addListInputs();
            }
            
            this._bindListeners();
        },
        addListInputs: function() {
            var theClone = this.inputsShellElement.clone();
            var lastVisibleElement = this.inputsWrapElement.children(':visible').last();
            theClone.addClass('active');
            theClone.css({
                opacity: 0,
                display: 'block',
                position: 'relative',
                top: -lastVisibleElement.outerHeight(),
                width: lastVisibleElement.width()
            });
            this.inputsWrapElement.append(theClone);
            theClone.animate({
                opacity: 1,
                top: -50 - lastVisibleElement.outerHeight()
            }, 150).animate({
                top: 0
            });
            
            this._uniqueInputs(theClone);
            this._bindTabListeners(theClone);
        },
        _uniqueInputs: function(ele) {
            ele.find('input,textarea,select').each(function(i, inputEle){
                var theId = $(inputEle).attr('id') + uniqid();
                $(inputEle).prev('label').attr('for', theId);
                $(inputEle).attr('id', theId);
                if ($(inputEle).is('textarea') && $(ele).hasClass('load-tiny')) {
                    tinyMCE.execCommand('mceAddControl', false, theId);
                }
                $(inputEle).removeAttr('disabled');
            });
        },
        _bindTabListeners: function(tab) {
            tab.find(this.options.deleteButton).on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().remove();
            });
        },
        _bindListeners: function() {
            var instance = this;
            this.addButtonElement.bind('click', function(e){
                e.preventDefault();
                instance.addListInputs();
            });
        }
    };
    
    $.fn.gummListEditor = function _gummListEditorInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummListEditor');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummListEditor', new $.gummListEditor(options, callback, this));
            }
            
        });
        return this;
    }
    
    // END LIST EDITOR
    
    // BEGIN PRICING TABLES EDITOR
    
	$.gummPricingTablesEditor = function gummListEditor(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    };
    
    $.gummPricingTablesEditor.settings = {
        addRowButton: '.gumm-add-table-row',
        columnItem: '.gumm-table-column',
        listItem: '.list-item',
        removeColumnButton: '.gumm-delete-table-column'
    };
    
    $.gummPricingTablesEditor.prototype = {
        addRowElement: null,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummPricingTablesEditor.settings, options);
            
            this.addRowButton = this.element.find(this.options.addRowButton);
            this.columnItems = this.element.find(this.options.columnItem);
            
            this.bindListeners();
        },
        getColumns: function() {
            return this.columnItems;
        },
        addRow: function() {
            var _self = this;
            this.getColumns().each(function(i, ele){
                var columnEle = $(ele);
                var listItemToClone = columnEle.find(_self.options.listItem + ':last');
                var clonedItem = listItemToClone.clone();
                
                clonedItem.find('input,textarea,select').each(function(i, inputEle){
                    var theId = $(inputEle).attr('id') + uniqid();
                    $(inputEle).prev('label').attr('for', theId);
                    $(inputEle).attr('id', theId);
                    if ($(inputEle).is('textarea') && $(ele).hasClass('load-tiny')) {
                        tinyMCE.execCommand('mceAddControl', false, theId);
                    }
                    $(inputEle).removeAttr('disabled');
                });
                
                
                listItemToClone.after(clonedItem);
                
            });
        },
        bindListeners: function() {
            var _self = this;
            this.addRowButton.on('click', function(e){
                e.preventDefault();
                _self.addRow();
            });
        }
    };
    
    $.fn.gummPricingTablesEditor = function _gummPricingTablesEditorInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummPricingTablesEditor');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummPricingTablesEditor', new $.gummPricingTablesEditor(options, callback, this));
            }
            
        });
        return this;
    }
    
    // END PRICING TABLES EDITOR
    
    $.gummNavigationMenu = function _gummNavigationMenu(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummNavigationMenu.settings = {
        items: 'li',
        subItems: 'ul'
    }
    
    $.gummNavigationMenu.prototype = {
        activeTopItem: null,
        __construct: function(options, callback){
            this.options = $.extend(true, {}, $.gummNavigationMenu.settings, options);
            this.bindListeners();
        },
        openItem: function(ele, sub) {
            ele.addClass('active');
            ele.children(this.options.subItems).show('fade', 100);
            
            if (sub !== true) this.activeTopItem = ele;
        },
        closeItem: function(ele, sub) {
            ele.removeClass('active');
            ele.find(this.options.subItems).hide('fade', 100);
            
            if (sub !== true) this.activeTopItem = null;
        },
        openSubItem: function(ele) {
            
        },
        closeSubItem: function(ele){
            
        },
        bindListeners: function() {
            var _self = this;
            this.element.children(this.options.items).bind('click', function(e){
                e.preventDefault();
                if ($(this).is(_self.activeTopItem)) {
                    _self.closeItem($(this));
                } else {
                    _self.openItem($(this));
                }
            });
            this.element.find(this.options.items + '.selectable').children('a').bind('click', function(e){
                e.preventDefault();
                var theLi = $(this).parent();
                theLi.toggleClass('selected');
                theLi.trigger('gummNavigationMenuSelected', [theLi.hasClass('selected')]);
            });
            this.element.children(this.options.items).bind('mouseenter', function(e){
                if (_self.activeTopItem !== null) {
                    _self.closeItem(_self.activeTopItem);
                    _self.openItem($(this));
                }
            });
            this.element.children(this.options.items).find(this.options.items).bind('mouseenter', function(e){
                _self.openItem($(this), true);
            });
            this.element.children(this.options.items).find(this.options.items).bind('mouseleave', function(e){
                _self.closeItem($(this), true);
            });
            $('body').bind('click', function(e){
                if (_self.activeTopItem !== null && $(e.target).parents('.' + _self.element.attr('class')).size() < 1) {
                    e.stopPropagation();
                    _self.closeItem(_self.activeTopItem);
                }
            });
        }
    }
    $.fn.gummNavigationMenu = function _gummNavigationMenuInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummNavigationMenu');
            if (instance) {

            } else {
                $.data(this, 'gummNavigationMenu', new $.gummNavigationMenu(options, callback, this));
            }
            
        });
        return this;
    }
    
    // Gumm Rating Picker FN
    $.gummRatingPicker = function gummRatingPicker(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummRatingPicker.prototype = {
        starsContainer: null,
        stars: null,
        input: null,
        state: 'listen',
        rating: 0,
        __construct: function(options, callback) {
            this.starsContainer = this.element.find('.rating-starts');
            this.stars = this.starsContainer.find('.star');
            this.clearIcon = this.starsContainer.find('.clear-rating');
            this.input = this.element.find('input:first');
            this.rating = this.input.val();
            this.bindListeners();
        },
        select: function(index) {
            this.stars.each(function(i, ele){
                if (index >= i) {
                    $(ele).removeClass('icon-star-empty').addClass('icon-star');
                } else {
                    $(ele).removeClass('icon-star').addClass('icon-star-empty');
                }
            });
            this.input.val(index+1);
        },
        bindListeners: function() {
            var _self = this;
            this.stars.on('mouseenter', function(e){
                _self.select($(this).parent().index());
            });
            this.starsContainer.on('mouseleave', function(e){
                _self.select(_self.rating-1)
            });
            this.clearIcon.on('click', function(e){
                e.preventDefault();
                _self.select(-1);
                _self.rating = 0;
            });
            this.stars.on('click', function(e){
                _self.rating = _self.input.val();
                e.preventDefault();
            });
        }
    }
    $.fn.gummRatingPicker = function initGummRatingPicker(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummNavigationMenu');
            if (instance) {

            } else {
                $.data(this, 'gummRatingPicker', new $.gummRatingPicker(options, callback, this));
            }
            
        });
        return this;
    }
    
    // Gumm Tabbed Input FN
    $.gummTabbedInput = function (options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummTabbedInput.settings = {
        sortable: false
    },
    $.gummTabbedInput.prototype = {
        _currentTab: null,
        tabLabel: '',
        __construct: function(options, callback) {
            this.options = $.extend(true, {}, $.gummTabbedInput.settings, options);
            
            this._initializeElements();
            this.tabLabel = this.navigationContainer.data('tab-label');
            this.goTo(this.currentTab());
            this._bindListeners();
            this._initializeWidgets();
        },
        _initializeElements: function() {
            this.navigationContainer    = this.element.children('.nav-tabs');
            this.navigationButtons      = this.navigationContainer.children('li');
            this.navigationInputs       = this.navigationContainer.find('input[type=radio]');
            this.tabsContainer          = this.element.children('.tabbed-inputs');
            this.tabsContainers         = this.tabsContainer.children();
        },
        currentTab: function() {
            if (this._currentTab === null) {
                if (this.navigationInputs.length > 0) {
                    this._currentTab = this.navigationInputs.filter(':checked').parent().index();
                } else {
                    this._currentTab = this.navigationButtons.filter('.active').index();
                }
            }
            
            return this._currentTab;
        },
        addTab: function(title, content, goToCurrent) {
            var navigationButton = this.navigationButtons.last().clone();
            navigationButton.children('a').html(title);
            this.navigationContainer.append(navigationButton);
            this.tabsContainer.append(content);

            var newIndex = content.index();

            this._initializeElements();
            if (goToCurrent !== false) {
                this.goTo(newIndex);
            } else {
                navigationButton.removeClass('active');
            }
            this._bindListeners();
            
            return newIndex;
        },
        deleteTab: function(index) {
            this.navigationButtons.eq(index).remove();
            this.tabsContainers.eq(index).remove();
            
            this._initializeElements();
            this.fixLabels();
            this._currentTab = null;
            
            var toGoTo = index > 0 ? (index-1) : 0;
            this.goTo(toGoTo);
        },
        deleteCurrentTab: function() {
            this.deleteTab(this.currentTab());
        },
        fixLabels: function() {
            var _self = this;
            this.navigationButtons.each(function(i, ele){
                $(ele).children('a').html(_self.tabLabel + ' #' + (i+1));
            });
        },
        goTo: function(index) {
            if (this.currentTab() === index) return;
            
            this.navigationInputs.attr('checked', false);
            this.navigationInputs.eq(index).attr('checked', true);
            
            this.navigationButtons.removeClass('active');
            this.navigationButtons.eq(index).addClass('active');
            
            this.tabsContainers.hide();
            this.tabsContainers.eq(index).show();
            
            this._currentTab = index;
        },
        _initializeWidgets: function() {
            var _self = this;
            if (this.options.sortable === true) {
                this.navigationContainer.sortable({
                    handle: 'a',
                    start: function(event, ui) {
                        ui.item.data('gumm-original-index', ui.item.index());
                        destroyGummWpEditor(ui.item);
                    },
                    update: function(event, ui) {
                        var currIndex   = ui.item.index();
                        var initIndex   = ui.item.data('gumm-original-index');
                        
                        var correspondingContent = _self.tabsContainers.eq(initIndex);
                        
                        // if (currIndex !== initIndex)
                        if (currIndex > initIndex) {
                            _self.tabsContainers.eq(currIndex).after(correspondingContent);
                        } else if (currIndex < initIndex) {
                            _self.tabsContainers.eq(currIndex).before(correspondingContent);
                        }
                        // if (currIndex === 0) {
                        //                             _self.tabsContainers.eq(currIndex).before(correspondingContent);
                        //                         } else {
                        //                             _self.tabsContainers.eq(currIndex).after(correspondingContent);
                        //                         }
                        
                        _self._initializeElements();
                        _self.fixLabels();
                        _self._currentTab = null;
                        initGummWpEditor(ui.item);
                    }
                });
            }
        },
        _bindListeners: function() {
            var _self = this;
            
            this.navigationButtons.on('click', function(e){
                e.preventDefault();
                
                _self.goTo($(this).index());
            });
        }
    }
    $.fn.gummTabbedInput = function gumMTabbedInputFn(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummTabbedInput');
            if (instance) {

            } else {
                $.data(this, 'gummTabbedInput', new $.gummTabbedInput(options, callback, this));
            }
            
        });
        return this;
    }
    
    // Gumm Page Builder
    $.gummPageBuilder = function (options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummPageBuilder.settings = {
        pageModel: '.admin-page-builder',
        elementsTabs: '.builder-elements-tabs',
        schemaInputsContainer: null,
        sidebarSchema: 'none',
        postId: null,
        itemPercentMargin: 2.127659574468085,
        widthsPercents: {
            1: 6.382978723404255,
            2: 14.893617021276595,
            3: 23.404255319148934,
            4: 31.914893617021278,
            5: 40.42553191489362,
            6: 48.93617021276595,
            7: 57.44680851063829,
            8: 65.95744680851064,
            9: 74.46808510638297,
            10: 82.97872340425532,
            11: 91.48936170212765,
            12: 100
        }
    }
    $.gummPageBuilder.prototype = {
        initialized: false,
        inEditMode: false,
        activeEditor: null,
        elementToTriggerVisible: null,
        editorMode: 'full',
        __construct: function(options, callback) {
            this.options = $.extend(true, {}, $.gummPageBuilder.settings, options);
            if (this.element.data('schema-inputs-container') !== undefined) {
                this.options.schemaInputsContainer = this.element.data('schema-inputs-container');
            }
            if (this.element.data('sidebar-schema') !== undefined) {
                this.options.sidebarSchema = this.element.data('sidebar-schema');
            }
            if (this.element.data('post-id') !== undefined) {
                this.postId = this.element.data('post-id');
            }
            if (this.element.data('meta-key') !== undefined) {
                this.metaKey = this.element.data('meta-key');
            }
            if (this.element.data('model') !== undefined) {
                this.model = this.element.data('model');
            }
            if (this.element.data('editor-mode') !== undefined) {
                this.editorMode = this.element.data('editor-mode');
            }
            
            this.pageModel      = this.element.find(this.options.pageModel);
            this.headerModel    = this.pageModel.find('.header-content-area').children('.admin-content-builder').children('.sortable-elements-wrapper');
            this.contentModel   = this.pageModel.find('.content-area').children('.admin-content-builder').children('.sortable-elements-wrapper');
            this.sidebarModels  = this.pageModel.find('.sidebar');
            
            if (!this.element.is(':visible') && this.editorMode === 'full') {
                this.elementToTriggerVisible = this.element.parents('.gumm-will-trigger-visible:first');
                if (this.elementToTriggerVisible.length < 1) {
                    this.elementToTriggerVisible = $('#gummbase_page_builder');
                }
            } else {
                this.initialized = true;
                this.doMath();
            }
            
            this.toolbar = new $.gummPageBuilderToolbar(this);
            
            var _self = this;
            this.element.find('.template-builder-element').each(function(i, ele){
                _self._initializeElementWidgets($(ele));
                
                // if (i === 1) {
                //     $(ele).addClass('active-settings-editor');
                // }
            });
            
            this._initializeWidgets();
            this._bindListeners();
        },
        doMath: function(callback) {
            var _self = this;
            this.modelWidth  = this.contentModel.parent().width();
            // console.log(window.getComputedStyle(this.contentModel.get(0)));
            // this.modelWidth     = parseFloat(window.getComputedStyle(this.contentModel.get(0)).width);
            this.headerWidth = this.headerModel.parent().width();
            
            this.itemsMargin = this.modelWidth*(this.options.itemPercentMargin/100);
            
            this.contentModel.css({
                left: -_self.itemsMargin,
                width: this.modelWidth + _self.itemsMargin
            });
            this.contentModel.find('.template-builder-element').each(function(i, ele){
                $(ele).css({
                    marginLeft: _self.itemsMargin,
                    width: _self.spanWidth($(ele).data('span-num'))
                });
            });
            
            if (typeof callback === 'function') {
                callback.apply(this);
            }
        },
        spanWidth: function(span, position) {
            var _w = this.modelWidth;
            if (position === 'header') {
                _w = this.headerWidth;
            }
            span = parseInt(span);
            return _w*(this.options.widthsPercents[span]/100);
        },
        absolutizeElements: function() {
            var elements = this.contentModel.find('.template-builder-element');
            // get the positions before we absolutize
            elements.each(function(i, ele){
                $(ele).data('current-position', $(ele).position());
            });
            // and make them absolute
            elements.each(function(i, ele){
                $(ele).css({
                    position: 'absolute',
                    left: $(ele).data('current-position').left,
                    top: $(ele).data('current-position').top
                });
            });
            
        },
        _initializeWidgets: function() {
            var _self = this;
            
            // ==================================== //
            // Initialize sortable content elements //
            // ==================================== //
            var theSortables = [this.contentModel, this.headerModel];
            $.each(theSortables, function(i, model){
                model.sortable({
                    items: '.template-builder-element',
                    placeholder: 'draggable-placeholder',
                    cancel: '.admin-close-button, .admin-element-edit, .bluebox-element-resize',
                    activate: function(event, ui) {
                        ui.helper.data('origin-dimensions', {width: ui.helper.width(), height: ui.helper.height()});
                        ui.helper.css({
                            width: ui.helper.data('origin-dimensions').width,
                            height: ui.helper.data('origin-dimensions').height
                        });

                        $(this).parent().addClass('drag-available');
                    },
                    deactivate: function(event, ui) {
                        $(this).parent().removeClass('drag-available');
                    },
                    start: function(event, ui) {
                        var marginLeft = _self.itemsMargin;
                        var width = _self.spanWidth(12);
                        if ($(this).data('model-position') === 'header') {
                            marginLeft = 0;
                            width = _self.spanWidth(12, 'header');
                        }
                        // if element came from the toolbar
                        if (ui.item.hasClass('builder-toolbar-element')) {
                            ui.placeholder.css({
                                marginLeft: marginLeft,
                                width: width,
                                height: 120,
                                padding: 0
                            });
                        } else {
                            ui.placeholder.css({
                                marginLeft: marginLeft,
                                width: parseFloat(window.getComputedStyle(ui.item.children().get(0)).width),
                                height: parseFloat(ui.item.children().css('height'))
                            });
                            // if ($(this).data('model-position') === 'header' && ui.item.data('available-position') === 'all') {
                            //     $(this).sortable('option', 'connectWith', '.sortable-elements-content');
                            //     $(this).sortable('refresh');
                            // } else if ($(this).data('model-position') === 'content' && ui.item.data('available-position') === 'all') {
                            //     $(this).sortable('option', 'connectWith', '.sortable-elements-header');
                            //     $(this).sortable('refresh');
                            // }
                            
                            // Destroy the editor first, and initialize afer, to avoid STUPID FF NS_ERROR_UNEXPECTED: Unexpected error
                            destroyGummWpEditor(ui.item);
                        }
                    },
                    stop: function(event, ui) {
                        $(this).sortable('option', 'connectWith', false);
                    },
                    over: function(event, ui) {
                        if (ui.item.hasClass('builder-toolbar-element')) {
                            ui.helper.stop().animate({
                                // width: _self.spanWidth(12) - 80,
                                // height: 80
                            }, 250);
                        }
                    },
                    out: function(event, ui) {;
                        if (ui.item.hasClass('builder-toolbar-element') && ui.helper !== null) {
                            ui.helper.stop().animate({
                                width: ui.helper.data('origin-dimensions').width,
                                height: ui.helper.data('origin-dimensions').height
                            }, 250);
                        }
                    },
                    update: function(event, ui) {
                        if (ui.item.hasClass('builder-toolbar-element')) {
                            var width = _self.spanWidth(12);
                            if ($(this).data('model-position') === 'header') {
                                width = _self.spanWidth(12, 'header');
                            }
                            
                            var tempItemPlaceholder = $('<div />');
                            tempItemPlaceholder.append('<div class="template-builder-element"><div class="admin-builder-element"><span class="title">' + ui.item.data('element-title') + '</span></div></div>');
                            tempItemPlaceholder.
                            css({
                                float: 'left',
                                width: _self.spanWidth(12)
                            }).
                            children('.template-builder-element').css({
                                marginLeft: ($(this).data('model-position') === 'content') ? _self.itemsMargin : 0,
                                width: width,
                                opacity: .5
                            });
                            ui.item.after(tempItemPlaceholder);

                            // load the admin stuff
                            tempItemPlaceholder.load(ajaxurl, {
                                gummcontroller: 'layouts',
                                action: 'add_layout_element',
                                gummnamed: {
                                    elementId: ui.item.data('element-id'),
                                    postId: _self.postId,
                                    layoutPosition: $(this).data('model-position'),
                                    metaKey: _self.metaKey,
                                    model: _self.model
                                }
                            }, function(){
                                _self.doMath(function(){
                                    var theElement = tempItemPlaceholder.children('.template-builder-element');
                                    tempItemPlaceholder.before(theElement).remove();
                                    _self._initializeElementWidgets(theElement);
                                    
                                    
                                    initGummWpEditor(theElement, true);
                                });
                                

                            });

                            // .. and remove the unneccessary list element
                            ui.item.remove();
                        } else {
                            initGummWpEditor(ui.item);
                        }
                    }
                });
            });
            
            
            // ============================= //
            // Initialize droppable sidebars //
            // ============================= //
            this.sidebarModels.children('.admin-content-builder').droppable({
                accept: '.element-sidebar',
                activeClass: 'drag-available',
                over: function(event, ui) {
                    var currentElement = $(this).children('.admin-builder-element');
                    currentElement.css({opacity:.2});
                    if (ui.helper.data('gumm-origin-width') === undefined) {
                        ui.helper.data('gumm-origin-width', ui.helper.width());
                    }
                    if (ui.helper.data('gumm-origin-height') === undefined) {
                        ui.helper.data('gumm-origin-height', ui.helper.height());
                    }
                    ui.helper.animate({
                        width: currentElement.width(),
                        height: currentElement.height()
                    }, 150);
                },
                out: function(event, ui) {
                    var currentElement = $(this).children('.admin-builder-element');
                    ui.helper.animate({
                        width: ui.helper.data('gumm-origin-width'),
                        height: ui.helper.data('gumm-origin-height')
                    }, 150);
                    currentElement.css({opacity:''});
                },
                drop: function(event, ui) {
                    var currentElement = $(this).children('.admin-builder-element');
                    currentElement.remove();
                    
                    $(this).append('<div class="admin-builder-element" data-element-id="'
                        + ui.draggable.data('element-id')
                        + '"><div class="element-content">'
                        + ui.draggable.data('element-title')
                        + '</div></div>'
                    );
                    
                    // and store to the input if any
                    $(this).children('input').val(ui.draggable.data('element-id'));
                    _self._enableLayoutInputs();
                }
            });
        },
        _initializeElementWidgets: function(element) {
            var _self = this;
            
            // The close listener
            $(element).children().children('.admin-close-button').on('click', function(e){
                e.preventDefault();
                $(this).parent().parent().hide('fade', 150, function(){
                    $(this).remove();
                });
            });
            
            // Make it resizable if needed
            if ($(element).parent().hasClass('sortable-elements-content') && $(element).data('grid-columns') > 1) {
                var spanWidth = this.spanWidth(12/parseInt($(element).data('grid-columns')));
                var gridStep =  spanWidth + this.itemsMargin;
                        
                $(element).resizable({
                    handles: 'e',
                    // minWidth: spanWidth,
                    // maxWidth: this.spanWidth(12),
                    // grid: [gridStep, gridStep],
                    // helper: "template-builder-element ui-resizable-helper bluebox-admin",
                    start: function(event, ui) {
                        var spanWidth = _self.spanWidth(12/parseInt($(element).data('grid-columns')));
                        var gridStep =  spanWidth + _self.itemsMargin;
                        
                        $(this).resizable('option', 'grid', [gridStep, gridStep]);
                        $(this).resizable('option', 'minWidth', spanWidth-0.000000000001);
                        $(this).resizable('option', 'maxWidth', _self.spanWidth(12));
                        
                        // console.log($(this));
                        // ui.helper
                        //     .addClass('template-builder-element')
                        //     .css({
                        //         opacity: 1,
                        //         overflow: 'visible'
                        //     })
                        //     .html(ui.element.html());
                        // ui.element.css({opacity: 0});
                        var width = _self.spanWidth($(this).data('span-num'));
                        
                        ui.originalSize.width = width;
                    },
                    stop: function(event, ui) {
                        // ui.element.css({opacity: ''});
                        
                        var grid = $(this).resizable('option', 'grid');
                        var ratio = Math.ceil($(this).width() / grid[0]) / parseInt($(this).data('grid-columns'));
                        
                        $(this).data('span-num', Math.round(12*ratio));
                        
                        $(this).find('input.template-element-width-ratio').val(ratio);
                    }
                });
            }
            
            // Bind the edit button listeners
            $(element).children().children('.admin-element-edit').on('click', {gummBuilderElement: $(element)}, function(e){
                e.preventDefault();
                _self.initializeElementEditor(e.data.gummBuilderElement);
            });
            $(element).find('.template-element-settings').children('.admin-close-button').on('click', {gummBuilderElement: $(element)}, function(e){
                e.preventDefault();
                _self.destructElementEditor(e.data.gummBuilderElement);
            });
            $(element).find('.bb-window-save').on('click', {gummBuilderElement: $(element)}, function(e){
                e.preventDefault();
                _self.destructElementEditor(e.data.gummBuilderElement);
            });
        },
        initializeElementEditor: function(element) {
            this.inEditMode = true;
            
            this.contentModel.sortable('disable');
            this.headerModel.sortable('disable');
            
            this.activeEditor = element.find('.template-element-settings');
            // var theHolder = $('<div id="gummui-wrap-helper" class="bluebox-admin"><div class="template-builder-element active-settings-editor"></div></div>');
            // theHolder.children('.template-builder-element').append(this.activeEditor);
            // $('body').append(theHolder);
            this.activeEditor.css({
                opacity: 0,
                scale: 0.5
            });
            element.addClass('active-settings-editor');
            this.activeEditor.transition({
                opacity: 1,
                scale: 1
                // '-webkit-backface-visibility': 'hidden',
                // '-webkit-filter': 'blur(0)'
            }, 350, function(){
                $(this).trigger('gummVisible');
            });
            this.element.append('<div class="gumm-editing-mask"></div>');
            
            $('body').addClass('gumm-page-builder-edit-mode-on');
            
            // $('#wpwrap').css({
            //     '-webkit-filter': 'blur(2px)'
            // });
            
            // $('#wpwrap').transition({
            //     scale: .9
            // }, 350);

        },
        destructElementEditor: function(element) {
            var _self = this;
            this.inEditMode = false;
            
            this.contentModel.sortable('enable');
            this.headerModel.sortable('enable');
            
            this.activeEditor.transition({
                opacity: 0,
                scale: 1.5
            }, 350, function(){
                // element.children().append(_self.activeEditor);
                element.removeClass('active-settings-editor');
                _self.activeEditor = null;
                // $('#gummui-wrap-helper').remove();
            });
            this.element.children('.gumm-editing-mask').remove();
            
            $('body').removeClass('gumm-page-builder-edit-mode-on');
            // $('#wpwrap').transition({
            //     scale: 1
            // }, 350);
        },
        _bindListeners: function() {
            var _self = this;
            
            // The schema listeners
            $(this.options.schemaInputsContainer).find('input.gumm-layout-structure-input').on('change', function(e){
                _self.pageModel.removeClass('layout-schema-' + _self.options.sidebarSchema);
                _self.pageModel.addClass('layout-schema-' + $(this).val());
                _self.options.sidebarSchema = $(this).val();
                _self.doMath();
                _self._enableLayoutInputs();
            });
            $(window).resize(function(e){
                if (this === e.target) {
                    _self.doMath();
                }
            });
            if (this.elementToTriggerVisible !== null) {
                this.elementToTriggerVisible.on('gummVisible', function(){
                    if (_self.initialized === false) {
                        _self.initialized = true;
                        _self.doMath();
                    }
                });
            }
        },
        _enableLayoutInputs: function() {
            this.sidebarModels.find('input.sidebar-value').attr('disabled', false);
            $(this.options.schemaInputsContainer).find('input.gumm-layout-structure-input').attr('disabled', false);
        }
    }
    /* The toolbar class */
    $.gummPageBuilderToolbar = function gummPageBuilderToolbar(gummPageBuilder) {
        this.pageBuilder = gummPageBuilder;
        
        this.element = gummPageBuilder.element.find(gummPageBuilder.options.elementsTabs);
        this.carousel = this.element.find('.scroll-carousel');
        this.itemsContainers = this.carousel.children();
        
        this.items = this.carousel.find('.builder-toolbar-element');
        this.newSidebarButton = this.carousel.find('.new-sidebar');
        
        this.tabs = this.element.find('.nav-tabs').children('li');
        this.activeTab = this.tabs.filter('.active');
        this.index = this.activeTab.index();
        // this.activeItemsContainer = this.itemsContainers.eq(this.index);
        
        var _self = this;
        this.items.each(function(i, item){
            _self._bindElementListeners(item);
        });
        
        this.doMath();
        this._bindListeners();
    }
    $.gummPageBuilderToolbar.prototype = {
        animationSpeed: 200,
        transition: 'easeInOutQuart',
        animating: false,
        doMath: function() {
            this.itemWidth = this.getActiveItems().eq(0).outerWidth();
            this.itemMargin = parseInt(this.getActiveItems().eq(0).css('margin-right'));
            this.stepWidth = this.itemWidth + this.itemMargin;
        },
        prev: function() {
            if (this.animating === true) return;
            
            var container = this.getActiveItemsContainer();
            var currLeft = parseInt(container.css('left'));
            if (isNaN(currLeft)) currLeft = 0;
            var left = -(Math.abs(currLeft) - this.stepWidth);
            
            if (left > 0) return;
            
            this.animating = true;
            var _self = this;
            container.animate({
                left: left
            }, this.animationSpeed, this.transition, function() {
                _self.animating = false;
            });
        },
        next: function() {
            if (this.animating === true) return;

            var items = this.getActiveItems();
            if (!items.last().prev().is(':visible')) return;
            
            var container = this.getActiveItemsContainer();
            var currLeft = parseInt(container.css('left'));
            if (isNaN(currLeft)) currLeft = 0;
            var left = -(this.stepWidth + Math.abs(currLeft));
            
            if ((items.length - Math.abs(left)/this.stepWidth) <= 4) return;
            
            this.animating = true;
            var _self = this;
            container.animate({
                left: left
            }, this.animationSpeed, this.transition, function() {
                _self.animating = false;
            });
        },
        getActiveItemsContainer: function() {
            return this.itemsContainers.eq(this.index);
        },
        getActiveItems: function() {
            return this.getActiveItemsContainer().children();
        },
        getItems: function() {
            return this.items;
        },
        getItemById: function(id, fromActive) {
            if (fromActive === undefined) fromActive = true;
            
            var item = false;
            this.getActiveItems.each(function(i, ele){
                if ($(ele).data('element-id') === id) {
                    item = $(ele);
                    return false;
                }
            });
            
            return item;
        },
        _bindElementListeners: function(element) {
            
            // ======================================== //
            // Initialize the draggable toolbar element //
            // ======================================== //
            
            var connectToSortable = false;
            if ($(element).hasClass('element-layout-element')) {
                if ($(element).data('layout-position') === 'all') {
                    connectToSortable = '.sortable-elements-header, .sortable-elements-content';
                } else if ($(element).data('layout-position') === 'content') {
                    connectToSortable = '.sortable-elements-content';
                } else if ($(element).data('layout-position') === 'header') {
                    connectToSortable = '.sortable-elements-header';
                }
            }
            
            $(element).draggable({
                appendTo: 'body',
                helper:'clone',
                zIndex: 200,
                connectToSortable: connectToSortable,
                start: function(event, ui) {
                    $(this).css({
                        opacity: .5
                    });
                },
                stop: function(event, ui) {
                    $(this).css({
                        opacity: ''
                    });
                }
            });
            
            // =============================== //
            // Bind element specific listeners //
            // =============================== //
            
            $(element).children('.admin-close-button').on('click', function(e){
                e.preventDefault();
                if ($(this).parent().data('element-group') === 'sidebar') {
                    var $this = $(this);
                    $this.text('').addClass('icon-spin icon-spinner');
                    
                    $.ajax({
                        url: $(this).attr('href'),
                        dataType: 'json',
                        success: function(data, textStatus, XMLHttpRequest) {
                            console.log(data);
                            if (data !== true) {
                                $this.text('×').removeClass('icon-spinner icon-spin');
                                gummbase.alert('Error removing sidebar.', {type: 'error'});
                                return false;
                            } else {
                                $this.parent().remove();
                            }
                        }
                    });
                }
            });
        },
        _bindListeners: function() {
            var _self = this;
            this.tabs.on('click', function(e){
                e.preventDefault();
                if ($(this).is(_self.activeTab)) return false;
                _self.activeTab.removeClass('active');
                $(this).addClass('active');
                _self.activeTab = $(this);
                
                _self.itemsContainers.eq(_self.index).hide();
                _self.index = $(this).index();
                _self.itemsContainers.eq(_self.index).show();
                
                // Call layouts controller via ajax to store user navigation
                $.ajax({
                    url: ajaxurl,
                    data: {
                        gummcontroller: 'layouts',
                        action: 'admin_store_user_navigation',
                        gummnamed: {
                            pageBuilderActiveTab: _self.index
                        }
                    }
                });
            });
            
            this.element.find('.toolbar-prev').on('click', function(e){
                e.preventDefault();
                _self.prev();
            });
            this.element.find('.toolbar-next').on('click', function(e){
                e.preventDefault();
                _self.next();
            });
            this.newSidebarButton.on('click', function(e){
                e.preventDefault();
                
                var popup = new $.gummPopup({
                    url: ajaxurl,
                    urlData: {
                        gummcontroller: 'sidebars',
                        action: 'add'
                    },
                    width: 400,
                    height: 350,
                    onConfirm: function(gummui) {
                        var theForm = $(this).find('#gumm-sidebars-add-form');
                        if (theForm.size() > 0) {
                            $.ajax({
                                url: theForm.attr('action'),
                                type: theForm.attr('method'),
                                data: theForm.serialize(),
                                success: function(data, textStatus, XMLHttpRequest) {
                                    try{
                                        var element = $(data);
                                        _self.getActiveItemsContainer().prepend(element);
                                        _self._bindElementListeners(element);
                                    } catch(err){}
                                }
                            });
                        }
                    }
                }, null, $(this));
            });
            
        }
    }
    /* Bind as jQuery Fn */
    $.fn.gummPageBuilder = function gummPageBuilderFn(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummPageBuilder');
            if (instance) {

            } else {
                $.data(this, 'gummPageBuilder', new $.gummPageBuilder(options, callback, this));
            }
            
        });
        return this;
    }
    
    
    // $.gummFilterableContent = function gummFilterableContent(options, callback, element) {
    //     this.element = $(element);
    //     this.__construct(options, callback);
    // }
    // $.gummFilterableContent.prototype = {
    //     data: [],
    //     autoCompleteTO: null,
    //     __construct: function(options, callback) {
    //         this.filterInput = this.element.find('input.filter-input');
    //         this.valueInput = this.element.find('input.icon-value');
    //         this.items = this.element.find('li');
    //         var _self = this;
    //         this.items.each(function(i, item){
    //             _self.data.push({
    //                 selector: '.' + $(item).children('i').attr('class').replace(' ', '.'),
    //                 value: $(item).children('i').data('icon-name'),
    //                 ele: $(item)
    //             });
    //         });
    //         
    //         this.currentValue = this.valueInput.val();
    //         if (this.currentValue) {
    //             this.element.children('.gumm-filtarable-content-container').animate({
    //                 scrollTop: this.items.filter('.selected').position().top - 200
    //             }, 0);
    //         }
    //         
    //         this._bindListeners();
    //     },
    //     filter: function(val) {
    //         $.each(this.data, function(i, _d){
    //             if (_d.value.indexOf(val) === -1) {
    //                 _d.ele.hide();
    //             } else {
    //                 _d.ele.show();
    //                 _d.ele.parent().show().prev('h5').show();
    //             }
    //             if (_d.ele.siblings(':visible').length < 1) _d.ele.parent().hide().prev('h5').hide();
    //         });
    //     },
    //     _bindListeners: function() {
    //         var _self = this;
    //         this.items.on('click', function(e){
    //             var val = $(this).children('i').attr('class');
    //             _self.valueInput.val(val);
    //             _self.items.removeClass('selected');
    //             $(this).addClass('selected');
    //             _self.currentValue = val;
    //             // $(this).children('.radio-label-group').children('input').trigger('click');
    //         });
    //         this.filterInput.on('keyup', function(e){
    //             try{clearTimeout(_self.autoCompleteTO)}catch(err){};
    //             _self.autoCompleteTO = setTimeout(_self.filter($(this).val()), 200);
    //         });
    //     }
    // }
    // $.fn.gummFilterableContent = function gummFilterableContentFN(options, callback) {
    //     var returnData = null;
    //     this.each(function () {
    //         var instance = $.data(this, 'gummFilterableContent');
    //         if (instance) {
    //             if (options === 'value' || options === 'val') {
    //                 returnData = instance.currentValue;
    //             }
    //         } else {
    //             $.data(this, 'gummFilterableContent', new $.gummFilterableContent(options, callback, this));
    //         }
    //         
    //     });
    //     return returnData === null ? this : returnData;
    // }
    
    // Icons Picker
    $.gummIconsPicker = function gummIconsPicker(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummIconsPicker.prototype= {
        __construct: function(options, callback) {
            this.items = this.element.find('.gumm-select-icon');
            this.input = this.element.find('input.icon-value');
            this.current = this.items.filter('.selected');
            this.bindListeners();
        },
        bindListeners: function() {
            var _self = this;
            this.items.on('click', function(e){
                if ($(this).is(_self.current)) return;
                
                _self.current.removeClass('selected');
                $(this).addClass('selected');
                _self.current = $(this);
                _self.input.val($(this).data('icon-value'));
            });
        }
    }
    $.fn.gummIconsPicker = function gummIconsPickerFn(options, callback) {
        var returnData = null;
        this.each(function () {
            var instance = $.data(this, 'gummIconsPicker');
            if (instance) {
                if (options === 'value' || options === 'val') {
                    returnData = instance.input.val();
                }
            } else {
                $.data(this, 'gummIconsPicker', new $.gummIconsPicker(options, callback, this));
            }
            
        });
        return returnData === null ? this : returnData;
    }
    
    // Time Zones Picker
    $.gummTimeZonePicker = function gummTimeZonePicker(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummTimeZonePicker.prototype = {
        __construct: function(options, callback) {
            this.availables = this.element.find('.gumm-items-available-container');
            this.enabled = this.element.find('.gumm-items-enabled-container');
            
            this.bindListeners();
        },
        bindListeners: function() {
            var _self = this;
            this.element.on('click', function(e){
                var ele = $(e.target);
                if (ele.hasClass('add-item')) {
                    e.preventDefault();
                    var clone = ele.parent().parent('tr').clone();
                    clone.find('input').removeAttr('disabled');
                    clone.find('a.add-item').removeClass('add-item icon-plus').addClass('remove-item icon-minus');
                    _self.enabled.prepend(clone);
                } else if (ele.hasClass('remove-item')) {
                    e.preventDefault();
                    ele.parent().parent('tr').hide('fade', 500, function(){
                        $(this).remove();
                    });
                }
            });
        }
    }
    $.fn.gummTimeZonePicker = function gummTimeZonePickerFn(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummTimeZonePicker');
            if (instance) {

            } else {
                $.data(this, 'gummTimeZonePicker', new $.gummTimeZonePicker(options, callback, this));
            }
            
        });
        return this;
    }
    
    

})(window, jQuery);