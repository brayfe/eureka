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

    $form['tos_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Text displayed in the site terms of service'),
      '#default_value' => $config->get('tos_text'),
    );

    $form['notifications_on'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable sending of faculty reminder notifications'),
      '#default_value' => $config->get('notifications_on'),
      '#description' => $this->t('On/Off for notifications, below, to be sent.'),
    );

    $form['faculty_profile_stale'] = array(
      '#type' => 'number',
      '#title' => $this->t('Faculty Profile Update Threshold'),
      '#default_value' => $config->get('faculty_profile_stale'),
      '#description' => $this->t('How much time (in days) should elapse before a
        faculty profile which has not been updated be considered stale?'),
    );
    $form['faculty_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Faculty notification frequency'),
      '#default_value' => $config->get('faculty_notification_frequency'),
      '#description' => $this->t('How frequently should faculty be notified (in days) if their stagnant profile has not been updated?'),
    );

    $form['project_stale'] = array(
      '#type' => 'number',
      '#title' => $this->t('Project Update Threshold'),
      '#default_value' => $config->get('project_stale'),
      '#description' => $this->t('How much time (in days) should elapse before a
        project which has not been updated be considered stale?'),
    );
    $form['project_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Project Lead notification frequency'),
      '#default_value' => $config->get('project_notification_frequency'),
      '#description' => $this->t('How frequently should project leads be
        notified (in days) if a stagnant project has not been updated?'),
    );
    $form['project_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email to update a project'),
      '#default_value' => $config->get('project_notification_message'),
    );
    $form['profile_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email to update a faculty profile'),
      '#default_value' => $config->get('profile_notification_message'),
    );

    // Relates to sending emails to students about updated content.
    $form['student_notifications_on'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable sending of student notifications'),
      '#default_value' => $config->get('student_notifications_on'),
      '#description' => $this->t('On/Off for student notifications, below, to be sent.'),
    );

    $form['student_notification_frequency'] = array(
      '#type' => 'number',
      '#title' => $this->t('Student notification frequency'),
      '#default_value' => $config->get('student_notification_frequency'),
      '#description' => $this->t('Number of days before notifying students if a faculty member they bookmarked has updated their profile or created a new project.'),
    );
    $form['student_project_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Notification email text when bookmarked faculty member adds a new project.'),
      '#default_value' => $config->get('student_project_notification_message'),
      '#description' => 'Use the shortcode [faculty] to represent a faculty member name. Use the shortcode [project] to represent a project title. Use [project-url] to represent a direct link to the project.',
    );
    $form['student_profile_notification_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Email text when a bookmarked faculty member profile is updated'),
      '#default_value' => $config->get('student_profile_notification_message'),
      '#description' => 'Use the shortcode [faculty] to represent a faculty member name. Use [faculty-url] to represent a direct link to the faculty member.',
    );

    return parent::buildForm($form, $form_state);
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
