/**
 * @file
 * Contains logic for javascript flipping behavior.
 *
 */
(function ($, Drupal) {

  /**
   * On click, toggle a class for an element with the given CSS.
   */
   Drupal.behaviors.flipOnClick = {
     attach: function (context, settings) {
      $('.sb-container .card').once().click(function() {
        var id = $(this).attr('id');
        $('#' + id).toggleClass('flipped');
      });
     }
   };

})(jQuery, Drupal);
