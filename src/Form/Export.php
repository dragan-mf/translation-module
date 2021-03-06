<?php

/**
 * @copyright   (c) 2014-16, Vrok
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace TranslationModule\Form;

use Vrok\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Form to set the export options.
 */
class Export extends Form implements InputFilterProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setName('translation-export');

        $translationRepository = $this->getEntityManager()
                ->getRepository('TranslationModule\Entity\Translation');
        $entryRepository = $this->getEntityManager()
                ->getRepository('TranslationModule\Entity\Entry');

        $module = $entryRepository->getFormElementDefinition('module');
        unset($module['attributes']['required']);
        $module['options']['empty_option'] = 'view.all';
        $this->add($module);

        $language = $translationRepository->getFormElementDefinition('language');
        unset($language['attributes']['required']);
        $language['options']['empty_option'] = 'view.all';
        $this->add($language);

        $this->add([
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'form.submit',
                'id'    => 'submit',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilterSpecification()
    {
        $entryRepository = $this->getEntityManager()
                ->getRepository('TranslationModule\Entity\Entry');
        $moduleSpec               = $entryRepository->getInputSpecification('module');
        $moduleSpec['required']   = false;
        $moduleSpec['allowEmpty'] = true;

        $translationRepository = $this->getEntityManager()
                ->getRepository('TranslationModule\Entity\Translation');
        $languageSpec               = $translationRepository->getInputSpecification('language');
        $languageSpec['required']   = false;
        $languageSpec['allowEmpty'] = true;

        return [
            $moduleSpec,
            $languageSpec,
        ];
    }
}
