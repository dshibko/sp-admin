<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class FeaturedPredictionFieldset extends LanguageFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($language, $required = 0)
    {

        parent::__construct($language);

        /*----------------------Featured Prediction Start----------------------*/

        //Predictors name
        $this->add(array(
            'name' => 'prediction_name',
            'type' => 'text',
            'attributes' => array(
                'required' => $required,
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
                'required' => $required,
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
                'required' => $required,
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
        $language = $this->getData();
        foreach ($match->getMatchLanguages() as $matchLanguage) {
            if ($matchLanguage->getLanguage()->getId() == $language['id']) {
                $featuredPrediction = $matchLanguage->getFeaturedPrediction();
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