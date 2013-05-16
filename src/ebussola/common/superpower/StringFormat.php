<?php

namespace ebussola\common\superpower;

/**
 * User: Leonardo Shinagawa
 * Date: 07/03/13
 * Time: 00:58
 */
trait StringFormat {

    /**
     * @param string $format
     * @return string
     */
    public function formatString($format) {
        $this->_assertImplementsArrayable();
        $values = $this->toArray();
        foreach ($values as $key => $value) {
            $txt = $this->_isEmpty($value) ? '%null%' : $value;
            $format = str_replace('{'.$key.'}', $txt, $format);
        }
        return strstr($format, '%null%') ? null : $format;
    }

    private function _isEmpty($value) {
        return (!is_numeric($value)) && (($value === null) || ($value == ''));
    }

    /**
     * @param array $formats
     * @param array|string $separator
     * @return string
     */
    public function joinFormats(array $formats, $separators) {
        if (!is_array($separators)) {
            $separators = array($separators);
        }

        $formats = array_values($formats);
        $formats_count = count($formats);
        foreach ($formats as $i => &$format) {
            $format = $this->formatString($format);
            if ($this->_isEmpty($format)) {
                unset($formats[$i]);
            } else {
                $format = $format.'{$'.$i.'}';
            }
            if ($i === $formats_count-1) {
                if ($format === null) {
                    $last_valid_i = 2;
                    do {
                        $format = &$formats[($formats_count-$last_valid_i)];
                        $format = str_replace('{$'.($formats_count-$last_valid_i).'}', '', $format);
                        if ($this->_isEmpty($format)) {
                            unset($formats[($formats_count-$last_valid_i)]);
                        }
                        $last_valid_i++;
                    } while ($format === '');
                } else {
                    $format = str_replace('{$'.($formats_count-1).'}', '', $format);
                }
            }
        }

        $separators_i = 0;
        $separators_count = count($separators);
        $formats = array_values($formats);
        foreach ($formats as $i => &$format) {
            $format = str_replace('{$'.$i.'}', $separators[$separators_i], $format);
            if ($separators_i === $separators_count-1) {
                $separators_i = 0;
            } else {
                $separators_i++;
            }
        }

        return join('', $formats);
    }

    public function smartFormat($format) {
        preg_match_all('/\\{.*?\\}/is', $format, $placeholders);
        $placeholders = $placeholders[0];
        foreach ($placeholders as $placeholder) {
            $format = str_replace($placeholder, '}{', $format);
        }
        $format = ltrim($format, '}');
        $format = rtrim($format, '{');
        preg_match_all('/\\{(.*?)\\}/is', $format, $matches);
        $separators = $matches[1];

        return $this->joinFormats($placeholders, $separators);
    }

    private function _assertImplementsArrayable() {
        if (!$this instanceof \ebussola\common\capacity\Arrayable) {
            throw new \BadMethodCallException('StringFormat need the class to be implemented by Arrayable');
        }
    }

}