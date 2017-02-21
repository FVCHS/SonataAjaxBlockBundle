<?php

namespace Fvchs\SonataAjaxBlockBundle\Controller\Block;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reads previously stored block information from the session and returns the blocks contents.
 */
class AjaxBlockController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBlockAction(Request $request)
    {
        $id = $request->get('id');
        $sessionVariable = sprintf('ajax_block_context_%s', $id);

        // Get context from session
        $session = $this->get('session');
        $blockContext = $session->get($sessionVariable);

        // Get right service for current block
        $type = $blockContext->getBlock()->getType();
        $manager = $this->get('sonata.block.manager');
        $service = $manager->getService($type);

        // Run Service as used to
        return $service->execute($blockContext);
    }
}
