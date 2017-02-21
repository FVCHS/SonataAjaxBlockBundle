<?php

namespace Fvchs\SonataAjaxBlockBundle\Service\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class AjaxBlockService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AjaxBlockService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        // We need to use the container here since directly injecting twig produces a circular reference.
        $this->container = $container;
    }

    /**
     * Renders empty block spaceholder.
     *
     * @param BlockContextInterface $blockContext
     * @return string
     */
    public function renderBlock($blockContext) {
        $uniqid = md5(serialize($blockContext->getSettings())); //TODO is this unique enough?

        $session = $this->container->get('session');
        $this->clearSessionVarsByPrefix(sprintf('ajax_block_context_%s', $uniqid), $session);
        $session->set(sprintf('ajax_block_context_%s_%s', $uniqid, $blockContext->getBlock()->getId()), $blockContext);

        return $this->container->get('twig')->render('@FvchsSonataAjaxBlock/Block/ajax_block.html.twig', [
            'id' => sprintf('%s_%s', $uniqid, $blockContext->getBlock()->getId())
        ]);
    }

    /**
     * Removes all session variables starting with the specified prefix. Used to protect the session from being overloaded.
     *
     * @param string $prefix
     * @param Session $session
     */
    private function clearSessionVarsByPrefix($prefix, $session) {
        foreach ($session->all() as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $session->remove($key);
            }
        }
    }
}
