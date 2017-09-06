<?php

namespace Drupal\eureka_notifications\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\eureka_notifications\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eureka_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'eureka_general.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('eureka_general.settings');

    $form['description'] = [
      '#markup' => 'General configuration options for the site.',
    ];

    $form['tos'] = array(
      '#type' => 'details',
      '#title' => t('Terms of Service'),
      '#open' => TRUE,
    );
    $form['tos']['tos_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Text displayed in the site terms of service'),
      '#default_value' => $config->get('tos_text'),
      '#description' => $this->t('Allowed HTML tags: @tags', ['@tags' => '<p> <strong> <em> <br> <a>']),
    );

    $form['faculty'] = array(
      '#type' => 'details',
      '#title' => t('Faculty Notifications'),
      '#open' => TRUE,
    );
    $form['faculty']['notifications_on'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable sending of faculty reminder notifications'),
      '#default_value' => $config->get('notifications_on'),
      '#description' => $this->t('On/Off for notifications, below, to be sent.'),
    );

    $form['faculty']['faculty_profile_stale'] = array(
      '#type' => 'number',
      '#title' => $this->t('Faculty profile update threshold'),
      '#default_value' => $config->get('faculty_profile_stale'),
      '#description' => $this->t('How much time (in days) should elapse before a
        faculty profile which has not been updated be considered stale?'),
    );
    $form['faculty']['faculty_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Faculty notification frequency'),
      '#default_value' => $config->get('faculty_notification_frequency'),
      '#description' => $this->t('How frequently should faculty be notified (in days) if their stagnant profile has not been updated?'),
    );

    $form['faculty']['project_stale'] = array(
      '#type' => 'number',
      '#title' => $this->t('Project update threshold'),
      '#default_value' => $config->get('project_stale'),
      '#description' => $this->t('How much time (in days) should elapse before a
        project which has not been updated be considered stale?'),
    );
    $form['faculty']['project_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Project lead notification frequency'),
      '#default_value' => $config->get('project_notification_frequency'),
      '#description' => $this->t('How frequently should project leads be
        notified (in days) if a stagnant project has not been updated?'),
    );
    $form['faculty']['project_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email to update a project'),
      '#default_value' => $config->get('project_notification_message'),
      '#description' => $this->t('This field does not allow any HTML.'),
    );
    $form['faculty']['profile_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email to update a faculty profile'),
      '#default_value' => $config->get('profile_notification_message'),
      '#description' => $this->t('This field does not allow any HTML.'),
    );

    $form['students'] = array(
      '#type' => 'details',
      '#title' => t('Student Notifications'),
      '#open' => TRUE,
    );
    // Relates to sending emails to students about updated content.
    $form['students']['student_notifications_on'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable sending of student notifications'),
      '#default_value' => $config->get('student_notifications_on'),
      '#description' => $this->t('On/Off for student notifications, below, to be sent.'),
    );

    $form['students']['student_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Student notification frequency'),
      '#default_value' => $config->get('student_notification_frequency'),
      '#description' => $this->t('Number of days before notifying students if a faculty member they bookmarked has updated their profile or created a new project.'),
    );
    $form['students']['student_project_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Notification email text when bookmarked faculty member adds a new project.'),
      '#default_value' => $config->get('student_project_notification_message'),
      '#description' => 'Use the shortcode [faculty] to represent a faculty member name. Use the shortcode [project] to represent a project title. Use [project-url] to represent a direct link to the project.',
      '#description' => $this->t('This field does not allow any HTML.'),
    );
    $form['students']['student_profile_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email text when a bookmarked faculty member profile is updated'),
      '#default_value' => $config->get('student_profile_notification_message'),
      '#description' => 'Use the shortcode [faculty] to represent a faculty member name. Use [faculty-url] to represent a direct link to the faculty member.',
      '#description' => $this->t('This field does not allow any HTML.'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $plaintext_fields = [
      'project_notification_message',
      'profile_notification_message',
      'student_profile_notification_message',
      'student_project_notification_message',
    ];
    foreach ($plaintext_fields as $field) {
      $sanitized = strip_tags($values[$field]);
      if ($sanitized !== $values[$field]) {
        $form_state->setErrorByName($field, $this->t('The highlighted field below may not contain HTML.'));
      }
    }

    $tos = strip_tags($values['tos_text'], '<p> <strong> <em> <br> <a>');
    if ($tos !== $values['tos_text']) {
      $form_state->setErrorByName('tos_text', $this->t('The Terms of Service text may only contain the following tags: @tags', ['@tags' => '<p> <strong> <em> <br> <a>']));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();
    $this->config('eureka_general.settings')
      ->set('notifications_on', $values['notifications_on'])
      ->set('faculty_profile_stale', $values['faculty_profile_stale'])
      ->set('faculty_notification_frequency', $values['faculty_notification_frequency'])
      ->set('project_stale', $values['project_stale'])
      ->set('project_notification_frequency', $values['project_notification_frequency'])
      ->set('project_notification_message', $values['project_notification_message'])
      ->set('profile_notification_message', $values['profile_notification_message'])
      ->set('student_notifications_on', $values['student_notifications_on'])
      ->set('student_notification_frequency', $values['student_notification_frequency'])
      ->set('student_profile_notification_message', $values['student_profile_notification_message'])
      ->set('student_project_notification_message', $values['student_project_notification_message'])
      ->set('tos_text', $values['tos_text'])

      ->save();

    parent::submitForm($form, $form_state);
  }

}
