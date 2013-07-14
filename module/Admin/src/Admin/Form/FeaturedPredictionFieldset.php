<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class FeaturedPredictionFieldset extends RegionFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($region)
    {

        parent::__construct($region);

        /*----------------------Featured Prediction Start----------------------*/

        //Predictors name
        $this->add(array(
            'name' => 'prediction_name',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_TITLE_LENGTH,
            ),
            'options' => array(
                'label' => 'Predictor Name',
            ),
        ));

        //Prediction
        $this->add(array(
            'name' => 'prediction_copy',
            'type' => 'textarea',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Prediction',
            ),
        ));

        //Image
        $this->add(array(
            'name' => 'prediction_image',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Image of the predictor',
            ),
        ));

        /*----------------------Featured Prediction End------------------------*/

    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match)
    {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getLanguage()->getId() == $region['id']) {
                $featuredPrediction = $matchRegion->getFeaturedPrediction();
                //Featured Prediction
                if ($featuredPrediction) {
                    $this->get('prediction_name')->setValue($featuredPrediction->getName());
                    $this->get('prediction_copy')->setValue($featuredPrediction->getCopy());
                    $this->get('prediction_image')->setValue($featuredPrediction->getImagePath());
                }
            }
        }
    }
}