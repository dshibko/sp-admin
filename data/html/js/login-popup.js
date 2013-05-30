(function() {
    "use strict";
    
    function Popup(element, options) {
        var defaults = {
            button  : ".login-popup-button",
            content : ".popup-content",
            close   : ".popup-content .popup-close"
        };
        this.options = $.extend(defaults, options);
        this.el      = $(element);
        this.init();
        this.bind();
    }
    Popup.prototype.$ = function(selector) {
        return this.el.find(selector);
    }
    Popup.prototype.bind = function() {
        this._button_click = $.proxy(this.button_click, this);
        this.button.bind("click", this._button_click);
        
        this._close_click = $.proxy(this.close_click, this);
        this.close.bind("click", this._close_click);
        
        this._document_click = $.proxy(this.document_click, this);
        $(document).bind("click", this._document_click);
    }
    Popup.prototype.cleanup = function() {
        this.button.unbind("click", this._button_click);
        this.close.unbind("click", this._close_click);
        $(document).unbind("click", this._document_click);
    }
    Popup.prototype.init = function() {
        this.button  = this.$(this.options.button);
        this.content = this.$(this.options.content);
        this.close   = this.$(this.options.close);
    }
    Popup.prototype.document_click = function(event) {
        if ($(event.target).closest(this.el).size() == 0 && event.target != this.el.get(0)) {
            this.hide();
        }
    }
    Popup.prototype.button_click = function() {
        this.show();
    }
    Popup.prototype.close_click = function() {
        this.hide();
    }
    Popup.prototype.show = function() {
        this.content.addClass("popup-opened");
        this.button.addClass("active");
    }
    Popup.prototype.hide = function() {
        this.content.removeClass("popup-opened");
        this.button.removeClass("active");
    }
    
    $.fn.extend({
        Popup: function(options) {
            return this.each(function() {
                $(this).data("Popup", new Popup(this, options));
            });
        }
    });
  
}) ();