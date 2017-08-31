<?php

namespace Drupal\eureka_migration;

use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Create a redirect entity whenever a file is migrated.
 */
class EurekaMigrateEventSubscriber implements EventSubscriberInterface {

  /**
   * Entity manager.
   *
   * @var X
   *   X provided by the factory injected below in the constructor.
   */
  protected $entityManager;

  /**
   * Implements __construct().
   *
   * Dependency injection defined in .services.yml.
   */
  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [MigrateEvents::POST_ROW_SAVE => [['onPostRowSave']]];
  }

  /**
   * Subscribed event callback: MigrateEvents::POST_ROW_SAVE.
   *
   * If the saved entity is a project, create it's redirect.
   *
   * @param Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The event triggered.
   */
  public function onPostRowSave(MigratePostRowSaveEvent $event) {
    $migrations = ['projects', 'users'];
    $migration = $event->getMigration()->getPluginId();

    if (in_array($migration, $migrations)) {
      // Row object containing the specific item just imported.
      $row = $event->getRow();
      // The unique destination ID of the item just imported.
      $destinationId = $event->getDestinationIdValues();

      // Only run if user is a faculty member.
      if ($migration == 'users') {
        if ($row->getSourceProperty('faculty_id')) {
          // Faculty Redirect settings.
          $settings = [
            'path' => 'faculty/view',
            'query' => ['faculty_id' => $row->getSourceProperty('faculty_id')],
            'status_code' => 301,
            'language' => 'und',
            'type' => 'user',
          ];

          $this->createRedirect($settings, $destinationId[0]);
        }
      }
      else {
        // Project Redirect settings.
        $settings = [
          'path' => 'project/view',
          'query' => ['project_id' => $row->getSourceProperty('project_id')],
          'status_code' => 301,
          'language' => 'und',
          'type' => 'node',
        ];

        $this->createRedirect($settings, $destinationId[0]);
      }
    }
  }

  /**
   * Create entity redirect based on given settings.
   */
  private function createRedirect($settings, $id) {
    $redirect = $this->entityManager->getStorage('redirect')->create();
    $redirect->setSource($settings['path'], $settings['query']);
    $redirect->setRedirect($settings['type'] . '/' . $id);
    $redirect->setStatusCode($settings['status_code']);
    $redirect->setLanguage($settings['language']);
    $redirect->save();
  }

}
