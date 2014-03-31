/**
 * Common stuff used on all FooTables
 */

jQuery(function () {

    //setup tabs for the page
    jQuery('.nav-tabs a').click(function (e) {

        //show the tab!
        jQuery(this).tab('show');

    }).on('shown', function (e) {

        //make sure that any footable in the visible tab is resized
        jQuery('.tab-pane.active .footable').trigger('footable_resize');

    });

    //if there is a hash, then show the tab
    if (window.location.hash.length > 0) {
        jQuery('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
    }

});