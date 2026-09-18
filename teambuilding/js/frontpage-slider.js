// This is emile.js:
// twitter.com/emilejs
(function(f,a)
{
	var h=document.createElement("div"),g=("backgroundColor borderBottomColor borderBottomWidth borderLeftColor borderLeftWidth borderRightColor borderRightWidth borderSpacing borderTopColor borderTopWidth bottom color fontSize fontWeight height left letterSpacing lineHeight marginBottom marginLeft marginRight marginTop maxHeight maxWidth minHeight minWidth opacity outlineColor outlineOffset outlineWidth paddingBottom paddingLeft paddingRight paddingTop right textIndent top width wordSpacing zIndex").split(" ");
	function e(j,k,l)
	{
		return(j+(k-j)*l).toFixed(3)
	}
	
	function i(k,j,l)
	{
		return k.substr(j,l||1)
	}
	
	function c(l,p,s)
	{
		var n=2,m,q,o,t=[],k=[];
		
		while(m=3,q=arguments[n-1],n--)
		{
			if(i(q,0)=="r")
			{
				q=q.match(/\d+/g);
				while(m--)
				{
					t.push(~~q[m])
				}
			}
			else
			{
				if(q.length==4)
				{
					q="#"+i(q,1)+i(q,1)+i(q,2)+i(q,2)+i(q,3)+i(q,3)
				}
				while(m--)
				{
					t.push(parseInt(i(q,1+m*2,2),16))
				}
			}
		}
		
		while(m--)
		{
			o=~~(t[m+3]+(t[m]-t[m+3])*s);
			k.push(o<0?0:o>255?255:o)
		}
		
		return"rgb("+k.join(",")+")"
	}
	
	function b(l)
	{
		var k=parseFloat(l),j=l.replace(/^[\-\d\.]+/,"");
		
		return isNaN(k)?{v:j,f:c,u:""}:{v:k,f:e,u:j}
	}
	
	function d(m)
	{
		var l,n={},k=g.length,j;
		
		h.innerHTML='<div style="'+m+'"></div>';
		l=h.childNodes[0].style;
		
		while(k--)
		{
			if(j=l[g[k]])
			{
				n[g[k]]=b(j)
			}
		}
		
		return n
	}
	
	a[f]=function(p,m,j)
	{
		p=typeof p=="string"?document.getElementById(p):p;
		j=j||{};
		
		var r=d(m),q=p.currentStyle?p.currentStyle:getComputedStyle(p,null),l,s={},n=+new Date,k=j.duration||200,u=n+k,o,
		t=j.easing||
		function(v)
		{
			return(-Math.cos(v*Math.PI)/2)+0.5};
			for(l in r)
			{
				s[l]=b(q[l])
			}
			o=setInterval(function()
			{
				var v=+new Date,w=v>u?1:(v-n)/k;
				for(l in r)
				{
					p.style[l]=r[l].f(s[l].v,r[l].v,t(w))+r[l].u
				}
				if(v>u)
				{
					clearInterval(o);
					j.after&&j.after()
				}
			},10)
		}
	})("emile",this);

