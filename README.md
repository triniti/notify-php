notify-php
=============

[![Build Status](https://api.travis-ci.org/triniti/notify-php.svg)](https://travis-ci.org/triniti/notify-php)
[![Code Climate](https://codeclimate.com/github/triniti/notify-php/badges/gpa.svg)](https://codeclimate.com/github/triniti/notify-php)
[![Test Coverage](https://codeclimate.com/github/triniti/notify-php/badges/coverage.svg)](https://codeclimate.com/github/triniti/notify-php/coverage)

Php library that provides implementations for __triniti:notify__ schemas. Using this library assumes that you've already created and compiled your own pbj classes using the [Pbjc](https://github.com/gdbots/pbjc-php) and are making use of the __"triniti:notify:mixin:*"__ mixins from [triniti/schemas](https://github.com/triniti/schemas).


## Symfony Integration
Enabling these services in a Symfony app is done by importing classes and letting Symfony autoconfigure and autowire them.

__config/packages/notify.yml:__

```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true
    public: false

  Triniti\Notify\:
    resource: '%kernel.project_dir%/vendor/triniti/notify/src/**/*'
    exclude: '%kernel.project_dir%/vendor/triniti/notify/src/Notifier/*'
    #tags:
    #  - {name: monolog.logger, channel: notify}
    #bind:
    #  $logger: '@monolog.logger.notify'

  Triniti\Notify\NotifyLocator: '@Triniti\Notify\ContainerAwareNotifierLocator'

  Triniti\Notify\Notifier\:
    resource: '%kernel.project_dir%/vendor/triniti/notify/src/Notifier/*Notifier'
    public: true
    #tags:
    #  - {name: monolog.logger, channel: notify}
    #bind:
    #  $logger: '@monolog.logger.notify'

  # todo: implement the notifiers
  acme_notify.alexa_notifier: '@Triniti\Notify\Notifier\AlexaNotifier'
  acme_notify.android_notifier: '@Triniti\Notify\Notifier\AndroidNotifier'
  acme_notify.apple_news_notifier: '@Triniti\Notify\Notifier\AppleNewsNotifier'
  acme_notify.browser_notifier: '@Triniti\Notify\Notifier\BrowserNotifier'
  acme_notify.email_notifier: '@Triniti\Notify\Notifier\EmailNotifier'
  acme_notify.ios_notifier: '@Triniti\Notify\Notifier\IosNotifier'
  acme_notify.slack_notifier: '@Triniti\Notify\Notifier\SlackNotifier'
  acme_notify.sms_notifier: '@Triniti\Notify\Notifier\SmsNotifier'

```
