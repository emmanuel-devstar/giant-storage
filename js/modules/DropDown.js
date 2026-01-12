class DropDown {
    constructor($) {          
        $.fn.dropDown = function (options) {

            options = $.extend({}, $.fn.dropDown.options, options);
    
            this.placeholder = this.children('span');
            this.el_u = this.find('ul.dropdown');
            this.opts = this.find('ul.dropdown > li');
            this.el_a = this.find('ul.dropdown > li > .option');
    
            this.val = '';
            this.index = -1;
    
            var obj = this;
    
            if (options.startVal) {
                //default option
                obj.val = options.startVal;
                obj.find(".opion[value = " + options.startVal + "]").trigger("click");
                obj.removeClass('active');
            }
    
            obj.on('click', function (e) {
                //open list
                e.stopPropagation();
                obj.toggleClass('active');
            });
    
            $(document).on('click', function (e) {
                //close list
                obj.removeClass('active');
            });
    
            obj.opts.on('click', function () {
                //choosing option
                var opt = $(this);
                obj.val = opt.text();
    
                /* obj.index = opt.index(); */
                obj.find(".dropdown-title-js").text(obj.val);
                obj.find(".dropdown-value-js").val(obj.val);
    
                if (options.select) {
                    options.select(opt);
                }
            });
        };
        return $;
    }
  }
  
  export default DropDown