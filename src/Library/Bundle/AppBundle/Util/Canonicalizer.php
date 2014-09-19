<?php

namespace Library\Bundle\AppBundle\Util;

class Canonicalizer
{
    /**
     * {@inheritdoc}
     */
    static public function canonicalize($input)
    {
        $result = static::translate($input);

        return (null === $result) ? null : mb_convert_case($result, MB_CASE_LOWER, mb_detect_encoding($result));
    }

    /**
     * Perform character replacements for special characters on the input string.
     *
     * @param string $input
     * @return string|null
     */
    static private function translate($input)
    {
        if (!is_null($input)) {
            $input = preg_replace('/[\ `~!@#\$%\^&\*\(\)\-=\+\\\|\[\]{};:\'",<\.>\/\?]/', '_', trim($input));
        }

        return $input;
    }
}
