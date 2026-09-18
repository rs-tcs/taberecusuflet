
(function (window, $, undefined) {
    $.ajaxSetup({
        data: {
            X_GUMM_REQUESTED_WITH: 'XMLHttpRequest',
        }
    });

    $.gummPopup = function gummPopup(options, callback, element) {
        if (element !== undefined) this.callerElement = $(element);
        
        this._create(options, callback);
    };
    
    $.gummPopup.settings = {
        width: 300,
        height: 200,
        appendTo: 'body',
        url: false,
        urlData: null,
        dataSelector: false,
        cloneDataElement: false,
        effect: 'pop',
        effectDuration: 250,
        effectEasing: 'easeInCubic',
        effectCloseDuration: 150,
        effectCloseEasing: 'linear',
        overlay: true,
        footerButtons: ['cancel', 'ok'],
        _headerClass: 'popup-top-bar',
        _footerClass: 'popup-bottom-bar',
        addClass: false,
        resizable: true,
        buttonOkEnabled: true,
        searchBar: false,
        searchSettings: {
            items: '.searchable'
        },
        onSearchComplete: function(){return true;},
        beforeOpen: function(){},
        onOpen: function(){},
        onBeforeClose: function(){},
        onClose: function(){},
        onContentReady: function(){},
        onConfirm: function(){},
        onCancel: function(){}
    };
    
    $.gummPopup.prototype = {
        _contentLoaded: false,
        _popupOpened: false,
        _loadCompleted: false,
        callerElement: null,
        headerElement: null,
        footerElement: null,
        contentElement: null,
        footerElement: null,
        buttonOkElement: null,
        buttonCancelelement: null,
        closeElement: null,
        _create: function(options, callback) {
            this.options = $.extend(true, {}, $.gummPopup.settings, options);

            this.element = $('<div id="gumm-popup-' + uniqid() + '" class="gumm-popup-window loading bluebox-admin"></div');
            if (this.options.addClass) {
                this.element.addClass(this.options.addClass);
            }
            this.element.css({
                width: this.options.width,
                height: this.options.height,
                opacity: 0,
                zIndex: 100
            });

            $(this.options.appendTo).append(this.element);
            
            this._addHeader();
            this._addContent();
            this._addFooter();
            
            this._bindListeners();
            
            this.element.draggable({
                handle: '.' + this.options._headerClass + ', .' + this.options._footerClass,
                cancel: '.close-link, input, .search-input-wrapper'
            });
            
            this.open();
        },
        _addHeader: function() {
            this.headerElement = $('<div class="' + this.options._headerClass + '"></div>');
            this.closeElement = $('<a class="close-link close-gumm-popup" href="#"></a>');
            
            if (this.options.searchBar === true) {
                this._addSearchBar();
            }
            
            this.headerElement.append(this.closeElement);
            this.element.append(this.headerElement);
        },
        _addSearchBar: function() {
            this.searchInput = $('<input class="span2" type="text" placeholder="filter" />');
            var searchWrapper = $('<div class="input-append search-input-wrapper"></div>');
            searchWrapper.append(this.searchInput);
            searchWrapper.append('<span class="add-on"><i class="icon-search"></i></span>');
            
            this.headerElement.append(searchWrapper);
        },
        _addFooter: function() {
            var instance = this;
            this.footerElement = $('<div class="' + this.options._footerClass + '"></div>');
            
            $.each(this.options.footerButtons, function(i, button){
                button = button.toLowerCase();
                var theButtonEle = $('<a class="btn" href="#">' + button + '</a>');
                switch(button) {
                 case 'ok':
                    theButtonEle.addClass('btn-primary');
                    if (instance.options.buttonOkEnabled === false) theButtonEle.addClass('inactive');
                    instance.buttonOkElement = theButtonEle;
                    break;
                 case 'cancel':
                    instance.buttonCancelElement = theButtonEle;
                    break;
                }
                instance.footerElement.append(theButtonEle);
            });
            this.element.append(this.footerElement);
        },
        _addContent: function() {
            this.contentElement = $('<div class="popup-content"></div>');
            this.contentInnerElement = $('<div class="popup-inner-content"></div>');
            
            this.contentElement.append(this.contentInnerElement);
            this.updateContent();
            
            this.element.append(this.contentElement);
        },
        _setContentHeight: function() {
            // Make sure we have no height on the content so to calculate accurately
            this.contentElement.outerHeight(0);
            var frameHeights = 0;
            this.element.children().each(function(i, ele){
                frameHeights += $(ele).outerHeight();
                if ($(ele).css('marginTop') != 'auto') frameHeights += parseInt($(ele).css('marginTop'));
                if ($(ele).css('marginBottom') != 'auto') frameHeights += parseInt($(ele).css('marginBottom'));
            });
            
            this.contentElement.outerHeight(this.element.innerHeight() - frameHeights);
        },
        updateContent: function(data) {
            this.options.beforeOpen.call(this, this.contentElement);
            
            var instance = this;
            var url = this.options.url;
            var urlData = this.options.urlData;
            var selector = this.options.dataSelector;
            
            if (data === undefined && url) {
                if (urlData) url += '?' + $.param(urlData);
                
                instance.contentElement.hide();
                instance.contentInnerElement.load(url, function(){
                    instance.contentElement.show();
                    instance._onContentReady();
                });
            } else if (data === undefined && selector) {
                var contentToAppend = null;
                if (this.options.cloneDataElement) {
                    contentToAppend = $(selector).clone();
                } else {
                    contentToAppend = $(selector);
                }
                // 
                instance.contentInnerElement.append(contentToAppend);
                contentToAppend.css({
                    display: 'block'
                });
                instance.contentElement.show();
                instance._onContentReady();
            } else if (data) {
                instance.contentInnerElement.html(data);
                instance._onContentReady();
            }
        },
        open: function() {
            var instance = this;
            this._setContentHeight();
            
            switch (this.options.effect) {
             case 'pop':
                if (this.callerElement !== null) {
                    this._animatePopOpen({
                        width: this.callerElement.width(),
                        height: this.callerElement.height(),
                        offset: this.callerElement.offset(),
                        position: this.callerElement.position()
                    });
                    break;
                }
             default:
                this.element.animate({opacity: 1}, this.options.effectDuration, this.options.effectEasing, function(){
                    instance._onOpen();
                });
                this.center();
            }
            
        },
        close: function() {
            var instance = this;
            switch (this.options.effect) {
             case 'pop':
                if (this.callerElement !== null) {
                    this._animatePopClose({
                        width: this.callerElement.width(),
                        height: this.callerElement.height(),
                        offset: this.callerElement.offset(),
                        position: this.callerElement.position()
                    });
                    break;
                }
             default:
                this.element.animate({opacity: 0}, this.options.effectCloseDuration, this.options.effectCloseEasing, function(){
                    instance._onClose();
                });
                this.center();
            }
        },
        _onOpen: function() {
            this.center();
            this.contentElement.css({
                overflowY: 'auto',
                overflowX: 'hidden'
            });
            this._popupOpened = true;
            if (this._contentLoaded === true) this._onLoadOpenComplete();
        },
        _onClose: function() {
            this.options.onBeforeClose.call(this, this.contentElement);
            this.element.remove();
            this._contentLoaded = false;
            this._popupOpened = false;
            this._loadCompleted = false;
            this.options.onClose.call(this);
        },
        _onContentReady: function() {
            this._contentLoaded = true;
            if (this._popupOpened === true) this._onLoadOpenComplete();
            
            this.element.removeClass('loading');
            if (this.options.searchBar) {
                this._initSearchable();
            }
            
            this.options.onContentReady.call(this, this.contentElement);
        },
        _onLoadOpenComplete: function() {
            if (this._loadCompleted === true) return;

            this._loadCompleted = true;
            
            if (this.options.resizable === true) {
                this.element.resizable({
                    alsoResize: '.popup-content'
                });
            }
            
            this.options.onOpen.call(this, this.contentElement);
        },
        _initSearchable: function() {
            var _self = this;
            
            this.searchItems = this.contentInnerElement.find(this.options.searchSettings.items);
            this.searchValues = [];
            this.searchItems.each(function(i, ele){
                _self.searchValues.push($(ele).data('search-value'));
            });
            
            this.searchInputTimeOut = null;
            var _prevVal = '';
            this.searchInput.on('keyup', function(e){
                var val = $(this).val();
                if (val.length > 0 && val !== _prevVal) {
                    if (_self.searchInputTimeOut !== null) clearTimeout(_self.searchInputTimeOut);
                    _prevVal = val;
                    $(this).next('.add-on').children('i').removeClass('icon-search').addClass('icon-spinner icon-spin');
                    _self.searchInputTimeOut = setTimeout(function(){
                        _self.searchInput.next('.add-on').children('i').addClass('icon-search').removeClass('icon-spinner icon-spin');
                        var matchedElements = [];
                        $.each(_self.searchValues, function(i, _v){
                            if (_v.toUpperCase().indexOf(val.toUpperCase()) > -1) {
                                matchedElements.push(_self.searchItems.eq(i));
                            }
                        });
                        _self.filterSearchedItems(matchedElements);
                    }, 500);
                } else if (val.length === 0) {
                    _self.searchInput.next('.add-on').children('i').addClass('icon-search').removeClass('icon-spinner icon-spin');
                    _self.nullSearchedItems();
                }

            });
            
        },
        filterSearchedItems: function(matchedElements) {
            if (this.options.onSearchComplete.apply(this, [matchedElements, false]) === false) return;
            this.searchItems.hide();
            $.each(matchedElements, function(i, ele){
                $(ele).show().removeClass('hidden');
            });
            
        },
        nullSearchedItems: function() {
            if (this.options.onSearchComplete.apply(this, [this.searchItems, true]) === false) return;
            this.searchItems.show().removeClass('hidden');
        },
        _animatePopOpen: function(initSettings) {
            var instance = this;
            
            var helper = this.element.clone();
            helper.css({opacity: 0});
            $('body').append(helper);
            this.center(helper);
            
            var animateTop = helper.position().top;
            var animateLeft = helper.position().left;
            
            helper.remove();
            
            this.element.children().hide();
            this.element.css({
                opacity: .5,
                position: 'absolute',
                top: initSettings.offset.top + (initSettings.height / 2),
                left: initSettings.offset.left + (initSettings.width / 2),
                width: 0,
                height: 0
            });
            
            this.element
            .animate({
                opacity: 1,
                width: instance.options.width,
                height: instance.options.height,
                top: animateTop,
                left: animateLeft,
                marginLeft: - (instance.options.width / 2),
                marginTop: - (instance.options.height / 2)
            }, this.options.effectDuration, this.options.effectEasing, function(){
                instance.element.children().show('fade', 50);
                instance._onOpen();
            });
        },
        _animatePopClose: function(endSettings) {
            var instance = this;
            this.element.children().hide();
            this.element.css({
                position: 'absolute',
                top: this.element.position().top + parseInt(this.element.css('marginTop')),
                left: this.element.position().left + parseInt(this.element.css('marginLeft')),
                margin: 0
            }).animate({
                opacity: .5,
                top: endSettings.offset.top + (endSettings.height / 2),
                left: endSettings.offset.left + (endSettings.width / 2),
                width: 0,
                height: 0
            }, this.options.effectCloseDuration, this.options.effectCloseEasing, function(){
                instance._onClose();
            });
        },
        disableOk: function() {
            this.options.buttonOkEnabled = false;
            this.buttonOkElement.addClass('inactive');
        },
        enableOk: function() {
            this.options.buttonOkEnabled = true;
            this.buttonOkElement.removeClass('inactive');
        },
        _bindListeners: function() {
            var instance = this;
            $(this.closeElement).bind('click', function(e){
                e.preventDefault();
                instance.close();
            });
            $(this.buttonCancelElement).bind('click', function(e){
                e.preventDefault();
                instance.close();
            });
            $(this.buttonOkElement).bind('click', function(e){
                e.preventDefault();
                if (instance.options.buttonOkEnabled === false) return false;
                if (instance.options.onConfirm.call(instance.element, {caller: instance.callerElement, content: instance.contentElement}) !== false){
                    instance.close();
                };
            });
        },
        
        center: function(ele) {
            if (ele === undefined) {
                var ele = this.element;
            }
            ele.css({
                position: 'fixed',
                top: '50%',
                left: '50%',
                marginLeft: - (ele.width() / 2),
                marginTop: - (ele.height() / 2)
            });
        },
        update: function(options){
            var _self = this;
            $.each(options, function(k, v){
                _self.k = v;
            });
        }
        
    };
    
    $.fn.gummPopupA = function _gummPopupInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummPopup');
            if (instance) {
                // update options of current instance
                instance.update(options);
            } else {
                $(this).bind('click', {gummPopupOptions: options}, function(e){
                    e.preventDefault();
                    var options = e.data.gummPopupOptions;
                    
                    if (options === undefined) options = {};
                    if (options.url === undefined) options.url = $(this).attr('href');
                    
                    $.data(this, 'gummPopup', new $.gummPopup(options, callback, this));
                });
            }
            
        });
        return this;
    }
    
    // ================= //
    // GUMM QUICK LAUNCH //
    // ================= //
    
    $.gummQuickLaunch = function gummQuickLaunch(options, callback, element) {
        this.element = $(element);
        this.__construct(options);
    }
    $.gummQuickLaunch.settings = {
        width: 'parent',
        height: 'parent',
        items: '.quickLaunchItem',
        navBar: '.quickLaunchNavBar',
        navBack: '.quickLaunchBackButton',
        viewTitle: '.quickLaunchViewTitle',
        contentPointer: '.quickLaunchContentPointer',
        contentPointerInvert: '.quickLaunchContentPointerInvert',
        itemContent: '.quickLaunchItemContent',
        itemIcon: '.quickLaunchIcon',
        itemTitle: '.quickLaunchItemTitle',
        allowItemSelect: false,
        _itemNavigateContentClass: 'ql-navigate-content',
        _itemNavigateViewClass: 'ql-navigate-view',
        _itemNavigateNone: 'ql-navigate-none',
        _qlViewClass: 'quickLaunchView',
        _qlSecondaryViewClass: 'quickLaunchSecondaryView',
        _qlViewPortClass: 'quickLaunchViewPort',
        _qlViewsContainerClass: 'quickLaunchViews',
        _qlItemActiveClass: 'ql-item-active',
        slideDuration: 250,
        slideEasing: 'easeInCirc',
        navigateDuration: 350,
        navigateEasing: 'easeInCubic'
    };
    
    $.gummQuickLaunch.prototype = {
        _state: 'view',
        _activeItem: {
            itemElement: null,
            contentElement: null
        },
        activeView: 'first',
        __construct: function(options) {
            this.options = $.extend(true, {}, $.gummQuickLaunch.settings, options);
            
            this.titleElement = this.element.find(this.options.viewTitle);
            var theTitleEleSpan = this.titleElement.children('span');
            theTitleEleSpan.css({
                position: 'absolute',
                display: 'block',
                width: theTitleEleSpan.width(),
                height: theTitleEleSpan.height(),
                left: theTitleEleSpan.position().left
            });
            
            this.navBarElement = this.element.find(this.options.navBar);
            this.navBackElement = this.element.find(this.options.navBack);
            
            this.navBackElement.css({
                display: 'block',
                opacity: 0
            });
            this.navBackElement.data('gummQuickLaunchOriginDimensions', {left: this.navBackElement.position().left});
            this.navBackElement.css({
                left: theTitleEleSpan.position().left + 10
            });
            
            this._initViewPort();
            this._bindListeners();
        },
        _initViewPort: function() {            
            this.firstViewElement = this.element.find('.' + this.options._qlViewClass).eq(0);
            this.secondaryViewElement = this._createSecondaryView();
            this.viewsElements = this.element.find('.' + this.options._qlViewClass);
            this.activeViewIndex = 0;

            var width = (this.options.width == 'parent') ? this.element.parent().width() : this.options.width;
            var height = (this.options.height == 'parent') ? this.element.parent().height() : this.options.height;
            height -= this.navBarElement.outerHeight();
            
            this.options.width = width;
            this.options.height = height;
            
            this.viewPortElement = $('<div class="' + this.options._qlViewPortClass + '"></div>');
            this.viewPortElement.css({
                position: 'relative',
                width: width,
                overflowX: 'hidden',
                overflowY: 'auto',
                height: height
            });
            this.viewsContainerElement = $('<div class="' + this.options._qlViewsContainerClass +'"></div>');
            this.viewsContainerElement.css({
                position: 'absolute',
                top: 0,
                left: 0,
                width: this.viewsElements.outerWidth() * this.viewsElements.size()
            });
            
            this.viewsContainerElement.append(this.viewsElements);
            this.viewsElements.css({
                position: 'relative',
                float: 'left',
                width: this.viewPortElement.width(),
                overflow: 'hidden'
            });
            
            this.viewPortElement.append(this.viewsContainerElement);
            this.element.append(this.viewPortElement);
        },
        _createSecondaryView: function() {
            var qlSecondaryView = this.element.children('.' + this.options._qlSecondaryViewClass).eq(0);
            if (qlSecondaryView.size() > 0) return qlSecondaryView;
            
            qlSecondaryView = $('<div class="' + this.options._qlSecondaryViewClass + ' ' + this.options._qlViewClass + '">SECOND</div>');
            qlSecondaryView.css({
                width: this.firstViewElement.width(),
                opacity: 0
            });
            this.firstViewElement.after(qlSecondaryView);
            
            return qlSecondaryView;
        },
        _getItemPart: function(itemEle, partKey) {
            var part = null;
            if ($(itemEle).data('gummQuickLaunchItemParts') === undefined) {
                var theContent = $(itemEle).parents('.quickLaunchRow:first').find(itemEle.attr('href'));
                var theIcon = $(itemEle).find(this.options.itemIcon);
                var thePointer = $(theContent).find(this.options.contentPointer);
                var thePointerInvert = $(theContent).find(this.options.contentPointerInvert);
                var theTitle = $(itemEle).find(this.options.itemTitle);
                var theContentRow = $(itemEle).parents('.quickLaunchRowContentsWrap:first');
                var siblings = $(itemEle).parents('.' + this.options._qlViewClass).eq(0).find(this.options.items).not(itemEle);
                var parts = {
                    content: theContent,
                    icon: theIcon,
                    pointer: thePointer,
                    pointerInvert: thePointerInvert,
                    title: theTitle,
                    contentRow: theContentRow,
                    siblings: siblings
                };
                $(itemEle).data('gummQuickLaunchItemParts', parts);
            } else {
                var parts = $(itemEle).data('gummQuickLaunchItemParts');
            }
            
            $.each(parts, function(k, v){
                if (k == partKey) {
                    part = v;
                    return;
                }
            });
            
            return $(part);
        },
        openItem: function(itemEle) {
            var itemEle = $(itemEle);
            if (this.options.allowItemSelect === true) {
                if (this._activeItem.itemElement) {
                    this._activeItem.itemElement.removeClass(this.options._qlItemActiveClass);
                }
                itemEle.addClass(this.options._qlItemActiveClass);
            }
            if (itemEle.hasClass(this.options._itemNavigateContentClass)) {
                this._openItemContent(itemEle);
            } else if (itemEle.hasClass(this.options._itemNavigateViewClass)) {
                this._openItemView(itemEle.attr('href'));
            } else if (itemEle.hasClass(this.options._itemNavigateNone)){
                var theContent = this._getItemPart(itemEle, 'content');
                this._activeItem.itemElement = itemEle;
                this._activeItem.contentElement = theContent;
            } else {
                // @todo - open popup or new window
            }
        },
        _openItemContent: function(itemEle) {
            var instance = this;
            var theContent = this._getItemPart(itemEle, 'content');
            
            this._state = 'active';
            
            this._activeItem.itemElement = itemEle;
            this._activeItem.contentElement = theContent;

            var thePointer = this._getItemPart(itemEle, 'pointer');
            var theIcon = this._getItemPart(itemEle, 'icon');
            if (thePointer.size() > 0 && theIcon.size() > 0) {
                thePointer.css({
                    left: theIcon.position().left + ((theIcon.outerWidth() + parseInt(theIcon.css('marginLeft')) + parseInt(theIcon.css('marginRight')) )/2) - (thePointer.width()/2) + parseInt(theContent.children('.quickLaunchContentWrap:first').css('border-top-width'))
                });
            }
            var thePointerInvert = this._getItemPart(itemEle, 'pointerInvert');
            if (thePointerInvert.size() > 0 && thePointer.size() > 0) {
                thePointerInvert.css({
                    top: 'auto',
                    left: parseInt(thePointer.css('left'))
                }).show();
            }
            this._getItemPart(itemEle, 'title').stop(true, true).hide();
            
            theContent.stop().css({
                opacity: 0,
                display: 'block'
            });
            if (theContent.data('gummQuickLaunchDimension') === undefined) {
                theContent.data('gummQuickLaunchDimension', {
                    height: theContent.outerHeight(),
                    top: theIcon.offset().top + theIcon.outerHeight() + 10 - theContent.offset().top
                });
            }
            
            // Get current height, if in animation
            var height = 0;
            if (theContent.data('gummQuickLaunchStepDimensions') !== undefined && theContent.data('gummQuickLaunchStepDimensions')) {
                height = theContent.data('gummQuickLaunchStepDimensions').height;
            }
            
            // Display and set proper height
            theContent.css({
                top: theContent.data('gummQuickLaunchDimension').top,
                height: height,
                opacity: 1,
                marginBottom: theContent.data('gummQuickLaunchDimension').top
            });
            
            // Hide sibling items
            this._getItemPart(itemEle, 'siblings').stop().animate({opacity: .4}, 150);
            
            // Animate
            theContent.animate({
                height: theContent.data('gummQuickLaunchDimension').height
            }, {
                duration: this.options.slideDuration,
                easing: this.options.slideEasing,
                step: function() {
                    // Hide the inverted pointer for the nice cutting effect
                    if ($(this).height() >= (thePointer.height() + 70) && thePointerInvert.is(':visible')) {
                        thePointerInvert.hide();
                    }
                    
                    // Scroll the viewPort if content slides below the visible area
                    var bottomScrollMargin = ($(this).offset().top + $(this).height()) - (instance.viewPortElement.offset().top + instance.viewPortElement.height());
                    if (bottomScrollMargin > 0) {
                        instance.viewPortElement.scrollTop(instance.viewPortElement.scrollTop() + bottomScrollMargin);
                    }
                }, complete: function() {
                    thePointerInvert.hide();
                    $(this).height('auto');
                    $(this).trigger('gummVisible');
                }
            });
        },
        _closeActiveItemContent: function() {
            var instance = this;
            this._state = 'view';
            
            var thePointer = this._getItemPart(this._activeItem.itemElement, 'pointer');
            var thePointerInvert = this._getItemPart(this._activeItem.itemElement, 'pointerInvert');
            
            thePointerInvert.show();

            this._activeItem.contentElement.stop().animate({
                height: 0
            }, {
                duration: this.options.slideDuration,
                easing: this.options.sldeEasing,
                step: function(){
                    // Hide the inverted pointer for the nice cutting effect
                    if ($(this).height() <= thePointerInvert.height() && thePointerInvert.is(':visible')) {
                        thePointerInvert.css({
                            top: thePointer.position().top
                        });
                    }
                    $(this).data('gummQuickLaunchStepDimensions', {height: $(this).height()});
                },
                complete: function() {
                    $(this).data('gummQuickLaunchStepDimensions', false);
                    $(this).css({display: 'none'});
                    instance._getItemPart(instance._activeItem.itemElement, 'title').show('fade', 150);
                    instance._getItemPart(instance._activeItem.itemElement, 'siblings').stop().animate({opacity: 1}, 250);
                    
                    thePointerInvert.hide();
                }

            });
        },
        _openItemView: function(url) {
            var instance = this;
            this._state = 'step';
            
            this.secondaryViewElement.load(url, function(responseText, textStatus, XMLHttpRequest){
                
                var titleElement = $(this).children('.quickLaunchViewTitle');
                $(this).attr('title', titleElement.attr('title'));
                titleElement.remove();
                
                $(this).find(instance.options.items).each(function(i, ele){
                    instance._bindItemListeners($(ele));
                });
                
                instance._navigate(instance.secondaryViewElement.index());
            });

        },
        _navigate: function(viewIndex) {
            if (viewIndex === undefined || viewIndex < 0) return;
            
            var instance = this;
            var theElement = $(this.viewsElements[viewIndex]);
            
            var currIndex = this._activeView().index();

            $(this.viewsElements).animate({opacity: 0}, this.options.navigateDuration);
            theElement.stop().css({opacity: 1});
            
            this._navigateHeader(viewIndex, this._activeView().attr('title'), theElement.attr('title'));
            
            var goToLeft = 0;
            if (viewIndex < currIndex) {
                goToLeft = theElement.position().left;
            } else {
                goToLeft = this.viewsContainerElement.position().left - theElement.position().left;
            }
            
            this.viewsContainerElement.animate({
                left: goToLeft
            }, this.options.navigateDuration, this.options.navigateEasing, function(){
                $(instance.viewsElements).height(theElement.height());
                theElement.height('auto');
                
                instance._state = 'view';
            });
            
            this._activeView(theElement);
        },
        _navigateHeader: function(viewIndex, oldTitle, newTitle) {
            var instance = this;
            
            var titleEle = this.titleElement.children('span').last();
            var titleClone = titleEle.clone();
            titleClone.text(newTitle).css({width: 'auto', opacity: 1});
            titleEle.after(titleClone);
            titleClone.width(titleClone.width());
            
            var titleEleAnimateTo = 0;
            var titleCloneInit = 0;
            
            if (viewIndex == 0) {
                this.navBackElement.stop().animate({
                    left: this.titleElement.width() / 2 - titleClone.width() / 2 + 10,
                    opacity: 0
                }, this.options.navigateDuration, this.options.navigateEasing);

                titleCloneInit = this.navBackElement.position().left;
                titleElementAnimateTo = this.titleElement.width() - titleEle.width();
                
            } else {
                this.navBackElement.children('span').text(oldTitle);
                instance.navBackElement.stop().animate({
                    left: instance.navBackElement.data('gummQuickLaunchOriginDimensions').left,
                    opacity: 1
                }, this.options.navigateDuration, this.options.navigateEasing);

                titleCloneInit = this.titleElement.width() - titleEle.width();
                titleElementAnimateTo = instance.navBackElement.data('gummQuickLaunchOriginDimensions').left;
            }

            titleClone.css({opacity: 0, left: titleCloneInit});
            titleClone.animate({
                left: this.titleElement.width() / 2 - titleClone.width() / 2,
                opacity: 1
            }, this.options.navigateDuration, this.options.navigateEasing);
            
            titleEle.animate({
                left: titleElementAnimateTo,
                opacity: 0
            }, this.options.navigateDuration, this.options.navigateEasing,  function(){
                $(this).remove();
            });
            
        },
        _activeView: function(view) {
            if (view !== undefined) {
                this.activeView = view;
            } else {
                if (this.activeView == 'first') this.activeView = this.firstViewElement;
            }
            return this.activeView;
        },
        _bindListeners: function() {
            var instance = this;
            $(this.options.items).each(function(i, ele){
                instance._bindItemListeners($(ele));
            });
            this.element.bind('click', function(e){
                var target = $(e.target);
                var cancelItem = target.parents(instance.options.itemContent).eq(0);
                if (cancelItem.size() < 1 && instance._state == 'active') {
                    e.stopPropagation();
                    instance._closeActiveItemContent();
                }
            });
            this.navBackElement.bind('click', function(e){
                instance._navigate(instance._activeView().index()-1);
            });
        },
        _bindItemListeners: function(itemEle) {
            var instance = this;
            $(itemEle).bind('click', function(e){
                e.preventDefault();
                if (instance._state == 'view') {
                    e.stopPropagation();
                    instance.openItem($(this));
                }
            });
        },
        getActiveItemContent: function() {
            return this._activeItem.contentElement;
        }
    }
    
    $.fn.gummQuickLaunch = function _gummQuickLaunchInit(options, callback) {
        var returnData = false;
        this.each(function () {
            var instance = $.data(this, 'gummQuickLaunch');
            if (instance) {
                switch (options) {
                 case 'option':
                    instance.update(options);
                    break;
                 case 'getActiveItemContent':
                    returnData = instance.getActiveItemContent();
                    break;
                }
            } else {
                // initialize new instance
                $.data(this, 'gummQuickLaunch', new $.gummQuickLaunch(options, callback, this));
            }
            
        });
        return (returnData === false) ? this : returnData;
    }
    
    
    // =============== //
    // GUMM GOOGLE MAP //
    // =============== //
    window.gummGoogleMapObjectsQueue = [];
    
    window.gummGoogleMapOnLoadCallback = function gummGoogleMapOnLoadCallback() {
        try {
            $(document).data('gummGoogleMapScriptInitialized', true);
            $.each(window.gummGoogleMapObjectsQueue, function(i, gummGmapObject){
                gummGmapObject.__initialize();
            });
            
        } catch(err) {console.log(err);}
    }
    
    $.gummGoogleMap = function gummGoogleMap(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummGoogleMap.settings = {
        width: 'auto',
        height: 'auto',
        sensor: true,
        zoom: 12,
        center: {
            lat: 42.13627,
            lng: 24.76276
        },
        marker: false,
        mapType: 'roadmap',
        controls: {
            pan: true,
            zoom: true,
            mapType: true,
            scale: true,
            streetView: true,
            overviewMap: true
        },
        scrollwheel: true,
        controlOptions: {},
        editor: false,
        useEditorInputsToInit: false,
        editorAddressInput: '.gmaps-address-input',
        editorDropPinButton: '.gmaps-drop-marker',
        editorRemovePinButton: '.gmaps-remove-marker',
        editorLatLngInput: '.gmaps-latlng-input',
        editorZoomInput: '.gmaps-zoom-input',
        editorMarkerInput: '.gmaps-marker-input',
        editorMapTypeInput: '.gmaps-maptype-input'
    }
    $.gummGoogleMap.prototype = {
        element: null,
        editorElement: null,
        editorAddressElement: null,
        editorDropPinElement: null,
        editorRemovePinElement: null,
        editorLatLngInput: null,
        _initialized: false,
        map: null,
        marker: null,
        _mapOptions: null,
        geocoder: null,
        __construct: function(options, callback) {
            this.options = $.extend(true, {}, $.gummGoogleMap.settings, options);
            
            if (this.options.width !== 'auto') this.element.width(this.options.width);
            if (this.options.height !== 'auto') this.element.height(this.options.height);
            
            if ($(document).data('gummGoogleMapScriptInit') !== true) {
                window.gummGoogleMapObjectsQueue.push(this);
                
                var sensor = (this.options.sensor === true) ? 'true' : 'false';
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "https://maps.googleapis.com/maps/api/js?sensor=" + sensor + "&callback=gummGoogleMapOnLoadCallback";
                document.body.appendChild(script);
                $(document).data('gummGoogleMapScriptInit', true);

            } else if ($(document).data('gummGoogleMapScriptInitialized') === true) {
                this.__initialize();
            } else {
                window.gummGoogleMapObjectsQueue.push(this);
            }
        },
        __initialize: function() {
            var instance = this;
            if (!this.element.is(':visible') && !this._initialized) {
                this.element.parents(':hidden').last().bind('gummVisible', function(e){
                    instance.__initialize();
                });
                
                return;
            } else if (!this.element.is(':visible') || this._initialized) {
                return;
            }
            this._initialized = true;

            this.map = new google.maps.Map(this.element.get(0), this.mapOptions());
            
            if (this.options.marker) {
                this.dropMarker(new google.maps.LatLng(parseFloat(this.options.marker.lat), parseFloat(this.options.marker.lng)));
            }
            
            if (this.options.editor !== false) {
                this.editorElement = this.element.parents(this.options.editor).eq(0);
                this.editorAddressElement = this.editorElement.find(this.options.editorAddressInput);
                this.editorDropPinElement = this.editorElement.find(this.options.editorDropPinButton);
                this.editorRemovePinElement = this.editorElement.find(this.options.editorRemovePinButton); 
                this.editorLatLngInput = this.editorElement.find(this.options.editorLatLngInput);
                this.editorZoomInput = this.editorElement.find(this.options.editorZoomInput);
                this.editorMarkerInput = this.editorElement.find(this.options.editorMarkerInput);
                this.editorMapTypeIdInput = this.editorElement.find(this.options.editorMapTypeInput);
                
                this._bindEditorListeners();
                
                if (this.options.useEditorInputsToInit === true) {
                    var zoom = this.editorZoomInput.val();
                    var center = this.editorLatLngInput.val();
                    var marker = this.editorMarkerInput.val();
                    var mapType = this.editorMapTypeIdInput.val();
                    if (zoom > 0) {
                        this.map.setZoom(parseInt(zoom));
                    }
                    if (center.length > 0) {
                        var latLngArr = center.split(',');
                        if (latLngArr.length == 2) {
                            this.map.setCenter(new google.maps.LatLng(parseFloat(latLngArr[0]), parseFloat(latLngArr[1])));
                        }
                    }
                    if (marker.length > 0) {
                        var latLngArr = marker.split(',');
                        if (latLngArr.length == 2) {
                            var latLng = new google.maps.LatLng(parseFloat(latLngArr[0]), parseFloat(latLngArr[1]));
                            this.dropMarker(latLng);
                        }
                    }
                    if (mapType.length > 0) {
                        var mapTypeId = google.maps.MapTypeId.ROADMAP;
                        switch (mapType.toLowerCase()) {
                         case 'satellite':
                            mapTypeId = google.maps.MapTypeId.SATELLITE;
                            break;
                         case 'hybrid':
                            mapTypeId = google.maps.MapTypeId.HYBRID;
                            break;
                         case 'terrain':
                            mapTypeId = google.maps.MapTypeId.TERRAIN;
                            break;
                        }
                        
                        this.map.setMapTypeId(mapTypeId);
                    }
                }
            }
            
            this._bindListeners();
        },
        mapOptions: function(options) {
            if (options !== undefined) this._mapOptions = options
            if (!this._mapOptions) {
                var mapTypeId = google.maps.MapTypeId.ROADMAP;
                switch (this.options.mapType.toLowerCase()) {
                 case 'roadmap':
                    break;
                 case 'satellite':
                    mapTypeId = google.maps.MapTypeId.SATELLITE;
                    break;
                 case 'hybrid':
                    mapTypeId = google.maps.MapTypeId.HYBRID;
                    break;
                 case 'terrain':
                    mapTypeId = google.maps.MapTypeId.TERRAIN;
                    break;
                }
                
                $.each(this.options.controlOptions, function(k, v) {
                    if (v.position !== undefined) {
                        switch (v.position) {
                         case 'TOP_LEFT':
                            v.position = google.maps.ControlPosition.TOP_LEFT;
                            break;
                         case 'LEFT_TOP':
                            v.position = google.maps.ControlPosition.LEFT_TOP;
                            break;
                        }
                    }
                });
                
                this._mapOptions = $.extend(true, {
                    zoom: this.options.zoom,
                    center: new google.maps.LatLng(this.options.center.lat, this.options.center.lng),
                    mapTypeId: mapTypeId,
                    panControl: this.options.controls.pan,
                    zoomControl: this.options.controls.zoom,
                    mapTypeControl: this.options.controls.mapType,
                    scaleControl: this.options.controls.scale,
                    streetViewControl: this.options.controls.streetView,
                    overviewMapControl: this.options.controls.overviewMap,
                    scrollwheel: this.options.editor !== false ? false : this.options.scrollwheel
                }, this.options.controlOptions);
            }

            return this._mapOptions;
        },
        centerMap: function(LatLng) {
            this.map.panTo(LatLng);
        },
        dropMarker: function(pos) {
            if (pos === undefined) var pos = this.map.getCenter();
            if (this.marker == null) {
                this.marker = new google.maps.Marker({
                    map:this.map,
                    draggable: Boolean(this.options.editor),
                    animation: google.maps.Animation.DROP,
                    position: pos
                });
                this._bindEditorMarkerListeners(this.marker);
            } else {
                this.marker.setPosition(this.map.getCenter());
            }
            
            var c = this.marker.getPosition();
            if (this.options.editor) {
                this.editorMarkerInput.val(c.lat() + ',' + c.lng());
            }
        },
        removeMarker: function(marker) {
            if (marker === undefined) marker = this.marker;

            marker.setMap(null);
            this.marker=null;
            this.editorMarkerInput.val('');
        },
        getGeocoder: function() {
            if (!this.geocoder) {
                this.geocoder = new google.maps.Geocoder();
            }
            
            return this.geocoder;
        },
        _bindListeners: function() {
            var instance = this;
            
            // if (!this.element.is(':visible') && !this._initialized) {
            // 
            // }
        },
        _bindEditorListeners: function() {
            var instance = this;
            
            this.editorAddressElement.autocomplete({
                //This bit uses the geocoder to fetch address values
                source: function (request, response) {
                    instance.getGeocoder().geocode({ 'address': request.term }, function (results, status) {
                        response($.map(results, function (item) {
                            return {
                                label: item.formatted_address,
                                value: item.formatted_address,
                                location: item.geometry.location
                            };
                        }));
                    });
                },
                select: function(event, ui) {
                    instance.centerMap(ui.item.location);
                }
            });
            
            this.editorDropPinElement.bind('click', function(e){
                e.preventDefault();
                instance.dropMarker();
            });
            
            this.editorRemovePinElement.bind('click', function(e){
                e.preventDefault();
                instance.removeMarker();
            });
            
            google.maps.event.addListener(this.map, 'center_changed', function(){
                var c = instance.map.getCenter();
                instance.editorLatLngInput.val(c.lat() + ',' + c.lng());
            });
            google.maps.event.addListener(this.map, 'zoom_changed', function(){
                instance.editorZoomInput.val(instance.map.getZoom());
            });
            google.maps.event.addListener(this.map, 'maptypeid_changed', function(){
                instance.editorMapTypeIdInput.val(instance.map.getMapTypeId());
            });
        },
        _bindEditorMarkerListeners: function(marker) {
            var instance = this;
            // google.maps.event.addListener(marker, 'dragstart', function(gl){
            //     var theTrashBin = $('<div class="trash"></div>');
            //     instance.element.append(theTrashBin);
            //     theTrashBin.css({
            //         position: 'absolute',
            //         bottom: 0,
            //         width: '100%',
            //         height: 30,
            //         background: 'rgba(0, 0, 0, .5)'
            //     });
            //     theTrashBin.bind('mouseenter', function(e){
            //         console.log(e);
            //     });
            // });
            
            google.maps.event.addListener(marker, 'position_changed', function(){
                var c = marker.getPosition();
                instance.editorMarkerInput.val(c.lat() + ',' + c.lng());
            });
            
            
        }
    }
    
    $.gummGoogleMapEditor = function gummGoogleMapEditor(map, editor) {
        this.map = map;
        this.editor = editor;
        this.__construct();
    }
    $.gummGoogleMapEditor.prototype = {
        __construct: function() {
            
        },
        createTrash: function() {
            
        },
        _bindListeners: function() {
            
        }
    }
    
    
    $.fn.gummGoogleMap = function gummGoogleMapInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummGoogleMap');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummGoogleMap', new $.gummGoogleMap(options, callback, this));
            }
            
        });
        return this;
    }
    
    // Rotatable
    
    $.gummRotatable = function gummRotatable(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummRotatable.settings = {
        button: '.rotate-button',
        step: 2,
        snap: true,
        start: function(){},
        stop: function(){}
    }
    
    $.gummRotatable.prototype = {
        state: 'default',
        centerX: null,
        centerY: null,
        radius: null,
        originX: null,
        originY: null,
        currAngle: 0,
        moveCounter: 0,
        __construct: function(options, callback) {
            this.options = $.extend(true, {}, $.gummRotatable.settings, options);
            this.button = this.element.find(this.options.button);
            

            
            this._bindListeners();
        },
        rotate: function(x, y) {
            
            var eleX = this.originX; this.element.offset().left;
            var eleY = this.originY; this.element.offset().top;
            
            // console.log(Math.acos(-0.25));
            // console.log(eleX);
            
            var c = Math.round(this.radius);
            var a = Math.round(Math.sqrt(Math.pow((eleX - x), 2) + Math.pow((y - eleY), 2)));
            // var b = m
            // console.log(a);
            // console.log(y-this.centerY);
            var tmp = this.centerX-x;
            var b = Math.round(Math.sqrt(Math.pow((y - this.centerY), 2) + Math.pow(tmp, 2)));

            // console.log(a);
            // console.log(b);
            // console.log('====');
            
            var cos = (c*c + b*b - a*a) / (2*b*c);
            // console.log(cos);
            var angle = Math.acos(cos)*180/Math.PI;
            
            // console.log(angle);
            
            // Finding out new OriginX and OriginY
            
            // var ca = Math.abs(this.centerX - eleX);
            // var cb = Math.abs(this.centerY - eleY);
            // 
            // var beta = Math.acos((c*c + ca*ca - cb*cb) / (2*c*ca))*180/Math.PI;
            // 
            // var gama = 90 - (angle + beta);
            // 
            // var xb = c*Math.sin(gama);
            // 
            // this.originX = this.centerX - xb;
            // 
            // var delta = 180 - (angle + beta) + ((180 - angle)/2);
            // var yb = c*Math.sin(delta);
            // 
            // this.originY = this.originY + yb;
            // 
            
            var quad = this.getQuadrantOfRotation(x, y);
            
            // if (quad == 3) {
            //     if ( x < (this.originX + this.element.outerWidth()) ) {
            //         angle = 360 - angle;
            //     }
            // }
            
            if (quad == 2 || quad == 3) {
                if ( x < (this.originX + this.element.outerWidth()) ) {
                    angle = 360 - angle;
                }
            } else if (quad == 4) {
                if ( x < (this.originX) ) {
                    angle = 360 - angle;
                }
            }
            
            if (angle > 180 && (quad == 3 || quad == 4)) {
                if (x < this.initX) {
                    // angle = angle - 360;
                }
                // console.log(this.element.css('rotate'));
                // console.log('y');
            }
            // var currAngle = parseInt(this.element.css('rotate'));
            
            // console.log(currAngle);
            
            // if (quad == 3 || quad == 4) {
            //     console.log('asd');
            //     if ( x < this.initX ) {
            //         angle = angle - 360;
            //     }
            // }
            
            // if ( (x > this.initX) ) {
            //     if (quad == 4 || quad == 1)
            //         this.currAngle += this.options.step;
            //     else if (quad == 2 || quad == 3)
            //         this.currAngle -= this.options.step;
            // } else {
            //     if (quad == 4 || quad == 1)
            //         this.currAngle -= this.options.step;
            //     else if (quad == 2 || quad == 3)
            //         this.currAngle += this.options.step;
            // }
            
            // var angle = this.currAngle;
            // // console.log(angle);
            if (this.options.snap == true) {
                angle = this.getSnapAngle(angle);
            }
            
            // 
            this.element.css({
                rotate: angle + 'deg'
            });
            
            this.initX = x;
            this.initY = y;
            
            this.currAngle = angle;
        },
        getQuadrantOfRotation: function(cursorX, cursorY) {
            var quad = false;
            if (cursorX > this.centerX && cursorY < this.centerY) {
                quad = 1;
            } else if (cursorX > this.centerX && cursorY > this.centerY) {
                quad = 2;
            } else if (cursorX < this.centerX && cursorY > this.centerY) {
                quad = 3;
            } else if (cursorX < this.centerX && cursorY < this.centerY) {
                quad = 4;
            }

            return quad;
        },
        getSnapAngle: function(angle) {
            if ( (355 < angle && 365 > angle) ) {
                angle = this.currAngle = 0;
            } else if (-10 < angle && 5 > angle) angle = 0;
            else if (20 < angle && 30 > angle) angle = 25;
            else if (40 < angle && 50 > angle) angle = 45;
            else if (80 < angle && 100 > angle) angle = 90;
            else if (170 < angle && 190 > angle) angle = 180;
            
            return angle;
        },
        stop: function() {
            var deg = this.currAngle;
            if (this.options.snap) deg = this.getSnapAngle(deg);
            this.options.stop.call(this, deg);
        },
        _bindListeners: function() {
            var _self = this;
            this.button.click(function(e){
                e.preventDefault();
                // if (_self.state === 'default') {
                //     
                //     e.stopPropagation();
                //     _self.state = 'resize';
                // }
            });
            this.button.bind('mouseup', function(e){
                if (_self.state === 'resize') {
                    _self.state = 'default';
                }
            });
            this.button.bind('mousedown', function(e){
                // _self.element.offset({left: 0, top: 0});
                e.preventDefault();
                if (_self.state === 'default') {
                    
                    _self.options.start.call(_self.element);
                    
                    e.stopPropagation();
                    _self.state = 'resize';
                        
                    _self.initX = e.pageX;
                    _self.initY = e.pageY;
                    
                    if (_self.originX === null)
                        // _self.originX = _self.initX;
                        _self.originX = _self.element.offset().left;
                    if (_self.originY === null)
                        // _self.originY = _self.initY;
                        _self.originY = _self.element.offset().top;
                    
                    if (!_self.centerX || !_self.centerY) {
                        // var currRotation = _self.element.css('rotate');
                        // _self.element.css({rotate: 0});
                        
                        _self.centerX = _self.element.offset().left + (_self.element.width() / 2);
                        _self.centerY = _self.element.offset().top + (_self.element.height() / 2);
                        
                        // _self.element.css({rotate: currRotation});
                    }
                        

                    _self.radius = (Math.sqrt(Math.pow(_self.element.width(), 2) + Math.pow(_self.element.height(), 2))) / 2;

                    
                }
            });
            $('body').mousemove(function(e){
                 // || _self.originX === null || _self.originY === null
                if (_self.state !== 'resize') return;
                
                _self.rotate(e.pageX, e.pageY);
            });
            $('body').mouseup(function(e){
                if (_self.state === 'resize') {
                    _self.state = 'default';
                    _self.stop();
                }
            });
            
        }
    }
    
    $.fn.rotatable = function gummRotatableInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummRotatable');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummRotatable', new $.gummRotatable(options, callback, this));
            }
            
        });
        return this;
    }

    // GUMM TABS
    $.gummTabs = function gummTabs(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    $.gummTabs.settings = {
        titlesContainer: '.nav-tabs',
        titleItems: '.tab',
        contentsContainer: '.tab-content',
        contentItems: '.tab-pane',
        activeClass: 'active',
        openOn: 'click'
    }
    $.gummTabs.prototype = {
        pointerItem: null,
        __construct: function(options, callback) {
            var _self = this;
            
            this.options = $.extend(true, {}, $.gummTabs.settings, options);
            this.titlesContainer = this.element.children(this.options.titlesContainer);
            this.titleItems = this.titlesContainer.children(this.options.titleItems);
            this.contentsContainer = this.element.children(this.options.contentsContainer);
            this.contentItems = this.contentsContainer.children(this.options.contentItems);
            
            this.activeTabTitle = this.titleItems.filter('.' + this.options.activeClass);
            if (this.activeTabTitle.size() < 1) {
                this.activeTabTitle = this.titleItems.filter(':first');
            }

            this.openTab(this.activeTabTitle.index());
            
            if (this.options.openOn == 'hover') {
                this.pointerItem = this.titleItems.filter(':not(.' + this.options.activeClass + ')').eq(0).clone();
                this.pointerItem.addClass(this.options.activeClass);
                this.pointerItem.css({display: 'none', position: 'absolute'});
                this.pointerItem.children().text('');
                // console.log(this.pointerItem);
                this.titlesContainer.append(this.pointerItem);
            }
            
            this._bindListeners();
        },
        openTab: function(index) {
            var theTabTitle = this.titleItems.eq(index);//filter('eq:(' + index + ')');
            var theTabContents = this.contentItems.eq(index);//filter('eq:(' + index + ')');
            
            if (theTabTitle.index() == this.activeTabTitle.index()) return;
            
            if (this.options.openOn == 'hover' && this.pointerItem) {
                var thePointer = this.pointerItem;
                var thePointerLink = thePointer.children('a');
                
                var theDestLink = theTabTitle.children('a');
                thePointer.css({display: 'block'});
                // thePointer.css
            } else {
                this.closeTab(this.activeTabTitle.index());

                theTabTitle.addClass(this.options.activeClass);
                theTabContents.show('fade');
            }
            
            this.activeTabTitle = theTabTitle;
        },
        openTabHover: function(index) {
            
        },
        closeTab: function(index) {
            var theTabTitle = this.titleItems.eq(index);//filter('eq:(' + index + ')');
            var theTabContents = this.contentItems.eq(index);//filter('eq:(' + index + ')');
            
            theTabTitle.removeClass(this.options.activeClass);
            theTabContents.hide('fade');
        },
        getTab: function(tabIndex) {
            return $(this.titleItems).eq(index);//filter(':eq(' + index + ')'));
        },
        getContentForTab: function(tab) {
            var index = tab;
            if (typeof(tab) != 'number') {
                index = $(tab).index();
            }
            return $(this.contentItems).eq(index);//filter(':eq(' + index + ')'));
        },
        _bindListeners: function() {
            var _self = this;
            this.titleItems.bind('click', function(e){
                e.preventDefault();
                _self.openTab($(this).index());
            });
            if (this.options.openOn == 'hover') {
                this.titleItems.bind('mouseenter', function(e){
                    _self.openTab($(this).index());
                });
            }
        }
    }
    
    $.fn.gummTabs = function initGummTabs(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummTabs');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummTabs', new $.gummTabs(options, callback, this));
            }
            
        });
        return this;
    }

    // GUMM ACCORDION
    $.gummAccordion = function gummAccordion(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummAccordion.settings = {
        items: 'li',
        titles: 'h3',
        contents: '.accordion-content',
        activeClass: 'ui-state-active',
        defaultClass: 'ui-state-default',
        speed: 500,
        easingOpen: 'easeInOutExpo',
        easingClose: 'easeInOutExpo'
    }
    
    $.gummAccordion.prototype = {
        items: null,
        titleElements: null,
        contentElements: null,
        activeItem: null,
        activeIndex: null,
        animating: false,
        initialized: false,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummAccordion.settings, options);
            
            this.titleElements = this.element.find(this.options.titles);
            this.contentElements = this.element.find(this.options.contents);

            this._initialize();
            this._bindListeners();
        },
        _initialize: function() {
            var _self = this;
            
            if (this.initialized === true) {
                return false;
            }
            if (this.element.is(':visible')) {
                this.initialized = true;
            }
            this.titleElements.each(function(i, ele){
                var elementContent = $(ele).next();
                elementContent.css('height', '');
                elementContent.data('originHeight', $(elementContent).outerHeight());
                elementContent.css({overflow: 'hidden'});
                if (!$(ele).hasClass(_self.options.activeClass)) {
                    elementContent.css({display: 'block', height: 0});
                } else {
                    _self.activeItem = $(ele);
                }
            });
        },
        _open: function(titleEle) {
            if (this.animating === true) return;
            if (!this.initialized) this._initialize();
            
            this.animating = true;
            var elementContent = titleEle.next();
            
            var _self = this;
            if (titleEle.hasClass(this.options.activeClass)) {
                elementContent.animate({
                    height: 0
                }, this.options.speed, this.options.easingOpen, function(){
                    _self.animating = false;
                });
                titleEle.removeClass(this.options.activeClass);
                titleEle.children('.accordion-button').removeClass('icon-minus').addClass('icon-plus');
                _self.activeItem = null;
            } else {
                if (_self.activeItem) {
                    _self.activeItem.next().animate({
                        height: 0
                    }, this.options.speed, this.options.easingClose);
                    _self.activeItem.removeClass(_self.options.activeClass).children('.accordion-button').removeClass('icon-minus').addClass('icon-plus');
                }
                
                titleEle.addClass(_self.options.activeClass).children('.accordion-button').removeClass('icon-plus').addClass('icon-minus');
                elementContent.animate({
                    height: elementContent.data('originHeight')
                }, this.options.speed, this.options.easingOpen, function(){
                    _self.animating = false;
                    _self.activeItem = titleEle;
                });
            }
        },
        _bindListeners: function(ele) {
            var _self = this;
            this.titleElements.on('click', function(e){
                e.preventDefault();
                _self._open($(this));
            });
        }
    }
    
    $.fn.gummAccordion = function _gummAccordionInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummAccordion');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummAccordion', new $.gummAccordion(options, callback, this));
            }
            
        });
        return this;
    }
    
    
    /* GUMM SWITCH*/
    $.gummSwitch = function gummSwitch(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummSwitch.settings = {
        on: true,
        speed: 150,
        easing: 'linear',
        backgroundAnimateTo: '-43px 0',
        change: function(){}
    }
    
    $.gummSwitch.prototype = {
        tabs: false,
        tabOn: false,
        tabOff: false,
        __construct: function(options, callback) {
            this.options = $.extend({}, $.gummSwitch.settings, options);
            this.on = this.options.on;
            
            this._bindListeners();
        },
        switchOn: function() {
            this.on = true;
            this.element.stop().animate({
                backgroundPosition: '0 0'
            }, this.options.speed, this.options.easing);
        },
        switchOff: function() {
            this.on = false;
            this.element.stop().animate({
                backgroundPosition: this.options.backgroundAnimateTo
            }, this.options.speed, this.options.easing);
        },
        _bindListeners: function() {
            var instance = this;
            this.element.bind('click', function(e){
                e.preventDefault();
                if (instance.on) {
                    instance.switchOff();
                } else {
                    instance.switchOn();
                }
                instance.options.change.apply(instance.element, [instance.on]);
            });
        }

    }
    
    $.fn.gummSwitch = function _gummSwitchInit(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummSwitch');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummSwitch', new $.gummSwitch(options, callback, this));
            }
            
        });
        return this;
    }
    
    // GUMM TWITTER FLIP
    $.gummTwitterFlip = function gummTwitterFlip(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummTwitterFlip.settings = {
        container: '.roki-tweet-content',
        items: 'div',
        start: 0
    }
    
    $.gummTwitterFlip.prototype = {
        currentIndex: 0,
        items: null,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummTwitterFlip.settings, options);
            this.containerElement = this.element.find(this.options.container);
                        
            // this.sourceUrl = this.element.data('twtter-source-url');
            // this.load();
            
            this.element.removeClass('loading');
            this.items = _self.containerElement.children(_self.options.items);
            this.setupOrder();
            this._bindListeners();
        },
        load: function() {
            var _self = this;
            this.containerElement.load(this.sourceUrl, function(){
                _self.element.removeClass('loading');
                _self.items = _self.containerElement.children(_self.options.items);
                _self.setupOrder();
                _self._bindListeners();
            });
        },
        setupOrder: function() {
            this.active = this.items.eq(this.currentIndex);
            var nextIndex = (this.currentIndex == (this.items.length - 1)) ? 0 : this.currentIndex + 1;

            this.next = this.items.eq(nextIndex);
            this.next.addClass('tocome');
        },
        _bindListeners: function(ele) {
            var _self = this;
            this.element.on('click', function(e){
                return;
                // _self.active.removeClass('active').addClass('go animating');
                // _self.next.removeClass('inactive').addClass('active animating');
                _self.next.addClass('come animating');
                _self.active.addClass('go animating');
                
                _self.containerElement.css({
                    height: _self.active.height()
                });
                _self.containerElement.animate({
                    height: _self.next.height()
                }, 300, function(){
                    $(this).css({height: 'auto'});
                });
                _self.active.on('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd', function(e){
                    _self.active.unbind('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd');
                    _self.active.unbind('remoooveeeYAAA');
                // setTimeout(function(){
                    _self.active.removeClass('go active animating').addClass('inactive');
                    _self.next.removeClass('tocome come inactive animating').addClass('active');
                    
                    _self.currentIndex = _self.next.index();
                    _self.setupOrder(true);
                // }, 3000);

                });
            });
            
            // this.items.on('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd', function(e){
            //     _self.active.removeClass('go active animating').addClass('inactive');
            //     _self.next.removeClass('tocome come inactive animating').addClass('active');
            //     
            //     _self.currentIndex = _self.next.index();
            //     _self.setupOrder(true);
            // });
        }
    }
    
    $.fn.gummTwitterFlip = function gummTwitterFlipFn(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummTwitterFlip');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummTwitterFlip', new $.gummTwitterFlip(options, callback, this));
            }
            
        });
        return this;
    }
    
    // ===================== //
    // Gumm Magnifying Glass //
    // ===================== //
    $.gummMagnifyingGlass = function(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummMagnifyingGlass.settings = {
        width: 200,
        height: 200,
        overflowElements: ''
    }
    
    $.gummMagnifyingGlass.prototype = {
        largeUrl: null,
        glass: null,
        originWidth: null,
        originHeight: null,
        nativeWidth: null,
        nativeHeight: null,
        originTop: null,
        originLeft: null,
        initialized: false,
        smallLoaded: false,
        largeLoaded: false,
        __construct: function(options, callback) {
            var _self = this;
            this.options = $.extend(true, {}, $.gummMagnifyingGlass.settings, options);
            this.img = this.element.children('img');
            this.largeUrl = this.element.attr('href');
            
            this.overflowElements = $(this.options.overflowElements);
            
            var smallImgHelper = new Image();
            smallImgHelper.src = this.img.attr('src');
            $(smallImgHelper).onImagesLoaded(function(_img){
                this.originWidth = this.img.width();
                this.originHeight = this.img.height();
                
                this.originTop = this.img.offset().top;
                this.originLeft = this.img.offset().left;
                
                this.smallLoaded = true;
                if (this.largeLoaded === true && this.initialized === false) {
                    this.initialize();
                }
            }, this);
            
            var imgHelper = new Image();
            imgHelper.src = this.largeUrl;
            $(imgHelper).onImagesLoaded(function(_img){
                this.nativeWidth = _img.width;
                this.nativeHeight = _img.height;
                
                this.largeLoaded = true;
                if (this.smallLoaded === true && this.initialized === false) {
                    this.initialize();
                }
            }, this);
        },
        initialize: function() {
            if (this.initialized) return;
            this.initialized = true;
            
            this.element.addClass('magnifying-glass-helper');
            this.element.css({
                position: 'relative',
                display: 'block'
            });
            
            this.glass = $('<div class="magnifying-glass"></div>');
            this.glass.css({
                position: 'absolute',
                display: 'none',
                zIndex: 10,
                overflow: 'visible',
                width: this.options.width,
                height: this.options.height,
                backgroundImage: "url('" + this.largeUrl + "')",
                backgroundRepeat: 'no-repeat',
                webkitBackfaceVisibility: 'hidden'
            });
            this.element.append(this.glass);
            
            this.bindListeners();
        },
        bindListeners: function() {
            var _self = this;
            this.element.mousemove(function(e){
                var mx = e.pageX - _self.originLeft;
                var my = e.pageY - _self.originTop;
                
                if(mx < _self.originWidth && my < _self.originHeight && mx > 0 && my > 0) {
                    if (!_self.glass.is(':visible')) {
                        _self.overflowElements.css({overflow: 'hidden'});
                        _self.glass.fadeIn(100);
                    }
                    var rx = Math.round(mx/_self.originWidth*_self.nativeWidth - _self.options.width/2)*-1;
                    var ry = Math.round(my/_self.originHeight*_self.nativeHeight - _self.options.height/2)*-1;
                    var bgp = rx + "px " + ry + "px";

                    _self.glass.css({
                        backgroundPosition: bgp
                    }).animate({
                        top: my - (_self.options.width / 2),
                        left: mx - (_self.options.height / 2)
                    }, 0);
                    
                } else {
                    _self.overflowElements.css({overflow: 'hidden'});
                    _self.glass.fadeOut(100);
                }
            });
            this.element.mouseleave(function(e){
                _self.overflowElements.css({overflow: 'hidden'});
                _self.glass.fadeOut(100);
            });
            this.element.on('click', function(e){
                e.preventDefault();
            });
        }
    }
    
    $.fn.gummMagnifyingGlass = function initGummMagnifyingGlass(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummSlidingDescription');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummMagnifyingGlass', new $.gummMagnifyingGlass(options, callback, this));
            }
            
        });
        return this;
    }
    
    // ================= //
    // GUMM AUTOCOMPLETE //
    // ================= //
    
    $.gummAutocomplete = function gummAutocomplete(options, callback, element) {
        this.element = $(element);
        this.__construct(options, callback);
    }
    
    $.gummAutocomplete.settings = {
        minLength: 1,
        timeout: 500,
        icon: '.searchform-icon',
        iconSearchClass: 'icon-spinner icon-spin',
        iconDefaultClass: 'icon-search',
        iconCloseClass: 'icon-remove',
        beforeFind: function(){}
    }
    
    $.gummAutocomplete.prototype = {
        state: 'default',
        length: 0,
        val: '',
        lastVal: '',
        searchTimeOut: null,
        popover: null,
        __construct: function(options, callback) {
            this.options = $.extend(true, {}, $.gummAutocomplete.settings, options);
            
            this.form = this.element.parent('form');
            // console.log(this.form);
            
            this.icon = this.form.children(this.options.icon);
            // console.log(this.icon);
            this.ajaxUrl = this.form.attr('action');
            
            this.createPopover();
            this.bindListeners();
        },
        createPopover: function() {
            this.element.popover({
                trigger: 'manual',
                placement: 'bottom'
            });
            
        },
        getPopoverContent: function() {
            return $(this.element.data('popover').$tip).find('.popover-content');
        },
        getPopoverTitle: function() {
            return $(this.element.data('popover').$tip).find('.popover-title');
        },
        setPopoverTitle: function(title) {
            this.element.attr('data-original-title', '<a href="' + this.ajaxUrl + '?s=' + this.val + '">' + this.element.data('view-all-title') + '</a>');
            this.getPopoverTitle().html(title);
        },
        setPopoverContent: function(content) {
            this.getPopoverContent().html(content);
        },
        find: function() {
            var _self = this;
            
            this.clearTimeOut();
            if (this.length >= this.options.minLength) {
                if (this.state === 'default') {
                    this.options.beforeFind.apply(this, [this.val]);
                    this.icon
                        .removeClass(this.options.iconDefaultClass)
                        .removeClass(this.options.iconCloseClass)
                        .addClass(this.options.iconSearchClass);
                }
                
                this.setPopoverTitle('<a href="' + this.ajaxUrl + '?s=' + this.val + '">' + this.element.data('view-all-title') + '</a>');
                
                this.state = 'find';
                this.lastVal = this.val;
                this.searchTimeOut = setTimeout(function(){
                    _self.getResults();
                }, this.options.timeout);
            } else {
                this.end();
            }
        },
        end: function() {
            this.clearTimeOut();
            this.state = 'default';
            if (this.length >= this.options.minLength) {
                this.icon
                    .removeClass(this.options.iconSearchClass)
                    .removeClass(this.options.iconDefaultClass)
                    .addClass(this.options.iconCloseClass);
            } else {

                this.icon
                    .removeClass(this.options.iconSearchClass)
                    .removeClass(this.options.iconCloseClass)
                    .addClass(this.options.iconDefaultClass);
                this.element.popover('hide');
            }
        },
        getResults: function() {
            var _self = this;
            $.ajax({
                url: this.ajaxUrl,
                data: {
                    gummcontroller: 'posts',
                    action: 'search',
                    gummparams: [this.val]
                },
                success: function(data, textStatus, XMLHttpRequest) {
                    if (!$(_self.element.data('popover').$tip).is(':visible')) {
                        _self.showPopOver();
                    }
                    _self.setPopoverContent(data);
                    _self.end();
                }
            })
            
        },
        showPopOver: function() {
            var theTip = $(this.element.data('popover').$tip);
            // theTip.outerWidth(this.element.outerWidth());
            theTip.css({
                width: this.element.outerWidth()
            });
            this.element.popover('show');
            $(this.element.data('popover').$tip).addClass('gumm-autocomplete-wrapper');
        },
        clearTimeOut: function() {
            try {
                clearTimeout(this.searchTimeOut);
            } catch(err){}
        },
        bindListeners: function() {
            var _self = this;
            this.element.on('keyup', function(e){
                _self.val = $(this).val();          
                _self.length = _self.val.length;
                
                if (_self.length === 0) {
                    _self.end();
                } else if (_self.val !== _self.lastVal) {
                    _self.find();
                }
            });
            this.element.on('blur', function(e){
                _self.element.popover('hide');
            });
            this.element.on('focus', function(e){
                if (_self.length === 0) {
                    _self.end();
                } else {
                    if (_self.val !== _self.lastVal) {
                        _self.find();                        
                    }
                }
            });
            this.icon.on('click', function(e){
                if ($(this).hasClass(_self.options.iconCloseClass)) {
                    _self.val = '';
                    _self.length = 0;
                    _self.element.val('').trigger('blur');
                    _self.end();
                } else if ($(this).hasClass(_self.options.iconDefaultClass)) {
                    _self.element.focus();
                }
            });
        }
    }
    
    $.fn.gummAutocomplete = function gummAutocompleteFn(options, callback) {
        this.each(function () {
            var instance = $.data(this, 'gummAutocomplete');
            if (instance) {
                // update options of current instance
                // instance.update(options);
            } else {
                $.data(this, 'gummAutocomplete', new $.gummAutocomplete(options, callback, this));
            }
            
        });
        return this;
    }
    
    
    
})(window, jQuery);

