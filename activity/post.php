<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * EasyDiscuss post activity class.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanEasydiscussActivityPost extends ComLogmanModelEntityActivity
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'format' => '{actor} {action} {object.subtype} {object.type} title {object}',
            'object_table'  => 'discuss_posts'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $config->append(array(
            'url'     => 'option=com_easydiscuss&view=post&task=edit&id=' . $this->row,
            'subtype' => array('object' => true, 'objectName' => 'EasyDiscuss')
        ));

        parent::_objectConfig($config);
    }

    protected function _actionConfig(KObjectConfig $config)
    {
        if ($this->verb == 'add') {
            $config->append(array('objectName' => 'added'));
        }

        parent::_actionConfig($config);
    }
}