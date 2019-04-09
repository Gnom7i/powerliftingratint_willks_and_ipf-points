<?php

/**
 * Yandex Turbo settings form
 * @return array
 */
function yandex_turbo_settings()
{
    global $base_url;

    $form = array();

    $args = array(
        '!url' => 'https://yandex.ru/support/webmaster/turbo/feed.html#quota',
        '@title' => 'Yandex.Turbo RSS quota'
    );

    $form['about'] = array(
      '#prefix' => theme_html_tag(array(
          'element' => array(
              '#tag' => 'h1',
              '#value' => t('Yandex Turbo settings'),
          ),
      )),
      '#markup' => '<p>' . t('Note, that you can only have 500 records exported. 
            Find out more about <a href="!url">@title</a>.', $args) . '</p><p>'
          . t('RSS feed:') . ' ' . $base_url . '/yandex.turbo.rss</p><p>&nbsp;</p>'

    );

    $form['yandex_turbo_node_types'] = array(
        '#title' => t('Available node types'),
        '#type' => 'checkboxes',
        '#description' => t('Select node types for Yandex Turbo RSS export'),
        '#options' => yandex_turbo_node_type_options(),
        '#default_value' => variable_get('yandex_turbo_node_types', array())
    );

    $form['yandex_turbo_promoted'] = array(
        '#title' => t('Sticky'),
        '#description' => 'Respect promoted to front page option',
        '#type' => 'select',
        '#options' => drupal_map_assoc(array(t('Yes'), t('No'))),
        '#default_value' => variable_get('yandex_turbo_promoted', 'No')
    );

    $form['yandex_turbo_sticky'] = array(
        '#title' => t('Sticky'),
        '#description' => 'Respect sticky at top of lists',
        '#type' => 'select',
        '#options' => drupal_map_assoc(array(t('Yes'), t('No'))),
        '#default_value' => variable_get('yandex_turbo_sticky', 'No')
    );

    $form['yandex_turbo_order_by'] = array(
        '#title' => t('Order by'),
        '#type' => 'select',
        '#options' => array(
            'created' => t('Date created'),
            'changed' => t('Date changed'),
            'title'   => t('Title')
        ),
        '#default_value' => variable_get('yandex_turbo_order_by', 'created')
    );

    $form['yandex_turbo_order'] = array(
        '#title' => t('Order'),
        '#type' => 'select',
        '#options' => drupal_map_assoc(array(t('ASC'), t('DESC'))),
        '#default_value' => variable_get('yandex_turbo_order', 'DESC')
    );

    $form['yandex_turbo_body'] = array(
        '#title' => t('Display'),
        '#description' => t('Choose turbo:content data source field'),
        '#type' => 'select',
        '#options' => array(
            'value' => t('Body'),
            'summary' => t('Summary'),
        ),
        '#default_value' => variable_get('yandex_turbo_body', 'value')
    );

    $form['yandex_turbo_rss_title'] = array(
        '#title' => t('RSS Title'),
        '#type' => 'textfield',
        '#required' => TRUE,
        '#default_value' => variable_get('yandex_turbo_rss_title', '')
    );

    $form['yandex_turbo_rss_description'] = array(
        '#title' => t('RSS description'),
        '#type' => 'textarea',
        '#required' => TRUE,
        '#default_value' => variable_get('yandex_turbo_rss_description', '')
    );

    $form['yandex_turbo_rss_analytics_type'] = array(
        '#title' => t('Analytics type'),
        '#type' => 'select',
        '#options' => drupal_map_assoc(array(
            t('Yandex'),
            t('LiveInternet'),
            t('Google'),
            t('MailRu'),
            t('Rambler'),
            t('Mediascope'),
        )),
        '#default_value' => variable_get('yandex_turbo_rss_analytics_type', 'Yandex')
    );

    $form['yandex_turbo_rss_analytics_id'] = array(
        '#title' => t('Analytics id'),
        '#type' => 'textfield',
        '#default_value' => variable_get('yandex_turbo_rss_analytics_id', '')
    );

    return system_settings_form($form);
}

/**
 * Form api options list for available node types
 * @return array
 */
function yandex_turbo_node_type_options()
{
    $options = array();
    $node_types = node_type_get_types();

    if(!empty($node_types))
    {
        foreach ($node_types as $type)
        {
            $options[$type->type] = t($type->name);
        }
    }

    return $options;
}