jQuery(function($) {
    
    // ========================= //
    // RELATED GENERIC LISTENERS //
    // ========================= //
    
    
    $(document).ready(function(){
        
        // CLOSE ACTIVE POPUPS
        // $('body').bind('click', function(e){
        //     var theEle = $(e.target);
        //     if (theEle.parents('.close-on-body-click').size() == 0 && !theEle.hasClass('close-on-body-click')) {
        //         $('.close-on-body-click').hide('fade', 100, function(){
        //             $('.close-on-body-click.remove-on-body-click').remove();
        //         });
        //     }
        // });
        
        // CLOSE & REMOVE TRIGGERS
        $('.close-parent').live('click', function(e){
            e.preventDefault();
            var $this = $(this);
            var classes = $this.attr('class');
            var theEle = $this.parent();
            var parentNum = /parent\-(\d+)/g.exec(classes);
            if (parentNum !== null) {
                for (i=1; i<parseInt(parentNum[1]); i++) {
                    theEle = theEle.parent();
                }
            }
            theEle.hide('fade', 150, function(){
                if ($this.hasClass('remove-on-close')) theEle.remove();
            });
        });
        
    });
    
    // ======================================== //
    // GUMMBASE FRAMEWORK CORE JS FUNCTIONALITY //
    // ======================================== //
    
    gummbase = {
        name: 'Gummbase',
        popup: {
            active: false,
            settings: {
                css: {
                    width: 600,
                     height: 538,
                     zIndex: 100,
                     display: 'none',
                     flash: false
                },
                classes: {
                    popupWindow: 'gumm-popup-window',
                    popupContent: 'popup-content',
                    popupTop: 'popup-top-bar',
                    popupBottom: 'popup-bottom-bar'
                },
                onOkAction: 'close',
                removeOnClose: false,
                appendTo: $('body')
            },
            create: function(options) {
                var settings = $.extend(true, {}, gummbase.popup.settings, options);
                
                var thePopup = $('<div id="gumm-popup-' + uniqid() + '" class="' + settings.classes.popupWindow + '"></div>');
                
                var thePopupClose = $('<a class="close-link close-parent parent-2" href="#"></a>');
                if (settings.removeOnClose === true) thePopupClose.addClass('remove-on-close');
                var thePopupTop = $('<div class="' + settings.classes.popupTop + '"></div>');
                thePopupTop.append(thePopupClose);
                
                var thePopupContent = $('<div class="' + settings.classes.popupContent + '"></div>');
                var thePopupBottom = $('<div class="' + settings.classes.popupBottom + '"></div>');
                
                var thePopupCancelButton = $('<a class="link-button cancel" href="#"><span>Cancel</span></a>');
                if (settings.removeOnClose === true) thePopupCancelButton.addClass('remove-on-close');
                var thePopupOkButton = $('<a class="link-button save popup-action-' + settings.onOkAction + '" href="#"><span>Ok</span></a>');
                if (settings.removeOnClose === true) thePopupOkButton.addClass('remove-on-close');
                thePopupBottom.append(thePopupCancelButton);
                thePopupBottom.append(thePopupOkButton);
                
                thePopup.append(thePopupTop, thePopupContent, thePopupBottom);
    
                settings.appendTo.append(thePopup);

                thePopup.addClass('close-on-body-click').css(settings.css);
                
                var theAddedHeightTopBar = parseInt(thePopupTop.css('margin-top')) + parseInt(thePopupTop.css('margin-bottom')) + parseInt(thePopupTop.css('padding-top')) + parseInt(thePopupTop.css('padding-bottom'));
                var theAddedHeightBottomBar = parseInt(thePopupBottom.css('margin-top')) + parseInt(thePopupBottom.css('margin-bottom')) + parseInt(thePopupBottom.css('padding-top')) + parseInt(thePopupBottom.css('padding-bottom'));
                var theAddedHeight = theAddedHeightTopBar + theAddedHeightBottomBar + parseInt(thePopup.css('padding-top')) + parseInt(thePopup.css('padding-bottom'));
                theAddedHeight = theAddedHeight + 10;
                
                var contentHeight = thePopup.height() - (thePopupTop.outerHeight() + thePopupBottom.outerHeight() + theAddedHeight);

                thePopup.children('.popup-content').css({
                    height: contentHeight,
                    marginTop: 55,
                    overflowY: 'scroll'
                });

                thePopup.center();
                thePopup.show('fade', 150);

                thePopup.draggable({
                    handle: '.popup-top-bar'
                });
                
                gummbase.popup.setActive(thePopup);
            },
            open: function(triggerEle) {
                var popupSettings = $.extend(true, {}, this.settings);
                if ($(triggerEle).hasClass('popup-remove-on-close')) popupSettings.removeOnClose = true;
                
                var boundPopup = $(triggerEle).data('boundPopup');
                if (boundPopup !== undefined && popupSettings.removeOnClose === false) {
                    gummbase.popup.setActive(boundPopup);
                    boundPopup.show('fade', 100, function(){
                        gummbase.popup.centerToSelection();
                    });
                    return;
                }
                
                var classes = $(triggerEle).attr('class');
                var popupHeight = /popup-height\-(\d+)/g.exec(classes);
                if (popupHeight !== null) popupSettings.css.height = parseInt(popupHeight[1]);
                var popupWidth = /popup-width\-(\d+)/g.exec(classes);
                if (popupWidth !== null) popupSettings.css.width = parseInt(popupWidth[1]);
                var onOkAction = /popup-onok\-(\w+)/g.exec(classes);
                if (onOkAction !== null) popupSettings.onOkAction = onOkAction[1];

                var theSrc = triggerEle.attr('href');
                if (theSrc.indexOf('#') > -1) {
                    popupSettings.appendTo = triggerEle.parent();
                    
                    gummbase.popup.create(popupSettings);
                    gummbase.popup.getActive().data('triggeredBy', triggerEle);

                    var thePopupContent = gummbase.popup.getActive().children('.' + gummbase.popup.settings.classes.popupContent);
                    thePopupContent.append($(theSrc));

                    $(theSrc).show();
                    gummbase.popup.centerToSelection();
                    
                    $(triggerEle).data('boundPopup', gummbase.popup.getActive());
                    
                } else {
                    $.ajax({
                        url: triggerEle.attr('href'),
                        beforeSend: function (jqXHR, settings) {
                            gummbase.popup.create(popupSettings);
                            gummbase.popup.getActive().data('triggeredBy', triggerEle);
                        },
                        success: function(data, textStatus, XMLHttpRequest) {
                            gummbase.popup.getActive().children('.' + gummbase.popup.settings.classes.popupContent).append(data);
                            gummbase.popup.centerToSelection();
                            $(triggerEle).data('boundPopup', gummbase.popup.getActive());
                        }
                    });
                }
            },
            close: function() {
            
            },
            centerToSelection: function() {
                var thePopup = gummbase.popup.getActive();
                var selectedOption = thePopup.find('.current-option');
                if (selectedOption.length > 0) {
                    var thePopupContent = thePopup.children('.' + gummbase.popup.settings.classes.popupContent);
                    thePopupContent.animate({
                       scrollTop: ((selectedOption.position().top + selectedOption.height()/2) - gummbase.popup.settings.css.width/2)
                    }, 400);
                }
            },
            setActive: function(ele) {
                gummbase.popup.active = ele;
            },
            getActive: function() {
                return gummbase.popup.active;
            }
        },
        alert: function(message, settings) {
            var settings = $.extend({
                type: 'info',
                width: 'auto',
                height: 'auto',
                flash: 2000
            }, settings);
            
            var theAlert = $('<div class="bluebox-admin"><div class="alert alert-block alert-' + settings.type + '"></div></div>');
            theAlert.children('.alert').append('<p>' + message + '</p>');
            theAlert.css({
                display: 'none',
                height: settings.height,
                width: settings.width,
                zIndex: 9999
            });
            $('body').append(theAlert);
            theAlert.center().show('fade', 150);
            
            if (settings.flash === true) settings.flash = 1000;
            if (settings.flash) {
                setTimeout(function(){
                    theAlert.hide('fade', 150, function(){
                        theAlert.remove();
                    });
                }, parseInt(settings.flash));
            }
        },
        extract: function(pattern, string) {

        },
        cookie: {
            write: function(name,value,days) {
                if (days) {
                        var date = new Date();
                        date.setTime(date.getTime()+(days*24*60*60*1000));
                        var expires = "; expires="+date.toGMTString();
                }
                else var expires = "";
                document.cookie = name+"="+value+expires+"; path=/";
            },
            read: function(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                        var c = ca[i];
                        while (c.charAt(0)==' ') c = c.substring(1,c.length);
                        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            },
            destroy: function (name) {
                gummbase.cookie.write(name,"",-1);
            }
        }
    };
});

