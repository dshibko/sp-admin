<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;
use \Application\Manager\LeagueManager;

class UserLeagueMovement extends AbstractHelper
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Neoco\View\Helper\UserLeagueMovement
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @param array $league
     * @return string
     */
    public function __invoke(array $league)
    {
        $html = '';
        if (!empty($league)){
            $config = $this->serviceLocator->get('config');
            $translator = $this->serviceLocator->get('translator');
            $html = '<div class="item col1 summary left"><section><figure class="clearfix">';
            switch($league['direction']){
                case LeagueManager::USER_LEAGUE_MOVEMENT_SAME : {
                    $html .='<div class="your-place no-dynamic"><p><strong>&ndash;</strong></p></div>';
                    $html .= '<figcaption>'.sprintf($translator->translate('You didn\'t move on the %s league this match'),'"' . $league['name'] . '"').'</figcaption>';
                    break;
                }
                case LeagueManager::USER_LEAGUE_MOVEMENT_UP:{
                    $html .= '<div class="your-place">';
                    $html .=     '<img src="'.$config['move_up_image_source'].'" class="up" title="Moved Up" alt="Moved Up">';
                    $html .=     '<p><strong>'.$league['places'].'</strong> '.$translator->translate('places').'</p>';
                    $html .= '</div>';
                    $html .= '<figcaption>'.sprintf($translator->translate('You moved up %s place(s) on the %s league this match'), $league['places'], '"' . $league['name'] . '"').'</figcaption>';
                    break;
                }
                case LeagueManager::USER_LEAGUE_MOVEMENT_DOWN:{
                    $html .= '<div class="your-place">';
                    $html .=     '<p><strong>'.$league['places'].'</strong> '.$translator->translate('places').'</p>';
                    $html .=     '<img src="'.$config['move_down_image_source'].'" class="down" title="Moved Down" alt="Moved Down">';
                    $html .= '</div>';
                    $html .= '<figcaption>'.sprintf($translator->translate('You moved down %s place(s) on the %s league this match'), $league['places'], '"' . $league['name'] . '"').'</figcaption>';
                    break;
                }
            }
            $html .= '</figure></section></div>';
        }
        return $html;
    }

}