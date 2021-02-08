<?php

namespace Drupal\mollo_news_graphql\Plugin\GraphQL\Schema;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\ComposableSchema;
use Drupal\mollo_news_graphql\Plugin\GraphQL\Wrappers\QueryConnectionNews;

/**
 * @Schema(
 *   id = "mollo_news_base",
 *   name = "Mollo News Base",
 *   extensions = "mollo",
 *   description = "MolloNews - Lists",
 * )
 */
class MolloNewsBase extends ComposableSchema {

  /**
   * {@inheritdoc}
   */
  public function getResolverRegistry() {
    $builder = new ResolverBuilder();
    $registry = new ResolverRegistry();

    $this->addQueryFields($registry, $builder);
    $this->addMolloNewsFields($registry, $builder);

    // Re-usable connection type fields.
    $this->addConnectionFields('MolloNewsConnection', $registry, $builder);





    return $registry;
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addMolloNewsFields(ResolverRegistry $registry, ResolverBuilder $builder) {
    $registry->addFieldResolver('MolloNews', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('MolloNews', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent()),
        $builder->produce('uppercase')
          ->map('string', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('MolloNews', 'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addQueryFields(ResolverRegistry $registry, ResolverBuilder $builder) {

  }

  /**
   * @param string $type
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addConnectionFields(string $type, ResolverRegistry $registry, ResolverBuilder $builder) {
    dpm('addConnectionFields - News');


  }

}
