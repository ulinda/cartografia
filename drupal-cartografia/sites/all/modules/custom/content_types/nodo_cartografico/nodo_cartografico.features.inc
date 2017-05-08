<?php
/**
 * @file
 * nodo_cartografico.features.inc
 */

/**
 * Implements hook_node_info().
 */
function nodo_cartografico_node_info() {
  $items = array(
    'nodo_cartografico' => array(
      'name' => t('Nodo cartografico'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
