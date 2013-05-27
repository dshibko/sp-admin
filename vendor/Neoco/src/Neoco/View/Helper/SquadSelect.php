<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 *
 */
class SquadSelect extends AbstractHelper
{
    /**
     * @param string $id
     * @param array $squad
     * @param string|null $default
     * @param string $attrs
     * @param int|null $scorer
     * @return string
     */
    public function __invoke($id, $squad, $default = null, $attrs = '', $scorer = null)
    {
        $html = '<select ' . $attrs . ' id="' . $id . '" name="' . $id . '">';
        if ($default != null)
            $html .= '<option value="-1">' . $default . '</option>';
        foreach ($squad as $player)
            $html .= '<option ' . ($player['id'] == $scorer ? 'selected="selected"': '') . ' value="' . $player['id'] . '">' . $player['shirtNumber'] . '. ' . $player['displayName'] . ' ' . $player['position'] . '</option>';
        $html .= '</select>';
        return $html;
    }

}