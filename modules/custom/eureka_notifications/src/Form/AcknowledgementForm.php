<?php

namespace Drupal\eureka_notifications\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Url;

/**
 * Class AcknowledgementForm.
 *
 * @package Drupal\eureka_notifications\Form
 */
class AcknowledgementForm extends FormBase {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;
  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs a new AcknowledgementForm object.
   */
  public function __construct(
    AccountProxy $current_user,
    ConfigFactory $config_factory
  ) {
    $this->currentUser = $current_user;
    $this->configFactory = $config_factory;
  }

  /**
   * Create method for injecting services.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'acknowledgement_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('eureka_general.settings');
    $form['text']['#markup'] = '<div class="panel">' . $config->get('tos_text') . '</div>';
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('I agree'),
    ];
    $form['submit']['#attributes']['class'][] = 'btn-info';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    if ($values['submit'] == 'I agree') {
      $uid = $this->currentUser->id();
      $user = user_load($uid);
      $user->field_terms_of_ser->value = TRUE;
      $user->save();
    }

    $url = Url::fromRoute('eureka_bookmark_dashboard.content');
    $form_state->setRedirectUrl($url);

  }

}
