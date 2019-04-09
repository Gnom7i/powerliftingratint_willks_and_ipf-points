<?php

/**
 * Page callback: Generates an Yandex Turbo RSS
 *
 * @return string
 *   An XML formatted string.
 */
function yandex_turbo_page_rss()
{
    $feeds      = array();
    $settings   = variable_get('yandex_turbo_node_types');
    $node_types = !empty($settings) ? array_keys(array_filter($settings)) : array();
    $orderBy    = variable_get('yandex_turbo_order_by', 'created');
    $order      = variable_get('yandex_turbo_order', 'DESC');
    $source     = variable_get('yandex_turbo_body', 'value');

    if($node_types)
    {
        $query = db_select('node', 'n')
            ->fields('n', array('nid', 'title', 'created'))
            ->condition('status', NODE_PUBLISHED)
            ->condition('type', $node_types, 'IN');

        $query->leftJoin('field_data_body', 'd', '(d.entity_id = n.nid AND d.entity_type = :node)', array(
            ':node' => 'node'
        ));

        $query->addField('d', 'body_' . $source, 'body');

        $query->leftjoin('url_alias', 'ua', 'ua.source = CONCAT(:str, n.nid)', array(
            ':str'=>'node/'
        ));

        $query->fields('ua', array('alias'));

        if('Yes' == variable_get('yandex_turbo_sticky', 'No'))
        {
            $query->orderBy('n.sticky', 'DESC');
        }

        if('Yes' == variable_get('yandex_turbo_promoted', 'No'))
        {
            $query->orderBy('n.promote', 'DESC');
        }

        $query->orderBy('n.' . $orderBy, $order)
            ->range(0, 500);

        $query->where('d.body_' . $source . ' != :empty', array(':empty' => ''));

        $feeds = $query->execute()
            ->fetchAllAssoc('nid', PDO::FETCH_ASSOC);
    }

    return theme('yandex_turbo_page_rss', array('feeds' => $feeds));
}

/**
 * Prints the RSS page for a feed.
 *
 * @param $variables
 *   An associative array containing:
 *   - feeds: An array of the feeds to theme.
 *
 * @return string
 *
 * @ingroup themeable
 */
function theme_yandex_turbo_page_rss($variables)
{
    /**
     * Feed absolute link
     */
    $rss_link = url('yandex.turbo.rss', array('absolute' => TRUE));

    /**
     * Feeds data
     *
     * @var array
     */
    $feeds = $variables['feeds'];

    /**
     * Allowed HTMl tags
     * @see https://yandex.ru/support/webmaster/turbo/feed.html#rss-elements__item
     */
    $allowed_tags = array(
        'figure', 'h1', 'h2', 'p', 'br', 'ul', 'ol', 'li', 'b', 'strong', 'i', 'em', 'sup', 'sub',
        'ins', 'del', 'small', 'big', 'pre', 'abbr', 'u', 'a', 'img', 'figcaption', 'video', 'figure'
    );

    /**
     * Removes CDATA tag from content
     *
     * @var string
     */
    $CDATA = '/^\s*\/\/<!\[CDATA\[([\s\S]*)\/\/\]\]>\s*\z/';

    /**
     * Items XML-string
     *
     * @var string
     */
    $items = '';

    /**
     * @vat string
     */
    $output = '';

    /**
     * Channel settings
     */
    $site_name           = variable_get('site_name', '');
    $site_slogan         = variable_get('site_slogan', 'Site description');
    $channel_title       = variable_get('yandex_turbo_rss_title', $site_name);
    $channel_description = variable_get('yandex_turbo_rss_description', $site_slogan);
    $channel_analytics   = variable_get('yandex_turbo_rss_analytics_type',   'Yandex');
    $channel_analyticsid = variable_get('yandex_turbo_rss_analytics_id',   '');

    if(!empty($feeds))
    {
        foreach ($feeds as $nid => $node)
        {
            $body = drupal_html_to_text( trim($node['body']), $allowed_tags );
            $body = preg_replace( $CDATA, '$1', $body );

            // Skip empty nodes
            if(!$body) continue;

            $node_uri = $node['alias'] ? $node['alias'] : 'node/' . $nid;

            $data = array(
                array(
                    'key' => 'title',
                    'value' => check_plain($node['title']),
                ),
                array(
                    'key' => 'link',
                    'value' => url( $node_uri, array('absolute' => TRUE) ),
                ),

                array(
                    'key' => 'pubDate',
                    'value' => date(DateTime::RFC822, $node['created']),
                ),
                array(
                    'key' => 'turbo:content',
                    'value' => '<![CDATA[' . PHP_EOL . $body . PHP_EOL . ']]>',
                    'encoded' => TRUE,
                )
            );

            $items .= format_xml_elements(array(
                array(
                    'key'     => 'item',
                    'value'   => PHP_EOL . format_xml_elements($data),
                    'encoded' => TRUE,
                    'attributes' => array(
                        'turbo' => 'true',
                    )
                )
            ));
        }

        $body = $node = $node_uri = $header = $h1 = null;
    }

    drupal_add_http_header('Content-Type', 'application/rss+xml; charset=utf-8');

    if(!empty(trim($channel_analyticsid)))
    {
        $is_liveinternet = ('LiveInternet' == $channel_analytics);
        $param = $is_liveinternet ? 'param' : 'id';

        $analytics_attributes = array(
            'type' => $channel_analytics,
            $param => check_plain($channel_analyticsid),
        );

        $analytics = format_xml_elements(array(
            array(
                'key' => 'yandex:analytics',
                'value' => '',
                'attributes' => $analytics_attributes
            )
        ));

        $items = $analytics . $items;
    }

    $rss = array(
        'key' => 'rss',
        'value' => PHP_EOL . format_rss_channel( $channel_title, $rss_link, $channel_description, $items ),
        'encoded' => TRUE,
        'attributes' => array(
            'xmlns:yandex' => 'http://news.yandex.ru',
            'xmlns:media' => 'http://search.yahoo.com/mrss/',
            'xmlns:turbo' => 'http://search.yahoo.com/mrss/',
            'version' => '2.0',
        ),
    );

    print "<?xml version=\"1.0\" encoding=\"utf-8\"?>"
        . PHP_EOL
        . format_xml_elements( array( $rss ) );
}