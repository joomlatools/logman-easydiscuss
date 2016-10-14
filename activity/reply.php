<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * EasyDiscuss reply activity class.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanEasydiscussActivityReply extends ComLogmanModelEntityActivity
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'format' => '{actor} {action} {object.type} {target.subtype} {target} {target.type}',
            'object_table'  => 'discuss_posts'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $config->append(array(
            'url'  => array('admin' => 'option=com_easydiscuss&view=post&task=edit&id=' . $this->row),
            'type' => array(
                'url'  => 'option=com_easydiscuss&view=post&task=edit&id=' . $this->row,
                'find' => 'object'
            )
        ));

        parent::_objectConfig($config);
    }

    public function getPropertyTarget()
    {
        $metadata = $this->getMetadata();

        $data = array(
            'objectName' => 'post',
            'url'        => array('admin' => 'option=com_easydiscuss&view=post&task=edit&id=' . $this->getMetadata()->parent->id),
            'find'       => 'target',
            'type'       => array(
                'object'     => true,
                'objectName' => 'post',
            ),
            'subtype'    => array('object' => true, 'objectName' => 'EasyDiscuss')
        );

        if ($title = $metadata->parent->title) {
            $data['objectName'] = $title;
        }

        return $this->_getObject($data);
    }

    protected function _actionConfig(KObjectConfig $config)
    {
        if ($this->verb == 'add') {
            $config->append(array('objectName' => 'added'));
        }

        parent::_actionConfig($config);
    }

    protected function _findActivityTarget()
    {
        $query = $this->getObject('lib:database.query.select')
                      ->columns('COUNT(*)')
                      ->table('discuss_posts')
                      ->where('id = :id')
                      ->bind(array('id' => $this->getMetadata()->parent->id));;

        // Need to catch exceptions here as table may not longer exist.
        try {
            $result = $this->getTable()->getAdapter()->select($query, KDatabase::FETCH_FIELD);
        } catch (Exception $e) {
            $result = 0;
        }

        return (bool) $result;
    }
}