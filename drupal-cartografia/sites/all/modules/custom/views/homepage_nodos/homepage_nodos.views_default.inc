<?php
/**
 * @file
 * homepage_nodos.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function homepage_nodos_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'homepage_nodos';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Homepage nodos';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Homepage nodos';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['hide_empty'] = TRUE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Tipo */
  $handler->display->display_options['fields']['field_tipo']['id'] = 'field_tipo';
  $handler->display->display_options['fields']['field_tipo']['table'] = 'field_data_field_tipo';
  $handler->display->display_options['fields']['field_tipo']['field'] = 'field_tipo';
  $handler->display->display_options['fields']['field_tipo']['label'] = 'Tipo de nodo';
  /* Field: Content: Campo disciplinario */
  $handler->display->display_options['fields']['field_campo_disciplinario']['id'] = 'field_campo_disciplinario';
  $handler->display->display_options['fields']['field_campo_disciplinario']['table'] = 'field_data_field_campo_disciplinario';
  $handler->display->display_options['fields']['field_campo_disciplinario']['field'] = 'field_campo_disciplinario';
  /* Field: Content: Dónde */
  $handler->display->display_options['fields']['field_donde']['id'] = 'field_donde';
  $handler->display->display_options['fields']['field_donde']['table'] = 'field_data_field_donde';
  $handler->display->display_options['fields']['field_donde']['field'] = 'field_donde';
  /* Field: Content: relacion */
  $handler->display->display_options['fields']['field_relacion']['id'] = 'field_relacion';
  $handler->display->display_options['fields']['field_relacion']['table'] = 'field_data_field_relacion';
  $handler->display->display_options['fields']['field_relacion']['field'] = 'field_relacion';
  $handler->display->display_options['fields']['field_relacion']['label'] = 'relacionado';
  $handler->display->display_options['fields']['field_relacion']['type'] = 'relation_otherendpoint';
  $handler->display->display_options['fields']['field_relacion']['delta_offset'] = '0';
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'nodo_cartografico' => 'nodo_cartografico',
  );

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block');
  $export['homepage_nodos'] = $view;

  return $export;
}
