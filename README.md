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

  Triniti\notify\:
    resource: '%kernel.project_dir%/vendor/triniti/notify/src/**/*'

```
