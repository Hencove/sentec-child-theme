import "./_flipbox";
//
//	? code here will execute before the DOM has fully loaded
//

(function (document, window, $) {
  //
  //	? code here will execute once the page is ready
  //
  // ... silence is golden

  let forcedFullSizeImages = $(".fusion-imageframe.is-always-full-size img");

  if (forcedFullSizeImages) {
    // $(forcedFullSizeImages).removeAttr("srcset");
    // $(forcedFullSizeImages).removeAttr("sizes");
    $(forcedFullSizeImages).attr("sizes", "(max-width: 640px) 100vw");
  }

  // get all the filters on this page...
  const filters = $(
    "body.post-type-archive-sentec-publications .wpc-filters-widget-select",
  );

  // if we got filters...
  if (!filters) {
    return;
  }

  $(document).ajaxComplete(function () {
    // Your code to run after an AJAX request completes
    console.log("AJAX request completed");

    // get all the filters on this page...
    const filters = $(
      "body.post-type-archive-sentec-publications .wpc-filters-widget-select",
    );
    // for each filter: inject (add html after select element, not inside it) new element for the arrow thing (with class)
    filters.each(function (index, filterEl) {
      // config standard select2 instance
      let select2Options = {
        minimumResultsForSearch: -1,
      };

      // instantiate select2
      $(filterEl).select2(select2Options);
    });
  });

  // for each filter: inject (add html after select element, not inside it) new element for the arrow thing (with class)
  filters.each(function (index, filterEl) {
    // config standard select2 instance
    let select2Options = {
      minimumResultsForSearch: -1,
    };

    // instantiate select2
    $(filterEl).select2(select2Options);
  });

  // todo: maybe use an object literal once we figure it all out
  let appendSelectElements = {
    // properties below
    circleCSSProps: {},

    // methods below
    _init: function () {},
    _doSomething: function () {
      let grabFromOtherScope = appendSelectElements.circleCSSProps;
    },
  };

  // appendSelectElements._init();

  //
  //
  //
})(document, window, jQuery);
