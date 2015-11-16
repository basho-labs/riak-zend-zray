# Riak Zend Server Z-Ray Plugin

This is a plugin to help you debug / profile your use of [Riak](http://basho.com/products/riak-kv/) within your PHP application using [Zend's Z-Ray](http://www.zend.com/en/products/server/z-ray) debugging tool. This plugin uses Z-Ray's tracing functions to profile each request made to your Riak servers, giving you insight into what requests are being made, what data is included in those requests and how long those requests are taking from start to finish.

1. [Prerequisites](#prerequisites)
1. [Installation](#installation)
1. [Uninstallation](#uninstallation)
1. [Contributing](#contributing)
1. [License and Authors](#license-and-authors)
1. [Screenshots](#screenshots)

## Prerequisites

  - Server(s) running [Riak KV](http://basho.com/products/riak-kv/) 2 or higher
  - PHP 5.4 or higher
  - End user application using the [Official Riak Client Library](https://github.com/basho/riak-php-client) version 2.1 or higher

## Installation

The simplest way to get the plugin is through the Zend Server plugin gallery within the GUI. Navigate to "Plugins" -> "Gallery", search for "riak", then click install and follow the on-prompts.

To manually install this plugin, download the *.zpk* file attached to a release, navigate within your ZendServer GUI to "Plugins" -> "Manage Plugins", click "Deploy Plugin" and follow the on-screen prompts within the modal window.

## Uninstallation

To uninstall this plugin, return to the "Manage Plugins" screen and click the trash can for the item listed as "Riak".

## Contributing

Basho Labs repos survive because of community contribution. Review the details in [CONTRIBUTING.md](CONTRIBUTING.md) in order to give back to this project.

## License and Authors

The riak-zend-zray project is Open Source software released under the Apache 2.0 License. Please see the [LICENSE](LICENSE) file for full license details.

* Author: [Christopher Mancini](https://github.com/christophermancini)

## Screenshots

![Ring Stats](https://raw.githubusercontent.com/basho-labs/riak-zend-zray/master/screenshots/Ring%20Stats.png) "Review Riak Ring Stats"
![Fetch Performance](https://raw.githubusercontent.com/basho-labs/riak-zend-zray/master/screenshots/Fetched%20Value.png) "Review Fetch Value Performance"
![Store Performance](https://raw.githubusercontent.com/basho-labs/riak-zend-zray/master/screenshots/Z-Ray%20Live%20Store%20Value.png) "Review Store Value Performance Using Z-Ray Live"
