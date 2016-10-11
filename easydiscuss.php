<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * EasyDiscuss LOGman Plugin.
 *
 * Provides handlers for dealing with EasyDiscuss events.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */

class PlgLogmanEasydiscuss extends ComLogmanPluginJoomla
{
    protected $_data;

    protected $_new_state = array();

    public function onContentBeforeSave($context, $data, $isNew)
    {
        if (is_array($data)) {
            $isNew = isset($data['id']) ? false : true;
        } elseif (is_object($data)) {
            $isNew = isset($data->id) ? false: true;
        }

        // Keep track of the new state. Some triggers they make are wrong.
        $this->_new_state[] = $isNew;
    }

    public function onContentAfterSave($context, $data, $isNew)
    {
        // Make use of our state value. Some triggers they make are wrong.
        if (count($this->_new_state)) {
            $isNew = array_pop($this->_new_state);
        }

        $parts = explode('.', $context);

        if ($parts[0] == 'com_easydiscuss')
        {
            $parent_id = is_array($data) ? $data['parent_id'] : $data->parent_id;

            // Fix context. Sometimes replies are triggered as posts.
            if (isset($parts[1]) && ($parts[1] == 'post') && $parent_id) {
                $context = $parts[0] . '.reply';
            }
        }

        return parent::onContentAfterSave($context, $data, $isNew);
    }

    public function onContentAfterDelete($context, $data)
    {
        $result = false;

        $parts = explode('.', $context);

        if ($parts[0] == 'com_easydiscuss' && !is_null($data))
        {
            $parent_id = is_array($data) ? $data['parent_id'] : $data->parent_id;

            // Fix context. Somtimes replies are triggered as posts.
            if (isset($parts[1]) && ($parts[1] == 'post') && $parent_id) {
                $context = $parts[0] . '.reply';
            }

            $result = parent::onContentAfterDelete($context, $data);
        }

        return $result;
    }

    protected function _getReplyObjectData($data, $event)
    {
        $object_data = array(
            'id'       => $data->id,
            'name'     => $data->title,
            'metadata' => array(
                'parent' => array('id' => $data->parent_id)
            )
        );

        $post = $this->_getPost($data->parent_id);

        if ($post)
        {
            $object_data['metadata']['parent']['title'] = $post->title;
            $object_data['metadata']['parent']['type']  = $post->post_type;
        }

        return $object_data;
    }

    protected function _getPostObjectData($data, $event)
    {
        // Grab the post row from DB (if possible) since $data is incomplete, e.g. post_type is missing.
        if ($event != 'onContentAfterDelete') {
            $data = $this->_getPost($data->id);
        }

        $object_data = array(
            'id'       => $data->id,
            'name'     => $data->title,
            'metadata' => array(
                'type'     => $data->post_type,
                'category' => array('id' => $data->category_id)
            )
        );

        $category = $this->_query(array('table' => 'discuss_category', 'where' => array('id' => $data->category_id)));

        if ($category) {
            $object_data['metadata']['category']['title'] = $category->title;
        }

        return $object_data;
    }

    protected function _getPost($id)
    {
        return $this->_query(array('table' => 'discuss_posts', 'where' => array('id' => $id)));
    }

    protected function _query($config, $method = KDatabase::FETCH_OBJECT)
    {
        $result = null;

        $config = new KObjectConfig($config);

        $config->append(array('where' => array()));

        if ($config->table)
        {
            $adapter = $this->getObject('lib:database.adapter.mysqli');
            $query   = $this->getObject('lib:database.query.select')->table($config->table)->columns('*');

            foreach ($config->where as $column => $value) {
                $query->where("$column = :$column")->bind(array($column => $value));
            }

            try {
                $result = $adapter->select($query, $method);
            } catch (Exception $e) {
                // Do nothing.
            }
        }

        return $result;
    }
}
