/**
 * @file
 * Context admin behaviors.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Provide the UI for drag-and-drop layout editing.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.tosAcknowledgement = {
    'attach': function(context) {
      jQuery("a#terms-of-service").trigger("click");
    }
  };
}(jQuery, Drupal));
