<?php

namespace Library\Bundle\AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class EmptyStringTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return (null === $value) ? '' : $value;
    }
}
