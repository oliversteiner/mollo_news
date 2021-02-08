<?php

namespace Drupal\mollo_news_graphql\Plugin\GraphQL\Wrappers;

use Drupal\Core\Entity\Query\QueryInterface;
use GraphQL\Deferred;

class QueryConnectionNews {

  /**
   * @var \Drupal\Core\Entity\Query\QueryInterface
   */
  protected $query;

  /**
   * graphql_testonnection constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryInterface $query
   */
  public function __construct(QueryInterface $query) {
    $this->query = $query;
    dpm('-- QueryConnection News ---');
  }

  /**
   * @return int
   */
  public function total() {
    $query = clone $this->query;
    $query->range(NULL, NULL)->count();
    return $query->execute();
  }

  /**
   * @return array
   */
  public function test(): array {
    return ['Test'];
  }

  /**
   * @return array|\GraphQL\Deferred
   */
  public function items() {
    dpm('items');

    $result = $this->query->execute();
    dpm($result);
    if (empty($result)) {
      return [];
    }

    $buffer = \Drupal::service('graphql.buffer.entity');
    $callback = $buffer->add($this->query->getEntityTypeId(), array_values($result));
    return new Deferred(function () use ($callback) {
      return $callback();
    });
  }

}
