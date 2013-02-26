/**
 * DashboardBundle Helper functions
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
var ZenstruckDashboardHelper = {
    initAjaxUrl: function() {
        $('.dashboard-widget-ajax').each(function() {
            var $this = $(this);
            var url = $this.data('url');

            $.ajax({
                url: url,
                success: function(data) {
                    $this.removeClass('dashboard-widget-ajax').html(data);
                }
            });
        });
    },

    initialize: function() {
        this.initAjaxUrl();
    }
};