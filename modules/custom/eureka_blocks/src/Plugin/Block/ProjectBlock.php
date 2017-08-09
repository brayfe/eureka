<?php

namespace Drupal\eureka_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ProjectBlock' block.
 *
 * @Block(
 *  id = "project_block",
 *  admin_label = @Translation("Project Block"),
 * )
 */
class ProjectBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'body' => [
        'value' => $this->t('The Office of Undergraduate Research recommends that you attend an info session [https://ugs.utexas.edu/our/find/sessions] or advising before contacting faculty members or project contacts about research opportunities. We\'ll cover the steps to getting involved, tips for contacting faculty, funding possibilities, and options for course credit. Once you have attended an Office of undergraduate Research info session or spoken to an advisor, you can use the "Who to contact" details for this project to get in touch with the project leader and express your interest in getting involved.'),
        'format' => 'plain_text',
      ],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $formats = filter_formats();
    $default_format = array_shift(array_keys($formats));

    $form['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#description' => $this->t('The text displayed for this block.'),
      '#default_value' => $this->configuration['body']['value'],
      '#format' => isset($this->configuration['body']['format']) ? $this->configuration['body']['format'] : $default_format,
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
    $build['project_block_body']['#markup'] = $body_text;

    return $build;
  }

}
