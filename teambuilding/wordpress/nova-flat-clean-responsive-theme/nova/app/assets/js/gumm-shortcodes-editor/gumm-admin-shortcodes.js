// closure to avoid namespace collision
(function(){
    tinymce.create('tinymce.plugins.gumm_shortcodes_editor', {
        init : function(ed, url) {
            var _self = this;
            var insertActionsEditorsMap = {
                'gumm-editor-tabs': 'gummInsertShortcodeTabs',
                'gumm-editor-skill-bars': 'gummInsertShortcodeSkillBars',
                'gumm-editor-list': 'gummInsertShortcodeList',
                'gumm-editor-pricing_table': 'gummInsertShortcodePricingTable'
            }
            
            this.bindListeners(ed, url);
            
            ed.addButton('gumm-shortcodes-editor-button', {
                title :  'Insert Shortcodes',
                cmd : 'openGummShortcodesEditor',
                image : url + '/shortcode-button.png'
            });
            
            ed.addButton('gumm-shortcodes-list-button', {
                title :  'Set List Type',
                cmd : 'openGummShortcodesListEditor',
                image : url + '/shortcode-list-button.png',
                state: 'disabled'
            });
            
            ed.addButton('gumm-shortcodes-column-button', {
                title :  'Set Column Type',
                cmd : 'openGummShortcodesColumnsEditor',
                image : url + '/shortcode-column-button.png'
            });
            
            // ed.addButton('gumm-2p2-poker-hand', {
            //     title :  'Post poker hand text as 2p2 format',
            //     cmd : 'openGummPokerHandPaster',
            //     image : url + '/shortcode-card-button.png'
            // });
            
            // INIT BUTTON
            ed.addCommand('openGummShortcodesEditor', function() {
                
                var shortcodesEditor = new jQuery.gummPopup({
                    width: 640,
                    height: 680,
                    url: ajaxurl,
                    urlData: {gummcontroller: 'shortcodes', action: 'gumm_admin_index'},
                    addClass: 'quick-launch-popup',
                    // footerButtons: false,
                    resizable: false,
                    onOpen: function(contentElement) {
                        contentElement.find('.quickLaunch').gummQuickLaunch();
                    },
                    onConfirm: function(gummui) {
                        var theQuickLaunch = gummui.content.children().children('.quickLaunch');
                        var theActiveContent = theQuickLaunch.gummQuickLaunch('getActiveItemContent');
                        var theEditor = theActiveContent.find('.gumm-sc-editor:first');
                        
                        if (theEditor.hasClass('sc-insertdefault')) {
                            ed.execCommand('gummInsertShortcode', false, theEditor);
                        } else {
                            jQuery.each(insertActionsEditorsMap, function(editorClass, insertAction){
                                if (theEditor.hasClass(editorClass)) {
                                    ed.execCommand(insertAction, false, theEditor);
                                    return false;
                                }
                            });
                        }
                    }
                }, null, jQuery('#' + this.id + '_gumm-shortcodes-editor-button'));
            });
            
            ed.addCommand('openGummShortcodesListEditor', function() {
                
                // return;
                var ulNode = _self.getSelectionNode(ed, 'ul');
                // console.log(ulNode);
                if (jQuery(ulNode).size() < 1) {
                    ed.controlManager.get('bullist').settings.onclick();
                }
                // tinyMCE.activeEditor.controlManager.get('bullist').settings.onclick();
                var shortcodesListEditor = new jQuery.gummPopup({
                    width: 640,
                    height: 680,
                    url: ajaxurl,
                    fromIframe: true,
                    urlData: {gummcontroller: 'shortcodes', action: 'gumm_admin_index_list_types'},
                    resizable: false,
                    addClass: 'quick-launch-popup',
                    onOpen: function(contentElement) {
                        contentElement.find('.quickLaunch').gummQuickLaunch();
                    },
                    onConfirm: function(gummui) {
                        var theQuickLaunch = gummui.content.children().children('.quickLaunch');
                        var theActiveContent = theQuickLaunch.gummQuickLaunch('getActiveItemContent');
                        var theListType = theActiveContent.find('.gumm-sc-editor-value:first').val();
                        
                        var theNode = jQuery(tinyMCE.activeEditor.selection.getNode());

                        var theUl = theNode.parents('ul:last');
                        if (theUl.size() < 1 && theNode.is('ul')) {
                            theUl = theNode;
                        }
                        
                        if (jQuery(theUl).size() > 0 && theListType.length > 0) {
                            theUl.attr('class', theListType);
                        }
                    }
                }, null, jQuery('#' + this.id + '_gumm-shortcodes-list-button'));
            });
            
            ed.addCommand('openGummShortcodesColumnsEditor', function() {
                var shortcodesListEditor = new jQuery.gummPopup({
                    width: 640,
                    height: 680,
                    url: ajaxurl,
                    fromIframe: true,
                    urlData: {gummcontroller: 'shortcodes', action: 'gumm_admin_index_column_types'},
                    resizable: false,
                    addClass: 'quick-launch-popup',
                    onOpen: function(contentElement) {
                        contentElement.find('.quickLaunch').gummQuickLaunch();
                        // var theQuickLaunch = contentElement.children('.quickLaunch');
                        // console.log(theQuickLaunch);
                    },
                    onConfirm: function(gummui) {
                        var theQuickLaunch = gummui.content.children().children('.quickLaunch');
                        var theActiveContent = theQuickLaunch.gummQuickLaunch('getActiveItemContent');

                        var theEditor = jQuery(theActiveContent).find('.gumm-sc-editor');
                        
                        if (!ed.selection.getContent({format: 'text'})) {
                            ed.selection.select(ed.selection.getNode());
                            var topNode = _self.getSelectionNode(ed, ':not(body,html)', true);
                            ed.selection.select(topNode.get(0));
        
                        }
                        
                        ed.execCommand('gummInsertShortcode', false, jQuery(theActiveContent).find('.gumm-sc-editor:first'));
                    }
                }, null, jQuery('#' + this.id + '_gumm-shortcodes-column-button'));
            });
            
            ed.addCommand('openGummPokerHandPaster', function(){
                var scEditor = new jQuery.gummPopup({
                    width: 640,
                    height: 320,
                    url:ajaxurl,
                    urlData: {gummcontroller: 'shortcodes', action: 'gumm_admin_poker_hand_parser'},
                    fromIframe: true,
                    onConfirm: function(gummui) {
                        var theForm = gummui.content.find('form#gumm-poker-hand-parser-form');
                        jQuery.ajax({
                            url: theForm.attr('action'),
                            type: theForm.attr('method'),
                            data: theForm.serialize(),
                            success: function(data, textStatus, XMLHttpRequest) {
                                var _handHistory = jQuery(data).filter('.poker-hand');
                                var _error = jQuery(data).filter('.parsing-error');
                                if (_error.size() < 1) {
                                    ed.selection.setContent(_handHistory.html());
                                    var heroHand = _handHistory.find('.hero-hand');
                                    var heroHandInput = jQuery('#gummbase_post_poker_hand').find('input.poker-hand-cards');
                                    var potSizeInput = jQuery('#gummbase_post_poker_hand').find('input.poker-hand-pot-size');
                                    var heroNameInput = jQuery('#gummbase_post_poker_hand').find('input.poker-hand-hero-name');
                                    
                                    var heroName = _handHistory.data('heroname');
                                    if (!heroName) heroName = 'Anonymous';
                                    
                                    heroHandInput.val(heroHand.data('herohand'));
                                    potSizeInput.val(_handHistory.data('potsize'));
                                    heroNameInput.val(heroName);
                                } else {
                                    gummbase.alert(_error.html());
                                }
                            }
                        });
                    }
                }, null, jQuery('#' + this.id + '_gumm-2p2-poker-hand'));

            });
            
            // OTHER COMMANDS
            ed.addCommand('getGummShortcodeString', function(ui, v){
                var gummScEditor = jQuery(v);
                var scId = gummScEditor.attr('title');

                var scString = '[' + scId;
                gummScEditor.find('input.sc-editor-attr').each(function(i, ele){
                    if (jQuery(ele).attr('title') && jQuery(ele).val()) {
                        if (jQuery(ele).is('[type=checkbox]')) {
                            if (jQuery(ele).attr('checked') != 'checked') {
                                return;
                            } else {
                                jQuery(ele).val(jQuery(ele).attr('title'));
                            }

                        } else if (jQuery(ele).is('[type=radio]')) {
                            if (jQuery(ele).attr('checked') != 'checked') {
                                return;
                            }
                        }
                        scString += ' ' + jQuery(ele).attr('title') + '="' + jQuery(ele).val() + '"';
                    }
                });
                
                var scContent = scContent = ed.selection.getContent();
                var theContentInput = gummScEditor.find('.gumm-sc-editor-content');
                if (theContentInput.size() > 0) {
                    scContent = theContentInput.val();
                }
                if (gummScEditor.hasClass('sc-nocontent')) {
                    scString += ' /]';
                } else {
                    scString += " ]\n  "+ scContent + ' [/' + scId + '] ';
                }
                
                return scString;
            });
            
            ed.addCommand('gummInsertShortcode', function(ui, v){
                var scString = ed.execCommand('getGummShortcodeString', false, v);
                if (scString) {
                    ed.selection.setContent(scString);
                }
            });
            
            ed.addCommand('gummInsertShortcodeTabs', function(ui, v){
                var theEditor = jQuery(v);
                // console.log(theEditor);
                var scName = theEditor.attr('title');
                var theActiveTab = theEditor.find('.gumm-tab-active-input:checked');
                var theActiveAttr = '';
                if (theActiveTab.size() > 0) {
                    // theActiveAttr = ' active_tab="' + (theActiveTab.parent().parent().index() + 1) + '"';
                }
                var theScString = '[' + scName + theActiveAttr + '] ';
                theEditor.children('.gumm-tabs-inputs-wrapper:first').children('.active').each(function(i, wrapperEle){
                    var scChildName = jQuery(wrapperEle).attr('title');
                    if (!scChildName) scChildName = 'tab';
                        var title = jQuery(wrapperEle).find('input.gumm-tab-title').val();
                        var content = jQuery(wrapperEle).find('textarea.gumm-tab-content').val();
                        var active = jQuery(wrapperEle).find('input.gumm-tab-active');
                        // console.log(active);
                        var theActiveAttr = active.is(':checked') ? ' active ' : ' ';
                        
                        // console.log(theActiveAttr);
                        
                        theScString += '[' + scChildName + ' title="' + title + '"' + theActiveAttr + ']' + content + '[/' + scChildName + '] ';
                });
                theScString += '[/' + scName + ']';
                try {
                    ed.execCommand('mceInsertContent', true, theScString);
                } catch(err){}
            });
            ed.addCommand('gummInsertShortcodeSkillBars', function(ui, v){
                var theEditor = jQuery(v);
                // console.log(theEditor);
                var scName = theEditor.attr('title');

                var theScString = '[' + scName + '] ';
                theEditor.children('.gumm-skill-bars-inputs-wrapper:first').children('.active').each(function(i, wrapperEle){
                    var scChildName = jQuery(wrapperEle).attr('title');
                    if (!scChildName) scChildName = 'skill_bar';
                        var title = jQuery(wrapperEle).find('input.gumm-tab-title').val();
                        var skillLevel = parseInt(jQuery(wrapperEle).find('input.gumm-slider-input').val());
                        var numberFormat = jQuery(wrapperEle).find('input.gumm-tab-numberformat').val();
                        

                        theScString += '[' + scChildName + ' title="' + title + '" level="' + skillLevel + '"';
                        if (numberFormat) {
                            theScString += ' numberformat="' + numberFormat + '"';
                        }
                        theScString += ' /] ';
                });
                theScString += '[/' + scName + ']';
                try {
                    ed.execCommand('mceInsertContent', true, theScString);
                } catch(err){}
            });
            ed.addCommand('gummInsertShortcodeList', function(ui, v){
                var theEditor = jQuery(v);
                // console.log(theEditor);
                var scName = theEditor.attr('title');

                var theScString = '[' + scName + '] ';
                theEditor.children('.gumm-list-inputs-wrapper:first').children('.active').each(function(i, wrapperEle){
                    var scChildName = jQuery(wrapperEle).attr('title');
                    if (!scChildName) scChildName = 'list_item';
                        var title = jQuery(wrapperEle).find('input.gumm-tab-title').val();

                        theScString += '[' + scChildName + ']' + title + '[/' + scChildName + ']';
                });
                theScString += '[/' + scName + ']';
                try {
                    ed.execCommand('mceInsertContent', true, theScString);
                } catch(err){}
            });
            
            ed.addCommand('gummInsertShortcodePricingTable', function(ui, v){
                var theEditor = jQuery(v);
                // var scName = theEditor.attr('title');
                var columnsData = [];
                var theColumns = theEditor.find('.gumm-table-column');
                
                theColumns.each(function(i, ele){
                    var columnEle = jQuery(ele);
                    var columnTitle = columnEle.find('input.gumm-table-column-title').val();
                    if (columnTitle.length < 1) return;
                    
                    var columnData = {
                        title: columnTitle,
                        currency: columnEle.find('input.gumm-table-column-currency').val(),
                        price: columnEle.find('input.gumm-table-column-price').val(),
                        subHeading: columnEle.find('input.gumm-table-column-subheading').val(),
                        description: columnEle.find('input.gumm-table-column-description').val(),
                        buttonTitle: columnEle.find('input.gumm-table-column-button-title').val(),
                        buttonLink: columnEle.find('input.gumm-table-column-button-link').val(),
                        active: columnEle.find('input.gumm-table-column-active').is(':checked'),
                        listItems: []
                    };
                    
                    columnEle.find('.list-item').each(function(n, listItem){
                        columnData.listItems.push(jQuery(listItem).find('input.gumm-table-list-item').val());
                    });
                    
                    columnsData.push(columnData);
                });
                
                var theTableString = '<ul class="bluebox-pricing-table cols-' + columnsData.length + '">';
                
                jQuery.each(columnsData, function(i, data){
                    if (data.active) {
                        theTableString += '<li class="selected">';
                    } else {
                        theTableString += '<li>';
                    }
                    theTableString += '<div><ul>';
                    theTableString += '<li class="heading-row"><h3>' + data.title + '</h3></li>';
                    theTableString += '<li class="price-row"><div>' + data.currency + '<strong>' + data.price + '</strong>' + '<span>' + data.subHeading + '</span></div></li>';
                    if (data.description) {
                        theTableString += '<li><strong>' + data.description + '</strong></li>';
                    }
                    jQuery.each(data.listItems, function(n, listItemString){
                        theTableString += '<li>' + listItemString + '</li>';
                    });
                    
                    if (data.buttonTitle) {
                        theTableString += '<li><a class="bluebox-button extra" href="' + data.buttonLink + '">' + data.buttonTitle + '<span class="icon-chevron-right"><span>></span></span></a></li>';
                    }
                    
                    theTableString += '</ul></div></li>';
                });
                
                theTableString += '</ul>';
                
                try {
                    ed.execCommand('mceInsertContent', true, theTableString);
                } catch(err){}
            });
        },
        getSelectionNode: function(ed, selector, last) {
            if (last === undefined) var last = false;
            var theNode = jQuery(ed.selection.getNode());
            if (!last) {
                var theSelectionNode = (theNode.is(selector)) ? theNode : theNode.parents(selector).first();
            } else {
                var theSelectionNode = theNode.parents(selector).last();
                if (theSelectionNode.size() < 1) theSelectionNode = theNode;
            }
            
            return theSelectionNode;
        },
        bindListeners: function(ed, url) {
            var _self = this;
            ed.onInit.add(function() {
                ed.dom.loadCSS(url + '/shortcode-editor.css?' + new Date().getTime());
            });
            
            ed.onNodeChange.add(function(ed, cm, e) {
                // Activates the link button when the caret is placed in a anchor element
                // if (e.nodeName == 'A')
                   // cm.setActive('link', true);
                if (jQuery(e).is('ul') || jQuery(e).parents('ul:first').size() > 0) {
                    cm.setActive('gumm-shortcodes-list-button', true);
                    // cm.setDisabled('gumm-shortcodes-list-button', false);
                } else {
                    cm.setActive('gumm-shortcodes-list-button', false);
                    // cm.setDisabled('gumm-shortcodes-list-button', true);
                }
                // console.log(e);
            });
            
            // ed.onBeforeSetContent.add(function(ed, o) {
                // console.log(o.content);
                // o.content = _self.parseContentToVisualShortcodes(o.content); 
            // });
        },
        parseContentToVisualShortcodes: function(content) {
            // return content.replace(/\[(.*?)\](.*?)\[\/.*?\]/gim, function(whole, one, two){
                // console.log(whole);
                // console.log(one);
                // console.log(two);
            // });
            jQuery.ajax({
                url: ajaxurl,
                data: {gummcontroller: 'shortcodes', action: 'gumm_admin_do_shortcode', gummnamed: {content: content}},
                success: function(data, textStatus, jqXHR) {
                    // console.log(data);
                }
            })
            // console.log(gse_shortcode_er);
            
            return content;
        },
        getInfo : function() {
            return {
                longname:  'GUMM WordPressFramework Based Shortcodes Editor',
                author:    'Evgeni Dimov',
                authorurl: 'https://gummltd.com',
                infourl:   '',
                version:   '1.0'
            };
        }
    });
	
    tinymce.PluginManager.add('gumm_shortcodes_editor', tinymce.plugins.gumm_shortcodes_editor);
})();