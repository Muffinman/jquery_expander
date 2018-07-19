<?php
/**
 * @file
 * Contains the jQuery Expander class.
 */

namespace Drupal\jquery_expander\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The jquery expander formatter.
 *
 * @FieldFormatter(
 *   id = "jquery_expander",
 *   module = "jquery_expander",
 *   label = @Translation("jQuery Expander formatter"),
 *   field_types = {
 *     "string_long",
 *     "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class JQueryExpander extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'expandText' => t('Expand') . ' »',
      'expandPrefix' => '...',
      'collapseTimer' => FALSE,
      'slicePoint' => 50,
      'userCollapseText' => t('Collapse'),
      'userCollapsePrefix' => '',
      'moreClass' => 'read-more',
      'lessClass' => 'read-less',
      'moreLinkClass' => 'more-link',
      'lessLinkClass' => 'less-link',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $element = [];

    $element['expandText'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Expand text'),
      '#description' => $this->t('Text displayed in a link instead of the hidden part of the element. Clicking this will expand/show the hidden/collapsed text.'),
      '#default_value' => $this->getSetting('expandText'),
    ];

    $element['expandPrefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Expand prefix'),
      '#description' => $this->t('Text to come before the expand link.'),
      '#default_value' => $this->getSetting('expandPrefix'),
    ];

    $element['slicePoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Slice point'),
      '#description' => $this->t('Text size in charachters before the expand link.'),
      '#default_value' => $this->getSetting('slicePoint'),
      '#element_validate' => array('_element_validate_integer_positive'),
    ];

    $element['collapseTimer'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Collapse timer'),
      '#description' => $this->t('Number of milliseconds after text has been expanded at which to collapse the text again.'),
      '#default_value' => $this->getSetting('collapseTimer'),
      '#element_validate' => array('_element_validate_integer_positive'),
    ];

    $element['userCollapseText'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Collapse text'),
      '#description' => $this->t('Text to use for the link to re-collapse the text.'),
      '#default_value' => $this->getSetting('userCollapseText'),
    ];

    $element['userCollapsePrefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Collapse prefix'),
      '#description' => $this->t('Text to come before the re-collapse link.'),
      '#default_value' => $this->getSetting('userCollapsePrefix'),
    ];

    $element['moreClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('More class'),
      '#description' => $this->t('Classes for the span around the read more link.'),
      '#default_value' => $this->getSetting('moreClass'),
    ];

    $element['lessClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Less class'),
      '#description' => $this->t('Classes for the span around the read less link.'),
      '#default_value' => $this->getSetting('lessClass'),
    ];

    $element['moreLinkClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('More link class'),
      '#description' => $this->t('Classes for the read more link anchor.'),
      '#default_value' => $this->getSetting('moreLinkClass'),
    ];

    $element['lessLinkClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Less link class'),
      '#description' => $this->t('Classes for the read less link anchor.'),
      '#default_value' => $this->getSetting('lessLinkClass'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = $this->t('Expand text: @expandText', ['@expandText' => $this->getSetting('expandText')]);
    $summary[] = $this->t('Expand prefix: @expandPrefix', ['@expandPrefix' => $this->getSetting('expandPrefix')]);
    $summary[] = $this->t('Slice point: @slicePoint', ['@slicePoint' => $this->getSetting('slicePoint')]);
    $summary[] = $this->t('Collapse timer: @collapseTimer', ['@collapseTimer' => $this->getSetting('collapseTimer')]);
    $summary[] = $this->t('User collapse text: @userCollapseText', ['@userCollapseText' => $this->getSetting('userCollapseText')]);
    $summary[] = $this->t('User collapse prefix: @userCollapsePrefix', ['@userCollapsePrefix' => $this->getSetting('userCollapsePrefix')]);
    $summary[] = $this->t('More class: @moreClass', ['@moreClass' => $this->getSetting('moreClass')]);
    $summary[] = $this->t('Less class: @lessClass', ['@lessClass' => $this->getSetting('lessClass')]);
    $summary[] = $this->t('More link class: @morelinkClass', ['@morelinkClass' => $this->getSetting('morelinkClass')]);
    $summary[] = $this->t('Less link class: @lessLinkClass', ['@lessLinkClass' => $this->getSetting('lessLinkClass')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = [
        '#type' => 'markup',
        '#markup' => $item->value,
        '#prefix' => '<div class="field-expander field-expander-' . $delta . '">',
        '#suffix' => '</div>',
        '#attached' => [
          'library' => [
            'jquery_expander/jquery_expander',
            'jquery_expander/jquery_expander_integration',
          ],
          'drupalSettings' => [
            'jqueryExpander' => [
              $delta => $this->getSettings(),
            ],
          ],
        ],
      ];
    }

    return $element;
  }

}
