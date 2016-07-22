<?php
/**
 * @author Joppe Aarts <joppe@zicht.nl>
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Tool\Plugin\Systemjs;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Zicht\Tool\Plugin as BasePlugin;

/**
 * Class Plugin
 *
 * @package Zicht\Tool\Plugin\Watch
 */
class Plugin extends BasePlugin
{
    /**
     * @param ArrayNodeDefinition $rootNode
     * @return void
     */
    public function appendConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('watch')
                    ->children()
                        ->scalarNode('config')
                            ->defaultValue('zwatch.json')
                        ->end()
                        ->scalarNode('zwatch')
                            ->defaultValue('./node_modules/zwatch/bin/zwatch')
                            ->beforeNormalization()
                                ->always(function($v) {
                                    if (is_file($v)) {
                                        return realpath($v);
                                    }
                                })
                                ->end()
                            ->validate()
                                ->ifTrue(function($f) {
                                    return !is_file($f);
                                })
                                ->thenInvalid('File does not exist. Do you need to install zwatch? npm install git+ssh://git@git.zicht.nl:zicht/zwatch.git')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
