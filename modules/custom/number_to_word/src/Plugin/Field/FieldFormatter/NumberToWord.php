<?php

namespace Drupal\number_to_word\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Numbers_Words;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\NumericFormatterBase;

/**
 * Plugin implementation of the 'number_to_word' formatter.
 *
 * @FieldFormatter(
 *   id = "number_to_word",
 *   label = @Translation("Number to word"),
 *   field_types = {
 *     "integer",
 *     "decimal",
 *     "float"
 *   }
 * )
 */
class NumberToWord extends NumericFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'currency' => '$',
        'locale' => 'en_US',
      ] + parent::defaultSettings();
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements['locale'] = [
      '#type' => 'select',
      '#title' => t('Locale'),
      '#options' => [
        'en_US' => t('US'),
        'en_GB' => t('GB'),
        'en_IN' => t('IN'),
      ],
      '#default_value' => $this->getSetting('locale'),
      '#weight' => 5,
    ];

    $elements['currency'] = [
      '#title' => t('Currency'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('currency'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function numberFormat($number) {
    $test = new Numbers_Words();

    $fnum = explode('.', $number);

    $num1 = abs($number);
    $ret = $this->getSetting('currency') . number_format($num1, 2) . "<br>";
    $ret .= ucwords($test->toWords($number, $this->getSetting('locale')));

    if ($num1 != $number) {
      $ret .= ' point ';
      $ret .= ucwords($test->toWords($fnum[1], $this->getSetting('locale')));
    }
    return $ret;
  }
}
