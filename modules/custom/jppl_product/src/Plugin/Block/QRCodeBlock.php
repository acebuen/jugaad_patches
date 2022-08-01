<?php

namespace Drupal\jppl_product\Plugin\Block;

use Drupal\jppl_product\QRGeneratorService;
use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'QR code' block.
 *
 * @Block(
 *   id = "qr_code",
 *   admin_label = @Translation("QR Code block")
 * )
 */
class QRCodeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Class for current_route_match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * Class for JPPL Product service.
   *
   * @var \Drupal\jppl_product\QRGeneratorService
   */
  protected $qrGeneratorService;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $routeMatch, QRGeneratorService $qrGeneratorService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
    $this->qrGeneratorService = $qrGeneratorService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('jppl_product.qr_generator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface && $node->bundle() == 'product') {
      $build = [];
      $path = '';
      if (isset($node->field_app_purchase_link) && !empty($node->field_app_purchase_link->uri)) {
        $path = $this->qrGeneratorService->generateQr($node->field_app_purchase_link->uri);
      }

      if (!empty($path)) {
        $build['qr_code'] = [
          '#theme' => 'qr_code',
          '#path' => $path,
        ];
      }

      return $build;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
      return 0;
  }

}
