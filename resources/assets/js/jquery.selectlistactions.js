/**
 *  jQuery.SelectListActions
 *  https://github.com/esausilva/jquery.selectlistactions.js
 *
 *  (c) http://esausilva.com
 */

;(function($) {
  //Moves selected item(s) from sourceList to destinationList
  $.fn.moveToList = function(sourceList, destinationList) {
    var opts = $(sourceList + ' option:selected');
    if (opts.length == 0) {
      alert("Nothing to move  ddd");
    }
    $(destinationList).append($(opts).clone());
  };

  //Moves all items from sourceList to destinationList
  $.fn.moveAllToList = function(sourceList, destinationList) {
    var opts = $(sourceList + ' option');
    if (opts.length == 0) {
      alert("Nothing to move bbb");
    }
    $(destinationList).append($(opts).clone());
  };

  //Moves selected item(s) from sourceList to destinationList and deleting the
  // selected item(s) from the source list
  $.fn.moveToListAndDelete = function(sourceList, destinationList) {
    var opts = $(sourceList + ' option:selected');
    if (opts.length == 0) {
      alert("Nothing to move hhh");
    }
    $(opts).remove();
    $(destinationList).append($(opts).clone());
  };

  //Moves all items from sourceList to destinationList and deleting
  // all items from the source list
  $.fn.moveAllToListAndDelete = function(sourceList, destinationList) {
    var opts = $(sourceList + ' option');
    if (opts.length == 0) {
      alert("Nothing to move yyyy");
    }
    $(opts).remove();
    $(destinationList).append($(opts).clone());
  };

  //Removes selected item(s) from list
  $.fn.removeSelected = function(list) {
    var opts = $(list + ' option:selected');
    if (opts.length == 0) {
      alert("Nothing to remove sss");
    }
    $(opts).remove();
  };

  //Moves selected item(s) up or down in a list
  $.fn.moveUpDown = function(list, btnUp, btnDown) {
    var opts = $(list + ' option:selected');
    if (opts.length == 0) {
      alert("Nothing to move uuuu");
    }
    if (btnUp) {
      opts.first().prev().before(opts);
    } else if (btnDown) {
      opts.last().next().after(opts);
    }
  };
})(jQuery);