<?php

namespace GatherContent\DataTypes;

use GatherContent\DataTypes\Group;

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
                            $class = Group::$type2Class[$elementData['field_type']];
                          /** @var \GatherContent\DataTypes\Base $element */
                            $element = new $class($elementData);
                            $elements[$element->id] = $element;
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
    public function getChildrenFields()
    {
        foreach ($this->data['component']['fields'] as $elementData) {
            $class = Group::$type2Class[$elementData['field_type']];
            /** @var \GatherContent\DataTypes\Base $element */
            $element = new $class($elementData);
            $elements[$element->id] = $element;
        }
        return $elements;
    }
}
