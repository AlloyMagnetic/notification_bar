<?php
/**
 * @file
 * Contains Drupal\notification_bar\Form\NotificationBarForm.
 */
namespace Drupal\notification_bar\Form;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class HabitatStatusbarForm extends FormBase {
  function getFormId() {
    return "notification_bar_form";
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $value = \Drupal::state()->get('notification_bar');
    if (!$value) {
      $value = [
        'color' => '#000000',
        'enabled' => false,
        'message' => 'Enter a message',
        'url' => false
      ];
    }

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $value['enabled']
    ];

    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#default_value' => $value['color'],
      '#options' => [
        '#000000' => 'Black',
        '#D10000' => 'Red',
        '#FF671F' => 'Orange',
        '#00AFD7' => 'Blue',
        '#C4D600' => 'Green'
      ],
      '#required' => true
    ];

    $form['message'] = [  
      '#type' => 'textarea',  
      '#title' => $this->t('Message'),  
      '#default_value' => $value['message'],
      '#required' => true
    ];
    
    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#default_value' => $value['url']
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  function submitForm(array &$form, FormStateInterface $form_state) {
    $value = [
      'enabled' => $form_state->getValue('enabled'),
      'message' => $form_state->getValue('message'),
      'color' => $form_state->getValue('color'),
      'url' => $form_state->getValue('url')
    ];
    \Drupal::state()->set('notification_bar', $value);
    drupal_flush_all_caches();
    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
  }
}