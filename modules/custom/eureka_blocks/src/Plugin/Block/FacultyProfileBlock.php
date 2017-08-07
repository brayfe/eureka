<?php

namespace Drupal\eureka_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'FacultyProfileBlock' block.
 *
 * @Block(
 *  id = "faculty_profile_block",
 *  admin_label = @Translation("Faculty Profile Block"),
 * )
 */
class FacultyProfileBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'body' => [
        'value' => $this->t('Before reaching out about your research interests, the Office of Undergraduate Research recommends you attend an info session [https://ugs.utexas.edu/our/find/sessions] or advising for tips on contacting faculty members.'),
        'format' => 'plain_text',
      ],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#description' => $this->t('The text displayed for this block.'),
      '#default_value' => $this->configuration['body']['value'],
      '#format' => isset($this->configuration['body']['format']) ? $this->configuration['body']['format'] : 'filtered_html',
      '#weight' => '1',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['body'] = $form_state->getValue('body');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $body_text = check_markup($this->configuration['body']['value'], $this->configuration['body']['format']);
    $build['faculty_profile_block_body']['#markup'] = $body_text;

    return $build;
  }

}
