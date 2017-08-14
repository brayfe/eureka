<?php

namespace Drupal\eureka_contacted_faculty\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\flag\FlagServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a controller to generate a link to mark/unmark "Contacted status".
 */
class ActionLinkController implements ContainerInjectionInterface {

  /**
   * The flag service.
   *
   * @var \Drupal\flag\FlagServiceInterface
   */
  protected $flagService;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructor.
   *
   * @param FlagServiceInterface $flag
   *   The flag service.
   */
  public function __construct(FlagServiceInterface $flag, RendererInterface $renderer) {
    $this->flagService = $flag;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flag'),
      $container->get('renderer')
    );
  }

  /**
   * Retrieves a Flagging entity based on a faculty profile entity id & user.
   */
  public function getFlagging($entity_id) {
    $query = \Drupal::entityTypeManager()->getStorage('flagging')->getQuery();
    $query->condition('entity_type', 'user')
      ->condition('entity_id', $entity_id);
    $query->condition('flag_id', 'profile_flag');
    $query->condition('uid', \Drupal::currentUser()->id());
    $id = $query->execute();
    if ($id) {
      return \Drupal::entityTypeManager()->getStorage('flagging')->load(key($id));
    }
  }

  /**
   * Performs a flagging when called via a route.
   *
   * @param int $entity_id
   *   The flaggable entity ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\Reload
   */
  public function contacted($entity_id, Request $request) {
    /* @var \Drupal\Core\Entity\EntityInterface $entity */
    $flagging = $this->getFlagging($entity_id);
    $flagging->field_contacted_faculty->value = TRUE;
    $flagging->save();
    return $this->generateResponse($entity_id, $request, FALSE);
  }

  /**
   * Performs a flagging when called via a route.
   *
   * @param int $entity_id
   *   The flaggable entity ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\Reload
   */
  public function uncontacted($entity_id, Request $request) {
    /* @var \Drupal\Core\Entity\EntityInterface $entity */
    $flagging = $this->getFlagging($entity_id);
    $flagging->field_contacted_faculty->value = FALSE;
    $flagging->save();
    return $this->generateResponse($entity_id, $request, TRUE);
  }

  /**
   * Main workhorse for creating an AJAX link.
   *
   * @param string $entity_id
   *    The user ID associated with this flagging.
   * @param string $status
   *    Optional way to specify contacted/not contacted without a DB query.
   *
   * @return array|string
   *    The link, as a render array.
   */
  public static function buildLink($entity_id, $status = '') {
    $flag = \Drupal::entityTypeManager()->getStorage('flag')->load('profile_flag');
    $link_type_plugin = $flag->getLinkTypePlugin();
    $entity = User::load($entity_id);
    $link = $link_type_plugin->getAsLink($flag, $entity);
    $renderable = $link->toRenderable();
    $query = \Drupal::entityTypeManager()->getStorage('flagging')->getQuery();
    $query->condition('entity_type', $entity->getEntityTypeId())
      ->condition('entity_id', $entity->id());
    if (!empty($flag)) {
      $query->condition('flag_id', $flag->id());
    }
    $query->condition('uid', \Drupal::currentUser()->id());
    $id = $query->execute();
    if ($id) {
      $flagging = \Drupal::entityTypeManager()->getStorage('flagging')->load(key($id));
      $renderable['#title'] = "Mark as not Contacted";
      if ($status === FALSE) {
        $renderable['#title'] = "Mark as not Contacted";
        $renderable['#attributes']['class'][] = 'contacted';
      }
      elseif ($status === TRUE) {
        $renderable['#title'] = "Mark as Contacted";
        $renderable['#attributes']['class'][] = 'uncontacted';
      }
      else {
        $actual_status = $flagging->field_contacted_faculty->getString();
        if ($actual_status == FALSE) {
          $renderable['#title'] = "Mark as Contacted";
          $status = TRUE;
        }
        else {
          $status = FALSE;
        }
      }
      $renderable['#url'] = self::getContactedUrl($status, $entity->id());
      $renderable['#attached']['library'][] = 'core/drupal.ajax';
      $renderable['#attributes']['class'][] = 'use-ajax';
      $renderable['#attributes']['class'][] = 'flag-' . $flag->id() . '-' . $entity->id();
      $renderable['#attributes']['data-dialog-options'] = Json::encode(['width' => 'auto']);
    }
    return $renderable;

  }

  /**
   * Wrapper for building a URL from route. Only used in buildLink().
   *
   * @param string $status
   *    Optional way to specify contacted/not contacted without a DB query.
   * @param string $entity_id
   *    The user ID associated with this flagging.
   *
   * @return string
   *    A built URL.
   */
  public static function getContactedUrl($status, $entity_id) {
    $route = 'eureka_contacted_faculty.action_link_contacted';
    if ($status == FALSE) {
      $route = 'eureka_contacted_faculty.action_link_uncontacted';
    }
    return Url::fromRoute($route, [
      'flag' => 'profile_flag',
      'entity_id' => $entity_id,
    ]);
  }

  /**
   * Generates a response object after handing the request.
   *
   * @param string $entity_id
   *   The entity ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   */
  protected function generateResponse($entity_id, Request $request, $status = '') {
    $response = new AjaxResponse();
    // Generate a CSS selector to use in a JQuery Replace command.
    $selector = '.flag-profile_flag-' . $entity_id;
    // Create a new JQuery Replace command to update the link display.
    $link = self::buildLink($entity_id, $status);
    $render = $link;
    $replace = new ReplaceCommand($selector, $render);
    $response->addCommand($replace);
    return $response;
  }

}
