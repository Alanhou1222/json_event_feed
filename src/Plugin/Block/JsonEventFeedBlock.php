<?php 

namespace Drupal\json_event_feed\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;


/**
 * Provides a 'Json Event Feed' Block.
 *
 * @Block(
 *   id = "json_event_feed_block",
 *   admin_label = @Translation("Json Event Feed Block"),
 *   category = @Translation("Json Event Feed"),
 * )
 */
class JsonEventFeedBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $final_array = [
      '#theme' => 'json_event_feed_block_theme',
      '#attached' => [
        'library' => [
          'json_event_feed/block',
        ],
      ],
    ];
    $config = $this->getConfiguration();
    $final_array['#attached']['drupalSettings']['field']['url'] = $config['url'];
    $final_array['#attached']['drupalSettings']['field']['title'] = $config['fields'][$config['title']];
    $final_array['#attached']['drupalSettings']['field']['subtitle'] = $config['fields'][$config['subtitle']];
    $final_array['#attached']['drupalSettings']['field']['image_url'] = $config['fields'][$config['image_url']];
    $final_array['#attached']['drupalSettings']['field']['date'] = $config['fields'][$config['date']];
    $final_array['#attached']['drupalSettings']['field']['time_start'] = $config['fields'][$config['time_start']];
    $final_array['#attached']['drupalSettings']['field']['time_end'] = $config['fields'][$config['time_end']];
    $final_array['#attached']['drupalSettings']['field']['location'] = $config['fields'][$config['location']];
    $final_array['#attached']['drupalSettings']['field']['links'] = $config['fields'][$config['links']];
    $final_array['#attached']['drupalSettings']['field']['description'] = $config['fields'][$config['description']];
    $final_array['#attached']['drupalSettings']['field']['page_link'] = $config['fields'][$config['page_link']];
    $final_array['#attached']['drupalSettings']['field']['type'] = $config['fields'][$config['type']];
    $final_array['#attached']['drupalSettings']['field']['tags'] = $config['fields'][$config['tags']];
    $final_array['#attached']['drupalSettings']['field']['popup'] = $config['popup'];
    $final_array['#attached']['drupalSettings']['field']['search'] = $config['search'];
    $final_array['#attached']['drupalSettings']['field']['wide'] = $config['wide'];
    $final_array['#attached']['drupalSettings']['field']['elementWidth'] = $config['elementWidth'];
    $final_array['#attached']['drupalSettings']['field']['elementPerPage'] = $config['elementPerPage'];
    $final_array['#attached']['drupalSettings']['field']['linkToEventsListingPage'] = $config['linkToEventsListingPage'];
    $final_array['#attached']['drupalSettings']['field']['linkToEventsListingPageText'] = $config['linkToEventsListingPageText'];
    $image = $this->configuration['placeholderImage'];
    if (!empty($image[0])) {
      if ($file = File::load($image[0])) {
        $build['placeholderImage'] = [
          '#theme' => 'image_style',
          '#style_name' => 'medium',
        ];
      }
    }
    $final_array['#attached']['drupalSettings']['field']['placeholderTitle'] = $config['placeholderTitle'];
    $final_array['#attached']['drupalSettings']['field']['placeholderImage'] = $file->createFileUrl();
    $final_array['#attached']['drupalSettings']['field']['placeholderUrl'] = $config['placeholderUrl'];

    return $final_array;
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    
    $form['#prefix'] = '<div id="json_event_feed_block_form">';

    $form['#suffix'] = '</div>';

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('url'),
      '#description' => $this->t('url of json file'),
      "#weight" => -2,
      '#default_value' => $config['url'] ?? '',
    ];

    $form['actions'] = [
      '#type' => 'actions',
      "#weight" => -1,
    ];

    $form['actions']['get_data_button'] = [
      '#type' => 'button',
      '#value' => $this->t('Get Data'),
      '#ajax' => [
        'callback' => [$this, 'getData'],
        'wrapper' => 'json_event_feed_block_form',
        'event' => 'click',
        'disable-refocus' => FALSE,
      ]
    ];

    $form['title'] = [
      '#type' => 'select',
      '#title' => $this->t('title'),
      '#description' => $this->t('key of event title'),
      '#options' => $config['fields'],
      '#default_value' => $config['title'] ?? 0,
    ];

    $form['subtitle'] = [
      '#type' => 'select',
      '#title' => $this->t('subtitle'),
      '#description' => $this->t('key of event subtitle'),
      '#options' => $config['fields'],
      '#default_value' => $config['subtitle'] ?? 0,
    ];

    $form['image_url'] = [
      '#type' => 'select',
      '#title' => $this->t('image url'),
      '#description' => $this->t('key of event image url'),
      '#options' => $config['fields'],
      '#default_value' => $config['image_url'] ?? 0,
    ];

    $form['date'] = [
      '#type' => 'select',
      '#title' => $this->t('date'),
      '#description' => $this->t('key of event date'),
      '#options' => $config['fields'],
      '#default_value' => $config['date'] ?? 0,
    ];

    $form['time_start'] = [
      '#type' => 'select',
      '#title' => $this->t('start time'),
      '#description' => $this->t('key of event start time'),
      '#options' => $config['fields'],
      '#default_value' => $config['time_start'] ?? 0,
    ];
    
    $form['time_end'] = [
      '#type' => 'select',
      '#title' => $this->t('end time'),
      '#description' => $this->t('key of event end time'),
      '#options' => $config['fields'],
      '#default_value' => $config['time_end'] ?? 0,
    ];

    $form['location'] = [
      '#type' => 'select',
      '#title' => $this->t('location'),
      '#description' => $this->t('key of event location'),
      '#options' => $config['fields'],
      '#default_value' => $config['location'] ?? 0,
    ];

    $form['links'] = [
      '#type' => 'select',
      '#title' => $this->t('links'),
      '#description' => $this->t('key of event links'),
      '#options' => $config['fields'],
      '#default_value' => $config['links'] ?? 0,
    ];
    
    $form['description'] = [
      '#type' => 'select',
      '#title' => $this->t('description'),
      '#description' => $this->t('key of event description'),
      '#options' => $config['fields'],
      '#default_value' => $config['description'] ?? 0,
    ];

    $form['page_link'] = [
      '#type' => 'select',
      '#title' => $this->t('page link'),
      '#description' => $this->t('key of event page link'),
      '#options' => $config['fields'],
      '#default_value' => $config['page_link'] ?? 0,
    ];

    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('type'),
      '#description' => $this->t('key of event type'),
      '#options' => $config['fields'],
      '#default_value' => $config['type'] ?? 0,
    ];

    $form['tags'] = [
      '#type' => 'select',
      '#title' => $this->t('tags'),
      '#description' => $this->t('key of event tags'),
      '#options' => $config['fields'],
      '#default_value' => $config['tags'] ?? 0,
    ];

    $form['linkToEventsListingPage'] = [
      '#type' => 'textfield',
      '#title' => $this->t('link to events listing page'),
      '#default_value' => $config['linkToEventsListingPage'] ?? '',
    ];

    $form['linkToEventsListingPageText'] = [
      '#type' => 'textfield',
      '#title' => $this->t('text for link to events listing page'),
      '#default_value' => $config['linkToEventsListingPageText'] ?? '',
    ];

    $form['placeholderTitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('title for placeholder'),
      '#default_value' => $config['placeholderTitle'] ?? '',
    ];

    $form['placeholderImage'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('image for placeholder'),
      '#name' => 'placeholderImage',
      '#default_value' => $config['placeholderImage'],
      '#upload_location' => 'public://placeholderImage',
    ];

    $form['placeholderUrl'] = [
      '#type' => 'textfield',
      '#title' => $this->t('url for placeholder'),
      '#default_value' => $config['placeholderUrl'] ?? '',
    ];

    $form['popup'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('popup'),
      '#default_value' => $config['popup'] ?? 1,
    ];

    $form['search'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('search function'),
      '#default_value' => $config['search'] ?? 1,
    ];

    $form['wide'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('wide view'),
      '#default_value' => $config['wide'] ?? 0,
    ];

    $form['elementWidth'] = [
      '#type' => 'textfield',
      '#title' => $this->t('element width'),
      '#description' => $this->t('wide view will multiply field value by 1.6 automatically'),
      '#attribute' => [
        '#type' => 'number',
      ],
      '#default_value' => $config['elementWidth'] ?? 300,
    ];

    $form['elementPerPage'] = [
      '#type' => 'textfield',
      '#title' => $this->t('element per page'),
      '#description' => $this->t('event element per page'),
      '#attribute' => [
        '#type' => 'number',
      ],
      '#default_value' => $config['elementPerPage'] ?? 7,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['url'] = $values['url'];
    $json = file_get_contents($this->configuration['url']);
    $obj = json_decode($json)[0];
    $keys = array_keys((array)$obj);
    $this->configuration['fields'] = array_merge(['N/A'], $keys);
    $this->configuration['title'] = $values['title'];
    $this->configuration['subtitle'] = $values['subtitle'];
    $this->configuration['image_url'] = $values['image_url'];
    $this->configuration['date'] = $values['date'];
    $this->configuration['time_start'] = $values['time_start'];
    $this->configuration['time_end'] = $values['time_end'];
    $this->configuration['location'] = $values['location'];
    $this->configuration['links'] = $values['links'];
    $this->configuration['description'] = $values['description'];
    $this->configuration['page_link'] = $values['page_link'];
    $this->configuration['type'] = $values['type'];
    $this->configuration['tags'] = $values['tags'];
    $this->configuration['popup'] = $values['popup'];
    $this->configuration['search'] = $values['search'];
    $this->configuration['wide'] = $values['wide'];
    $this->configuration['elementWidth'] = $values['elementWidth'];
    $this->configuration['elementPerPage'] = $values['elementPerPage'];
    $this->configuration['linkToEventsListingPage'] = $values['linkToEventsListingPage'];
    $this->configuration['linkToEventsListingPageText'] = $values['linkToEventsListingPageText'];
    // Save image as permanent.
    $image = $values['placeholderImage'];
    if ($image != $this->configuration['image']) {
      if (!empty($image[0])) {
        $file = File::load($image[0]);
        $file->setPermanent();
        $file->save();
      }
    }
    $this->configuration['placeholderTitle'] = $values['placeholderTitle'];
    $this->configuration['placeholderImage'] = $values['placeholderImage'];
    $this->configuration['placeholderUrl'] = $values['placeholderUrl'];
  }
  
  /**
   * Ajax call
   */
  public function getData(array &$form, FormStateInterface $form_state) {

    $this->configuration['url'] = $form_state->getValues()['settings']['url'];
    $json = file_get_contents($this->configuration['url']);
    $obj = json_decode($json)[0];
    $keys = array_keys((array)$obj);
    $this->configuration['fields'] = array_merge(['N/A'], $keys);
    $form['settings']['title']['#options'] = $this->configuration['fields'];
    $form['settings']['subtitle']['#options'] = $this->configuration['fields'];
    $form['settings']['image_url']['#options'] = $this->configuration['fields'];
    $form['settings']['date']['#options'] = $this->configuration['fields'];
    $form['settings']['time_start']['#options'] = $this->configuration['fields'];
    $form['settings']['time_end']['#options'] = $this->configuration['fields'];
    $form['settings']['location']['#options'] = $this->configuration['fields'];
    $form['settings']['links']['#options'] = $this->configuration['fields'];
    $form['settings']['description']['#options'] = $this->configuration['fields'];
    $form['settings']['page_link']['#options'] = $this->configuration['fields'];
    $form['settings']['type']['#options'] = $this->configuration['fields'];
    $form['settings']['tags']['#options'] = $this->configuration['fields'];
    return $form['settings'];

  }
}