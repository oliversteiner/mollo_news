<?php

namespace Drupal\mollo_news_graphql\Plugin\GraphQL\SchemaExtension;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\graphql\GraphQL\Response\ResponseInterface;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;
use Drupal\mollo_news_graphql\Plugin\GraphQL\Response\MolloNewsResponse;
use Exception;

/**
 * @SchemaExtension(
 *   id = "mollo_news_extension",
 *   name = "Mollo News  Extension",
 *   description = "Mollo News Extension",
 *   schema = "mollo_news"
 * )
 */
class MolloNewsExtension extends SdlSchemaExtensionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry) {
    $builder = new ResolverBuilder();

    $registry->addFieldResolver('Query', 'mollo_news',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['mollo_news']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('getMolloNews', 'items',
      $builder->callback(function (MolloNewsResponse $response) {
        return $response->getViolations();
      })
    );

    // Create mollo_news mutation.
    $registry->addFieldResolver('Mutation', 'createMolloNews',
      $builder->produce('create_mollo_news')
        ->map('data', $builder->fromArgument('data'))
    );

    $registry->addFieldResolver('MolloNewsResponse', 'mollo_news',
      $builder->callback(function (MolloNewsResponse $response) {
        return $response->mollo_news();
      })
    );

    $registry->addFieldResolver('MolloNewsResponse', 'errors',
      $builder->callback(function (MolloNewsResponse $response) {
        return $response->getViolations();
      })
    );

    $registry->addFieldResolver('MolloNews', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('MolloNews', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
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


    // Response type resolver.
    $registry->addTypeResolver('Response', [
      __CLASS__,
      'resolveResponse',
    ]);
  }

  /**
   * Resolves the response type.
   *
   * @param \Drupal\graphql\GraphQL\Response\ResponseInterface $response
   *   Response object.
   *
   * @return string
   *   Response type.
   *
   * @throws \Exception
   *   Invalid response type.
   */
  public static function resolveResponse(ResponseInterface $response): string {
    // Resolve content response.
    if ($response instanceof MolloNewsResponse) {
      return 'MolloNewsResponse';
    }
    throw new Exception('Invalid response type.');
  }

}
