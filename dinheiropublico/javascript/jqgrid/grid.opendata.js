/*
**
* Extend jqGrid history
* by Joao Martins
* 
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl-2.0.html
**/ 
(function ($) {
    $.fn.jqGridOpenData = function (options) {
        var firsttime = true;

        var defaultOptions =
            {
			datatype: "json",
			rowNum:40,
			rowList:[40,100,200],
			rownumbers: false,
			rownumWidth: 30,
			gridview: true,
			viewrecords: true,
			autowidth: true,
			height: '100%',
			forceFit: true,
            toppager: true,
			hasHistory: true,
            gridComplete: function() {
                if(firsttime){
                    firsttime = false;
                }
                else{
                    $('html, body').animate({"scrollTop": $(this).offset().top-70});
                }
            }
		};

        var newOptions = $.extend(true, defaultOptions, options);
		if(newOptions.hasHistory)		
			return this.jqGridHistory(newOptions);
		else
			return this.jqGrid(newOptions);

    };
})(jQuery);