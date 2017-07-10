<?php

namespace Drupal\utexas_flipcard_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'FlipCardBlock' block.
 *
 * @Block(
 *  id = "flip_card_block",
 *  admin_label = @Translation("FlipCard Block"),
 *  deriver = "Drupal\utexas_flipcard_block\Plugin\Derivative\UTexasFlipCardBlockDerivative"
 * )
 */
class FlipCardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'card_front' => '',
      'card_back' => '',
      'front_color' => '333f48',
      'back_color' => '382f2d',
      'flip_behavior' => 'flip-on-hover',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   *
   * Creates a generic configuration form for all block types. Individual
   * block plugins can add elements to this form by overriding
   * BlockBase::blockForm(). Most block plugins should not override this
   * method unless they need to alter the generic form elements.
   *
   * @see \Drupal\Core\Block\BlockBase::blockForm()
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $definition = $this->getPluginDefinition();
    $form['provider'] = [
      '#type' => 'value',
      '#value' => $definition['provider'],
    ];

    $form['admin_label'] = [
      '#type' => 'item',
      '#title' => $this->t('Block description'),
      '#plain_text' => $definition['admin_label'],
    ];
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 255,
      '#default_value' => $this->label(),
      '#required' => TRUE,
    ];
    $form['label_display'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display title'),
      '#default_value' => FALSE,
      '#return_value' => static::BLOCK_LABEL_VISIBLE,
      '#access' => FALSE,
    ];

    // Add context mapping UI form elements.
    $contexts = $form_state->getTemporaryValue('gathered_contexts') ?: [];
    $form['context_mapping'] = $this->addContextAssignmentElement($this, $contexts);
    // Add plugin-specific settings for this block type.
    $form += $this->blockForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['flip_behavior'] = [
      '#type' => 'radios',
      '#title' => $this->t('Flip behavior'),
      '#options' => [
        'flip-on-hover' => 'Flip on hover (not mobile-compatible)',
        'flip-on-click' => 'Flip on click',
      ],
      '#weight' => '1',
      '#default_value' => $this->configuration['flip_behavior'],
    ];
    $form['front_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Card front: background color'),
      '#options' => [
        '333f48' => 'Blue Gray',
        '382f2d' => 'Dark Gray',
      ],
      '#weight' => '1',
      '#default_value' => $this->configuration['front_color'],
    ];
    $form['card_front'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Card front: text'),
      '#description' => $this->t('Text to display when the card is initially displayed'),
      '#default_value' => $this->configuration['card_front']['value'],
      '#format' => isset($this->configuration['card_front']['format']) ? $this->configuration['card_front']['format'] : 'plain_text',
      '#weight' => '2',
    ];

    $form['back_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Card back: background color'),
      '#options' => [
        '333f48' => 'Blue Gray',
        '382f2d' => 'Dark Gray',
      ],
      '#weight' => '3',
      '#default_value' => $this->configuration['back_color'],
    ];
    $form['card_back'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Card back: text'),
      '#description' => $this->t('Text to display when the card is flipped'),
      '#default_value' => $this->configuration['card_back']['value'],
      '#format' => isset($this->configuration['card_back']['format']) ? $this->configuration['card_front']['format'] : 'plain_text',
      '#weight' => '4',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['card_front'] = $form_state->getValue('card_front');
    $this->configuration['card_back'] = $form_state->getValue('card_back');
    $this->configuration['back_color'] = $form_state->getValue('back_color');
    $this->configuration['front_color'] = $form_state->getValue('front_color');
    $this->configuration['flip_behavior'] = $form_state->getValue('flip_behavior');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $front_color = $this->configuration['front_color'];
    $front_text = check_markup($this->configuration['card_front']['value'], $this->configuration['card_front']['format']);
    $back_color = $this->configuration['back_color'];
    $back_text = check_markup($this->configuration['card_back']['value'], $this->configuration['card_back']['format']);

    $library = ['utexas_flipcard_block/utexas-flipcard-block.' . $this->configuration['flip_behavior']];

    return [
      '#theme' => 'utexas_flipcard_block',
      '#front_text' => $front_text,
      '#front_color' => $front_color,
      '#back_text' => $back_text,
      '#back_color' => $back_color,
      '#attached' => ['library' => $library],
      '#id' => $this->getDerivativeId(),
    ];
  }

}
