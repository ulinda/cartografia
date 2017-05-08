<?php
/**
 * @file
 * nodo_cartografico.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function nodo_cartografico_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_main|node|nodo_cartografico|form';
  $field_group->group_name = 'group_main';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'nodo_cartografico';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Contenido',
    'weight' => '2',
    'children' => array(
      0 => 'body',
      1 => 'field_donde',
      2 => 'field_campo_disciplinario',
      3 => 'field_tipo',
      4 => 'field_node_weight',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-main field-group-tab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_main|node|nodo_cartografico|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Contenido');

  return $field_groups;
}
