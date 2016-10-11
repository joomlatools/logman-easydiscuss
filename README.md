LOGman EasyDiscuss plugin
========================

Plugin for integrating [EasyDiscuss](http://stackideas.com/easydiscuss/) with LOGman. [LOGman](https://www.joomlatools.com/extensions/logman/) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/) by simply going to the root directory of your Joomla site using the command line and executing the following command:

```
composer require joomlatools/logman-easydiscuss:*
```

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

### Replies

* Add
* Edit

## Limitations

* At the moment and while using the latest stable version of EasyDiscuss at this date (v4.0.10), delete actions are not supported since no data is provided when triggering the onContentAfterDelete event.