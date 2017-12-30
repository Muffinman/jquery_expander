(function ($, drupalSettings) {
  Drupal.behaviors.JQueryExpanderBehavior = {
    attach: function (context) {
      var expander = drupalSettings.jqueryExpander;
      for (var key in expander) {
        $('.field-expander-' + key).expander(expander[key]);
      }
    }
  };
})(jQuery, drupalSettings);
