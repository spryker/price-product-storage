<?php

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Translation\Business\TranslatorInterface;

class TranslationFacade extends AbstractFacade
{
    /**
     * @param string $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->getDependencyContainer()
            ->getTranslator($locale)
            ->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->getDependencyContainer()
            ->getTranslator($locale)
            ->transchoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * @param string $locale
     * @return TranslatorInterface
     */
    public function getTranslator($locale)
    {
        return $this->getDependencyContainer()->getTranslator($locale);
    }
}