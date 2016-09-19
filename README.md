Authorization
=============

[![Build Status](https://travis-ci.org/judicaelpaquet/IzbergBundle.svg?branch=master)](https://travis-ci.org/judicaelpaquet/IzbergBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/judicaelpaquet/IzbergBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/judicaelpaquet/IzbergBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/judicaelpaquet/IzbergBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/judicaelpaquet/IzbergBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/judicaelpaquet/IzbergBundle/downloads.svg)](https://packagist.org/packages/judicaelpaquet/IzbergBundle)
[![Latest Stable Version](https://poser.pugx.org/judicaelpaquet/IzbergBundle/v/stable.svg)](https://packagist.org/packages/judicaelpaquet/IzbergBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/357fb122-cbd2-4f30-a5a2-a2dcb701b80b/mini.png)](https://insight.sensiolabs.com/projects/357fb122-cbd2-4f30-a5a2-a2dcb701b80b)

This bundle provides various tools to rapidly secure your API with single one annotation


Documentation
-------------

1/ If you want juste indicate that your API is public you must write :
     * @Authorization(access="public")

2/ If you want juste indicate that your API is just allow for the internal call, you have to write :
     * @Authorization(access="private")

3/ If you want create a restriction access by IPs :
     * @Authorization(access="protected", ip="127.0.0.1,192.168.0.1")
     * @Authorization(access="protected", domain="localhost,local.com")

Installation
------------

composer require judicaelpaquet/authorization

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
	