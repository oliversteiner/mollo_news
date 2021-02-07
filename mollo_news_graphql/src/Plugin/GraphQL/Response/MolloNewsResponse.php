<?php

namespace Drupal\mollo_news_graphql\Plugin\GraphQL\Response;

use Drupal\Core\Entity\EntityInterface;
use Drupal\graphql\GraphQL\Response\Response;

/**
 * Type of response used when an mollo_news is returned.
 */
class MolloNewsResponse extends Response {

  /**
   * The mollo_news to be served.
   *
   * @var \Drupal\Core\Entity\EntityInterface|null
   */
  protected $mollo_news;

  /**
   * Sets the content.
   *
   * @param \Drupal\Core\Entity\EntityInterface|null $entity
   *   The mollo_news to be served.
   */
  public function setMolloNews(?EntityInterface $entity): void {
    $this->mollo_news = $entity;
  }

  /**
   * Gets the mollo_news to be served.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The mollo_news to be served.
   */
  public function mollo_news(): ?EntityInterface {
    return $this->mollo_news;
  }


}
