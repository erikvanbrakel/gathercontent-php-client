<?php

namespace GatherContent\DataTypes;

class ElementComponent extends Element
{
  /**
   * {@inheritdoc}
   */
  protected function initPropertyMapping()
  {
    parent::initPropertyMapping();
    $this->propertyMapping = array_replace(
      $this->propertyMapping,
      [
        'uuid' => 'id',
        'name' => 'name',
        'fields' => [
          'type' => 'closure',
          'closure' => function (array $data) {
            $elements = [];
            foreach ($data as $elementData) {
              $class = static::$type2Class[$elementData['field_type']];
              /** @var \GatherContent\DataTypes\Base $element */
              $element = new $class($elementData);
              $elements[] = $element;
            }
            },
        ],
      ]
    );

    return $this;
  }

  /**
   * Get list of children fields.
   *
   * @return array
   *   Array of fields.
   */
  public function getChildrenFields() {
    $type2Class = [
      'text' => ElementText::class,
      'attachment' => Element::class,
      'guidelines' => ElementGuideline::class,
      'choice_checkbox' => ElementCheckbox::class,
      'choice_radio' => ElementRadio::class,
    ];
    foreach ($this->data['component']['fields'] as $elementData) {
      $class = $type2Class[$elementData['field_type']];
      /** @var \GatherContent\DataTypes\Base $element */
      $element = new $class($elementData);
      $elements[] = $element;
    }
    return $elements;
  }
}
