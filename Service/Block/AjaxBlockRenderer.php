<?php

namespace Fvchs\SonataAjaxBlockBundle\Service\Block;

use Psr\Log\LoggerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BlockRenderer;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\BlockBundle\Exception\Strategy\StrategyManagerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the execution and rendering of a block. Overrides the default BlockRenderer with ajax behaviour.
 */
class AjaxBlockRenderer extends BlockRenderer
{
    /**
     * @var AjaxBlockService
     */
    private $ajaxBlockService;

    /** Constructor.
     * @param BlockServiceManagerInterface $blockServiceManager
     * @param StrategyManagerInterface     $exceptionStrategyManager
     * @param LoggerInterface|null         $logger
     * @param bool                         $debug
     * @param AjaxBlockService|null        $blockService
     */
    public function __construct(
        BlockServiceManagerInterface $blockServiceManager,
        StrategyManagerInterface $exceptionStrategyManager,
        LoggerInterface $logger = null,
        $debug = false,
        AjaxBlockService $blockService = null
    ) {
        parent::__construct($blockServiceManager, $exceptionStrategyManager, $logger, $debug);

        $this->ajaxBlockService = $blockService;
    }

    /**
     * {@inheritdoc}
     */
    public function render(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();

        // if this is an ajax block, render the preview Block now
        if ($this->getUseAjax($block)) {
            return new Response($this->ajaxBlockService->renderBlock($blockContext));
        }

        return parent::render($blockContext, $response);
    }

    /**
     * Checks if $block has the ajax attribute set to true.
     *
     * @param BlockInterface $block
     *
     * @return bool
     */
    private function getUseAjax($block)
    {
        $settings = $block->getSettings();

        if (array_key_exists('attr', $settings)) {
            $attributes = $settings['attr'];

            foreach ($attributes as $attr) {
                foreach ($attr as $key => $value) {
                    if ($key == 'ajax') {
                        return $value;
                    }
                }
            }
        }

        return false;
    }
}
