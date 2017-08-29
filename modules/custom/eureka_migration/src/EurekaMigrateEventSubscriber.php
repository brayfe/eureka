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

      if ($migration == 'users') {
        // Only run if user is a faculty member.
        if ($row->getSourceProperty('faculty_id')) {
          // Project Redirect Defaults.
          $defaults = [
            'path' => 'faculty/view',
            'query' => ['faculty_id' => $row->getSourceProperty('faculty_id')],
            'status_code' => 301,
            'language' => 'und',
            'type' => 'user',
          ];
        }
        else {
          // Non-faculty user.
          return;
        }
      }

      // The unique destination ID of the item just imported.
      $destinationId = $event->getDestinationIdValues();

      // Project Redirect Defaults.
      $defaults = [
        'path' => 'project/view',
        'query' => ['project_id' => $row->getSourceProperty('project_id')],
        'status_code' => 301,
        'language' => 'und',
        'type' => 'node',
      ];

      $redirect = $this->entityManager->getStorage('redirect')->create();
      $redirect->setSource($defaults['path'], $defaults['query']);
      $redirect->setRedirect($defaults['type'] . '/' . $destinationId[0]);
      $redirect->setStatusCode($defaults['status_code']);
      $redirect->setLanguage($defaults['language']);
      $redirect->save();
    }

  }

}
