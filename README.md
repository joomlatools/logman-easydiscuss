LOGman EasyDiscuss plugin
========================

Plugin for integrating [EasyDiscuss](http://stackideas.com/easydiscuss/) with LOGman. [LOGman](http://joomlatools.com/logman) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/). Create a `composer.json` file inside the root directory of your Joomla! site containing the following code:

```
{
    "require": {        
        "joomlatools/plg_logman_easydiscuss": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Run composer install.

### Package

For downloading an installable package just make use of the **Download ZIP** button located in the right sidebar of this page.

After downloading the package, you may install this plugin using the Joomla! extension manager.

## Usage

After the package is installed, make sure to enable the plugin and that both LOGman and EasyDiscuss are installed.

## Supported activities

The following EasyDiscuss actions are currently logged:

### Posts

* Add
* Edit
* Delete

### Replies

* Add
* Edit
* Delete

## Limitations

* Delete actions are only logged when deleting items from the fronted interface. Events are not triggered when deleting posts and replies from the backend.