(function ($, undefined) {

  // Start this process after the DOM has loaded
  $(function() {
    var ref = document.referrer.toLowerCase();
	
    slider.getPageList();

    // Custom functions for specific pages:
	
    slider.getPage('#servicii').onload = function() 
	{
      $('.slideshow').cycle({ fx: 'scrollRight', cleartypeNoBg: true, timeout: 5000 });
    }
	
    slider.getPage('#noi').onload = function() {
      $.getScript('/js/BrightcoveExperiences.js', function () {
        brightcove.createExperiences();
      });
      $.getScript('/js/APIModules_all.js');
      $.getScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
      $.getScript('/js/jquery.tinycarousel.min.js');
      $.getScript('/js/jquery.getUrlParam.js');
      $.getScript('/js/videos.js');
    }

    slider.loadPages();
    slider.setLeftRightButtons();
    slider.setLinkListeners();
    slider.setHashListener();
	

    // Should we show the splash screen?
    if(!ref.match(/^http:\/\/(\w+\.)?gorillaglass2\.m7sandbox\.com/) && 
       !ref.match(/^http:\/\/(\w+\.)?corninggorillaglass\.com/) &&
       location.hash === '') {
      slider.showSplash();
    }
    else {
      slider.fixPage(true);
    }

  });
  
  // Namespace & variables
  var slider = window.homepage_slider = {
    pages: []
  };

  // Util functions
  var idFromString = function(original) {
    return original.replace(/[^a-zA-Z0-9]+/g, '-').toLowerCase();
  }
  var qcLabelFromString = function(original) {
    return original.replace(/[^a-zA-Z0-9]+/g, '+');
  }

  // Get pages from nav links
  slider.getPageList = function() {
    // Links are in two columns. Pages need to be arranged
    // in the order that the links are displayed, starting
    // with col. 1
    var col1 = [], col2 = [];
	
    $('.links.primary-links a').each( function(i) {
      var name = $(this).text();
	  /* alert($(this)); */
      var list = col1;
      /* if(i%2 === 1) {
        list = col2;
      } */
	  
      list.push({
        href: $(this).attr('href'),
        title: name,
        hash: '#' + idFromString(name),
        id: idFromString(name + " container"),
        qc: qcLabelFromString(name),
        navlink: $(this)
      });
    } );
    
    slider.pages = col1.concat(col2);

    $.each(slider.pages, function(i) {
      this.index = i;
    });
  }

  // Gets a specific page by id
  slider.getPage = function(hash) {
    for(var i = 0; i < slider.pages.length; i++) {
      if(slider.pages[i].hash === hash) return slider.pages[i];
	}
  }

  // Asyncronously load the new pages into the slider
  slider.loadPages = function() {
    slider.dom = $('#page-slider .page-slider-wrap');
    $.each(slider.pages, function() {
      var page = this;
      if(page.title === "Home") {
        page.dom = slider.dom.children(".front");
        page.id = page.dom.attr('id');
        page.dom.addClass('slider-container');
      }
      else {
        page.dom = $('<div class="slider-container" id="' + page.id + '"></div>');
		/* alert('<div class="slider-container" id="' + page.id + '">'+page.href+'</div>'); */
        page.dom.load(page.href + ' #inner', function() {
          if(page.onload !== undefined) {
            page.onload();
          }
          /* initColorbox(); */
          $("a.sn, a.offsite").attr('target', '_blank');
        });
        page.dom.css('margin-left', 1100 * page.index);
        slider.dom.append(page.dom);
      }
    });
  }

  // Go to a specific page
  slider.goToPage = function(page, skipAnimation) {
    var newMargin = 250 - 1100 * page.index;

    if(skipAnimation === true) {
      slider.dom.css('margin-left', newMargin);
    }
    else {
      // right now, jq animate seems to be borked
      // so have fun with this:
      emile(slider.dom.get().pop(), 'margin-left: ' + newMargin + 'px', { duration: 800 });
      // slider.dom.animate(
      //  { 'margin-left': newMargin },
      //  { duration: 800 });
    }

    // Save current page
    slider.current = page;
    
    // Select the right link
    $('a.active').removeClass('active').removeClass('active-trail');
    page.navlink.addClass('active');

    // Conditionally hide or display the left and right arrows
    if(page.index === 0) {
      slider.leftButton.hide();
    }
    else {
      slider.leftButton.show();
    }

    if(page.index === slider.pages.length - 1) {
      slider.rightButton.hide();
    }
    else {
      slider.rightButton.show();
    }

    // Render qc tag
    slider.qcTag();
  }

  slider.fixPage = function(skipAnimation) {
    if(location.hash === '') {
      slider.goToPage(slider.getPage('#home'), skipAnimation);
    }
    else {
      slider.goToPage(slider.getPage(location.hash), skipAnimation);
    }
  }

  // Borrowed from localscroll to prevent flicker when setting hash
  slider.set_hash = function(hash) {
    var attr = 'id';
    
    if(hash == '' || hash === undefined || hash == '#') return;
    id = hash.substring(1);

    var elem = document.getElementById(id) || document.getElementsByName(id)[0];
    if(elem !== undefined) {
      attr = elem.id == id ? 'id' : 'name';
      elem[attr] = '';
    }

    $a = $('<a> </a>').attr(attr, id).css({
      position:'absolute',
      top: $(window).scrollTop(),
      left: $(window).scrollLeft()
    });
    
    $('body').prepend($a);
    location = hash;
    $a.remove();
    
    if(elem !== undefined) {
      elem[attr] = id;
    }
  }

  slider.setLeftRightButtons = function() {
    slider.leftButton = $('<a href="#" id="btn-left"></a>');
    slider.rightButton = $('<a href="#" id="btn-right"></a>');
    slider.dom.parent().append(slider.leftButton);
    slider.dom.parent().append(slider.rightButton);

    slider.leftButton.click(function(event) {
      event.preventDefault();
      slider.set_hash(slider.pages[slider.current.index-1].hash);
    });

    slider.rightButton.click(function(event) {
      event.preventDefault();
      slider.set_hash(slider.pages[slider.current.index+1].hash);
    });
  }

  slider.setLinkListeners = function() {
    $('.links.primary-links a').live('click', function(event) {
      event.preventDefault();

      // All we do here is set the url string.
      // A hashchange listener will pick this up and change the page for us.
      // Nifty!
      slider.set_hash('#' + idFromString($(this).text()));
    });
  }
  
  slider.setHashListener = function() {
    $(window).bind('hashchange', function() {
      slider.fixPage();
    });
  }

  slider.showSplash = function() {
    var interrupt;

    interrupt = window.setTimeout(slider.hideSplash, 1000);
    $('#splash').show().click(function() {
      slider.hideSplash();
      clearTimeout(interrupt);
    });
  }

  slider.hideSplash = function() {
    $('.page-slider-wrap').css('margin-left', '1200px');
    slider.fixPage();
    $('#splash').animate(
        { 'right': '100%' },
        {
          duration: 3000,
          complete: function() {
            $(this).remove();
          }
        });
  }

 
  slider.qcTag = function() {
    var label = slider.current.title,
        url = slider.current.qc,
        s = document.createElement('script'),
        x = document.getElementsByTagName('script')[0],
        y = document.getElementsByTagName('')
        pixel = document.createElement('img');
    
    _qoptions={qacct:"p-8a1rOwsVdBWng",labels:"_fp.event." + label};
    
    s.type = 'text/javascript';
    s.async = true;
    s.src = 'https://edge.quantserve.com/quant.js';
    x.parentNode.insertBefore(s, x);

    pixel.src = 'https://pixel.quantserve.com/pixel/p-8a1rOwsVdBWng.gif?labels=_fp.event.' + url;
    pixel.style.display = 'none';
    pixel.border = '0';
    pixel.height = '1';
    pixel.width = '1';
    pixel.alt = 'Quantcast';
    window.document.body.appendChild(pixel);
  }

})(jQuery);