// ================= //
// GOOGLE JS SCRIPTS //
// ================= //

// Webfonts
function loadGoogleWebFonts(familiesArray) {
    if (!familiesArray) return;
    
    WebFontConfig = {
      google: {
          families: familiesArray
      },
      active: function() {
          jQuery.event.trigger('gummGoogleFontsLoaded');
      }
    };
    (function() {
      var wf = document.createElement('script');
      wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
          '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
      wf.type = 'text/javascript';
      wf.async = 'true';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(wf, s);
    })();
}

// ================================ //
// GENERIC GUMM FRAMEWORK FUNCTIONS //
// ================================ //

jQuery.fn.center = function() {
    this.css({
        position: 'fixed',
        top: '50%',
        left: '50%',
        marginLeft: '-' + (this.width() / 2) + 'px',
        marginTop: '-' + (this.height() / 2) + 'px'
    });
    return this;
}
jQuery.fn.gummRemove = function() {
    var theObj = this;
    theObj.hide('fade', 100, function(){
        theObj.remove();
    });
}

var _gummUniqidHelper = 0;
function uniqid() {
    return ++_gummUniqidHelper;
}

function rgb2hex(rgbString) {
    var parts = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    
    if (parts === null) return false;

    delete (parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    var hexString = parts.join(''); // "0070ff"
    
    return '#' + hexString;
}

if (typeof relative_time !== 'function') {
	function relative_time(time_value, compact) {
	  var values = time_value.split(" ");
	  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	  var parsed_date = Date.parse(time_value);
	  var relative_to = (arguments.length > 2) ? arguments[2] : new Date();
	  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	  delta = delta + (relative_to.getTimezoneOffset() * 60);

	  if (delta < 60) {
	    return (compact === true) ? '1m' : 'less than a minute ago';
	  } else if(delta < 120) {
	    return (compact === true) ? '1m' : 'about a minute ago';
	  } else if(delta < (60*60)) {
	    var mins = (parseInt(delta / 60)).toString();
	    return (compact === true) ? mins + 'm' : mins  + ' minutes ago';
	  } else if(delta < (120*60)) {
	    return (compact === true) ? '1h' : 'about an hour ago';
	  } else if(delta < (24*60*60)) {
	    var hours = (parseInt(delta / 3600)).toString();
	    return (compact === true) ? hours + 'h' : 'about ' + hours + ' hours ago';
	  } else if(delta < (48*60*60)) {
	    return (compact === true) ? '1d' : '1 day ago';
	  } else {
	    var days = (parseInt(delta / 86400)).toString();
	    return (compact === true) ? days + 'd' : days + ' days ago';
	  }
	}
}

/**
 * @author Alexander Farkas
 * v. 1.22
 */


(function($) {
	if(!document.defaultView || !document.defaultView.getComputedStyle){ // IE6-IE8
		var oldCurCSS = $.css;
		$.css = function(elem, name, force){
			if(name === 'background-position'){
				name = 'backgroundPosition';
			}
			if(name !== 'backgroundPosition' || !elem.currentStyle || elem.currentStyle[ name ]){
				return oldCurCSS.apply(this, arguments);
			}
			var style = elem.style;
			if ( !force && style && style[ name ] ){
				return style[ name ];
			}
			return oldCurCSS(elem, 'backgroundPositionX', force) +' '+ oldCurCSS(elem, 'backgroundPositionY', force);
		};
	}
	
	var oldAnim = $.fn.animate;
	$.fn.animate = function(prop){
		if('background-position' in prop){
			prop.backgroundPosition = prop['background-position'];
			delete prop['background-position'];
		}
		if('backgroundPosition' in prop){
			prop.backgroundPosition = '('+ prop.backgroundPosition;
		}
		return oldAnim.apply(this, arguments);
	};
	
	function toArray(strg){
		strg = strg.replace(/left|top/g,'0px');
		strg = strg.replace(/right|bottom/g,'100%');
		strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
		var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
		return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
	}
	
	$.fx.step. backgroundPosition = function(fx) {
		if (!fx.bgPosReady) {
			var start = $.css(fx.elem,'backgroundPosition');
			if(!start){//FF2 no inline-style fallback
				start = '0px 0px';
			}
			
			start = toArray(start);
			fx.start = [start[0],start[2]];
			var end = toArray(fx.end);
			fx.end = [end[0],end[2]];
			
			fx.unit = [end[1],end[3]];
			fx.bgPosReady = true;
		}
		//return;
		var nowPosX = [];
		nowPosX[0] = ((fx.end[0] - fx.start[0]) * fx.pos) + fx.start[0] + fx.unit[0];
		nowPosX[1] = ((fx.end[1] - fx.start[1]) * fx.pos) + fx.start[1] + fx.unit[1];           
		fx.elem.style.backgroundPosition = nowPosX[0]+' '+nowPosX[1];

	};
})(jQuery);

jQuery.fn.onImagesLoaded = function(_cb, context) { 
  return this.each(function() {
 
    var $imgs = (this.tagName.toLowerCase()==='img')?jQuery(this):jQuery('img',this),
        _cont = this,
            i = 0,
    _done=function() {
      if( typeof _cb === 'function' ) _cb.call(context, _cont);
    };
 
    if( $imgs.length ) {
      $imgs.each(function() {
        var _img = this,
        _checki=function(e) {
          if((_img.complete) || (_img.readyState=='complete'&&e.type=='readystatechange') )
          {
            if( ++i===$imgs.length ) _done();
          }
          else if( _img.readyState === undefined ) // dont for IE
          {
            jQuery(_img).attr('src',jQuery(_img).attr('src')); // re-fire load event
          }
        }; // _checki \\
 
        jQuery(_img).bind('load readystatechange', function(e){_checki(e);});
        _checki({type:'readystatechange'}); // bind to 'load' event...
      });
    } else _done();
  });
};

function loadSerializedData(data)
{
    console.log(data);
	var tmp = data.split('&'), dataObj = {};

	// Bust apart the serialized data string into an obj
	for (var i = 0; i < tmp.length; i++)
	{
		var keyValPair = tmp[i].split('=');
		dataObj[keyValPair[0]] = keyValPair[1];
	}
	
    return dataObj;
}