<?php

namespace Drupal\mollo_news_graphql\Plugin\GraphQL\DataProducer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\mollo_news_graphql\Plugin\GraphQL\Response\MolloNewsResponse;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a new entity.
 *
 * @DataProducer(
 *   id = "create_mollo_news",
 *   name = @Translation("Create Mollo News"),
 *   description = @Translation("Creates a new Mollo News."),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("MolloNews (Mollo)")
 *   ),
 *   consumes = {
 *     "data" = @ContextDefinition("any",
 *       label = @Translation("MolloNews data")
 *     )
 *   }
 * )
 */
class MolloNewsCreate extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * CreateMolloNews constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }

  /**
   * Creates an mollo_news.
   *
   * @param array $data
   *   The submitted values for the mollo_news.
   *
   *   The newly created mollo_news.
   *
   * @throws \Exception
   */
  public function resolve(array $data): MolloNewsResponse {
    $response = new MolloNewsResponse();
    if ($this->currentUser->hasPermission("create mollo_news content")) {
      $values = [
        'type' => 'mollo_news',
        'title' => $data['title'],
        'body' => $data['description'],
      ];
      $node = Node::create($values);
      $node->save();
      $response->setMolloNews($node);
    }
    else {
      $response->addViolation(
        $this->t('You do not have permissions to create Mollo News.')
      );
    }
    return $response;
  }

}
